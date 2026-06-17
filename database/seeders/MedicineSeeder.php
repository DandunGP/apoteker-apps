<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            // === OBAT KERAS ===
            [
                'kode' => 'OBK-001',
                'nama' => 'Amoxicillin 500mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 8500.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'OBK-002',
                'nama' => 'Metformin 500mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 6000.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBK-003',
                'nama' => 'Amlodipine 5mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 7500.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBK-004',
                'nama' => 'Cefadroxil 500mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 12500.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'OBK-005',
                'nama' => 'Methylprednisolone 4mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 9000.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'OBK-006',
                'nama' => 'Ketoconazole 200mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 11000.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'OBK-007',
                'nama' => 'Asam Mefenamat 500mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 8000.00,
                'min_stok' => 25,
            ],
            [
                'kode' => 'OBK-008',
                'nama' => 'Atorvastatin 10mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 18000.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'OBK-009',
                'nama' => 'Ranitidine 150mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Strip',
                'harga' => 6500.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'OBK-010',
                'nama' => 'Omeprazole 20mg',
                'kategori' => 'Obat Keras',
                'satuan' => 'Box',
                'harga' => 35000.00,
                'min_stok' => 5,
            ],

            // === OBAT BEBAS ===
            [
                'kode' => 'OBB-001',
                'nama' => 'Paracetamol 500mg',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 4500.00,
                'min_stok' => 30,
            ],
            [
                'kode' => 'OBB-002',
                'nama' => 'Ibuprofen 200mg',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 5500.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBB-003',
                'nama' => 'Antimo Tablet',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 5000.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'OBB-004',
                'nama' => 'Promag Tablet',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 8500.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBB-005',
                'nama' => 'Diapet Kapsul',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 7000.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'OBB-006',
                'nama' => 'Bodrex Tablet',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 4000.00,
                'min_stok' => 25,
            ],
            [
                'kode' => 'OBB-007',
                'nama' => 'Mylanta Sirup 150ml',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Botol',
                'harga' => 42000.00,
                'min_stok' => 8,
            ],
            [
                'kode' => 'OBB-008',
                'nama' => 'Sanaflu Tablet',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 3500.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBB-009',
                'nama' => 'Decolgen Tablet',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 4000.00,
                'min_stok' => 20,
            ],
            [
                'kode' => 'OBB-010',
                'nama' => 'Panadol Extra',
                'kategori' => 'Obat Bebas',
                'satuan' => 'Strip',
                'harga' => 11500.00,
                'min_stok' => 25,
            ],

            // === OBAT BEBAS TERBATAS (OBAT TERBATAS) ===
            [
                'kode' => 'OBT-001',
                'nama' => 'Betadine Solusi Antiseptik 30ml',
                'kategori' => 'Obat Terbatas',
                'satuan' => 'Botol',
                'harga' => 28500.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'OBT-002',
                'nama' => 'Insto Tetes Mata 7.5ml',
                'kategori' => 'Obat Terbatas',
                'satuan' => 'Botol',
                'harga' => 16500.00,
                'min_stok' => 12,
            ],
            [
                'kode' => 'OBT-003',
                'nama' => 'CTM Tablet 4mg',
                'kategori' => 'Obat Terbatas',
                'satuan' => 'Strip',
                'harga' => 3000.00,
                'min_stok' => 30,
            ],
            [
                'kode' => 'OBT-004',
                'nama' => 'Rohto Cool Tetes Mata',
                'kategori' => 'Obat Terbatas',
                'satuan' => 'Botol',
                'harga' => 18000.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'OBT-005',
                'nama' => 'Dextromethorphan Sirup 60ml',
                'kategori' => 'Obat Terbatas',
                'satuan' => 'Botol',
                'harga' => 15000.00,
                'min_stok' => 10,
            ],

            // === SUPLEMEN ===
            [
                'kode' => 'SUP-001',
                'nama' => 'Vitamin C 500mg Sweetlet',
                'kategori' => 'Suplemen',
                'satuan' => 'Strip',
                'harga' => 6000.00,
                'min_stok' => 30,
            ],
            [
                'kode' => 'SUP-002',
                'nama' => 'Sangobion Kapsul',
                'kategori' => 'Suplemen',
                'satuan' => 'Strip',
                'harga' => 22000.00,
                'min_stok' => 15,
            ],
            [
                'kode' => 'SUP-003',
                'nama' => 'Vitamin D3 1000IU',
                'kategori' => 'Suplemen',
                'satuan' => 'Botol',
                'harga' => 95000.00,
                'min_stok' => 5,
            ],
            [
                'kode' => 'SUP-004',
                'nama' => 'Neurobion Forte Tablet',
                'kategori' => 'Suplemen',
                'satuan' => 'Strip',
                'harga' => 46000.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'SUP-005',
                'nama' => 'Enervon-C Multivitamin',
                'kategori' => 'Suplemen',
                'satuan' => 'Strip',
                'harga' => 7500.00,
                'min_stok' => 20,
            ],

            // === ALAT KESEHATAN ===
            [
                'kode' => 'ALK-001',
                'nama' => 'Spuit 3cc Terumo',
                'kategori' => 'Alat Kesehatan',
                'satuan' => 'Box',
                'harga' => 145000.00,
                'min_stok' => 3,
            ],
            [
                'kode' => 'ALK-002',
                'nama' => 'Masker Medis Sensi 3-Ply',
                'kategori' => 'Alat Kesehatan',
                'satuan' => 'Box',
                'harga' => 45000.00,
                'min_stok' => 10,
            ],
            [
                'kode' => 'ALK-003',
                'nama' => 'Handscoon Latex Sensi',
                'kategori' => 'Alat Kesehatan',
                'satuan' => 'Box',
                'harga' => 85000.00,
                'min_stok' => 5,
            ],
            [
                'kode' => 'ALK-004',
                'nama' => 'Termometer Digital Omron',
                'kategori' => 'Alat Kesehatan',
                'satuan' => 'Box',
                'harga' => 78000.00,
                'min_stok' => 4,
            ],
            [
                'kode' => 'ALK-005',
                'nama' => 'Jarum Suntik Terumo 23G',
                'kategori' => 'Alat Kesehatan',
                'satuan' => 'Box',
                'harga' => 120000.00,
                'min_stok' => 3,
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::updateOrCreate(
                ['kode' => $med['kode']],
                $med
            );
        }
    }
}
