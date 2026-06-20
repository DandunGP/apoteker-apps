<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class POSTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_page_is_accessible_by_cashier()
    {
        $cashier = User::create([
            'name' => 'Cashier Test',
            'email' => 'cashier@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $this->actingAs($cashier)
            ->get('/kasir/pos')
            ->assertStatus(200);
    }

    public function test_checkout_successful_with_cash()
    {
        $cashier = User::create([
            'name' => 'Cashier Test',
            'email' => 'cashier@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $medicine = Medicine::create([
            'kode' => 'MED-001',
            'nama' => 'Paracetamol',
            'kategori' => 'Analgesik',
            'satuan' => 'Strip',
            'harga' => 5000,
            'min_stok' => 5,
        ]);

        $batch = MedicineBatch::create([
            'medicine_id' => $medicine->id,
            'no_batch' => 'B-001',
            'stok_awal' => 10,
            'stok_sisa' => 10,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d'),
            'tanggal_kadaluwarsa' => Carbon::now()->addYear()->format('Y-m-d'),
            'is_validated' => 1,
        ]);

        $this->actingAs($cashier)
            ->postJson('/kasir/pos/checkout', [
                'items' => [
                    [
                        'type' => 'medicine',
                        'id' => $medicine->id,
                        'quantity' => 2,
                    ]
                ],
                'payment_method' => 'tunai'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Transaksi berhasil!'
            ]);

        $batch->refresh();
        $this->assertEquals(8, $batch->stok_sisa);
    }

    public function test_checkout_fails_with_qris()
    {
        $cashier = User::create([
            'name' => 'Cashier Test',
            'email' => 'cashier@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $medicine = Medicine::create([
            'kode' => 'MED-001',
            'nama' => 'Paracetamol',
            'kategori' => 'Analgesik',
            'satuan' => 'Strip',
            'harga' => 5000,
            'min_stok' => 5,
        ]);

        $this->actingAs($cashier)
            ->postJson('/kasir/pos/checkout', [
                'items' => [
                    [
                        'type' => 'medicine',
                        'id' => $medicine->id,
                        'quantity' => 2,
                    ]
                ],
                'payment_method' => 'qris'
            ])
            ->assertStatus(422); // Validation error
    }

    public function test_checkout_fails_when_out_of_stock()
    {
        $cashier = User::create([
            'name' => 'Cashier Test',
            'email' => 'cashier@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $medicine = Medicine::create([
            'kode' => 'MED-001',
            'nama' => 'Paracetamol',
            'kategori' => 'Analgesik',
            'satuan' => 'Strip',
            'harga' => 5000,
            'min_stok' => 5,
        ]);

        // Medicine batch exists but has stok_sisa = 0
        MedicineBatch::create([
            'medicine_id' => $medicine->id,
            'no_batch' => 'B-001',
            'stok_awal' => 10,
            'stok_sisa' => 0,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d'),
            'tanggal_kadaluwarsa' => Carbon::now()->addYear()->format('Y-m-d'),
            'is_validated' => 1,
        ]);

        $this->actingAs($cashier)
            ->postJson('/kasir/pos/checkout', [
                'items' => [
                    [
                        'type' => 'medicine',
                        'id' => $medicine->id,
                        'quantity' => 1,
                    ]
                ],
                'payment_method' => 'tunai'
            ])
            ->assertStatus(422); // 422 Unprocessable because Exception throws inside controller
    }
}
