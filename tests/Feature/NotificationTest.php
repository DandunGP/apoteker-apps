<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_and_generate_expiry_notifications()
    {
        Mail::fake();

        // 1. Create users with roles that should receive notifications
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        $pharmacist = User::create([
            'name' => 'Apoteker Test',
            'email' => 'apoteker_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'apoteker',
        ]);

        $cashier = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        // 2. Create medicines
        $medicineA = Medicine::create([
            'kode' => 'MED-001',
            'nama' => 'Medicine Expiring Soon',
            'kategori' => 'Analgesik',
            'satuan' => 'Strip',
            'harga' => 7000,
            'min_stok' => 10,
        ]);

        $medicineB = Medicine::create([
            'kode' => 'MED-002',
            'nama' => 'Medicine Far Expiry',
            'kategori' => 'Analgesik',
            'satuan' => 'Strip',
            'harga' => 7000,
            'min_stok' => 10,
        ]);

        // 3. Create batches
        // Expiring in 15 days
        $batchExpiring = MedicineBatch::create([
            'medicine_id' => $medicineA->id,
            'no_batch' => 'BATCH-EXP-001',
            'stok_awal' => 100,
            'stok_sisa' => 50,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d'),
            'tanggal_kadaluwarsa' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'is_validated' => 1,
        ]);

        // Expiring in 4 months
        $batchSafe = MedicineBatch::create([
            'medicine_id' => $medicineB->id,
            'no_batch' => 'BATCH-SAFE-002',
            'stok_awal' => 100,
            'stok_sisa' => 50,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d'),
            'tanggal_kadaluwarsa' => Carbon::now()->addMonths(4)->format('Y-m-d'),
            'is_validated' => 1,
        ]);

        // 4. Run the artisan command
        $this->artisan('check:expiring-medicines')
            ->expectsOutput('Successfully generated notifications for 1 expiring batches.')
            ->assertExitCode(0);

        // Assert mail was sent to admin and pharmacist
        Mail::assertSent(\App\Mail\ExpiryNotificationMail::class, function ($mail) use ($admin) {
            return $mail->hasTo($admin->email);
        });

        Mail::assertSent(\App\Mail\ExpiryNotificationMail::class, function ($mail) use ($pharmacist) {
            return $mail->hasTo($pharmacist->email);
        });

        // Assert mail was NOT sent to cashier
        Mail::assertNotSent(\App\Mail\ExpiryNotificationMail::class, function ($mail) use ($cashier) {
            return $mail->hasTo($cashier->email);
        });

        // 5. Assert database records
        // Admin Gudang should have 1 notification for BATCH-EXP-001
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'expiry',
            'reference_id' => $batchExpiring->id,
            'reference_type' => 'batch',
        ]);

        // Apoteker should have 1 notification for BATCH-EXP-001
        $this->assertDatabaseHas('notifications', [
            'user_id' => $pharmacist->id,
            'type' => 'expiry',
            'reference_id' => $batchExpiring->id,
            'reference_type' => 'batch',
        ]);

        // Kasir should not have any notification
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $cashier->id,
        ]);

        // Neither admin nor apoteker should have notification for BATCH-SAFE-002
        $this->assertDatabaseMissing('notifications', [
            'reference_id' => $batchSafe->id,
        ]);
    }

    public function test_read_and_redirect_notification()
    {
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Kadaluwarsa',
            'message' => 'Obat Test akan kadaluwarsa',
            'type' => 'expiry',
            'link' => '/admin/monitoring/expiry',
            'reference_id' => 999,
            'reference_type' => 'batch',
        ]);

        // 1. Trying to read while unauthenticated should redirect to login
        $response = $this->get('/notifications/' . $notification->id . '/read');
        if ($response->status() !== 302) {
            $response->dump();
        }
        $response->assertRedirect('/login');

        // 2. Login as the owner of the notification
        $this->actingAs($user);

        // 3. Mark as read and check redirect
        $this->get('/notifications/' . $notification->id . '/read')
            ->assertRedirect('/admin/monitoring/expiry');

        // 4. Assert read_at is filled
        $notification->refresh();
        $this->assertNotNull($notification->read_at);
    }

    public function test_unauthorized_user_cannot_read_notification()
    {
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'one@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'two@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'apoteker',
        ]);

        $notification = Notification::create([
            'user_id' => $user1->id,
            'title' => 'Peringatan Kadaluwarsa',
            'message' => 'Obat Test akan kadaluwarsa',
            'type' => 'expiry',
            'link' => '/admin/monitoring/expiry',
            'reference_id' => 999,
            'reference_type' => 'batch',
        ]);

        // Login as User Two (not the owner)
        $this->actingAs($user2);

        // Access the read route -> should return forbidden (403)
        $this->get('/notifications/' . $notification->id . '/read')
            ->assertStatus(403);

        // Notification read_at should still be null
        $notification->refresh();
        $this->assertNull($notification->read_at);
    }

    public function test_authenticated_user_can_view_notifications_page()
    {
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Kadaluwarsa',
            'message' => 'Obat Test akan kadaluwarsa',
            'type' => 'expiry',
            'link' => '/admin/monitoring/expiry',
            'reference_id' => 999,
            'reference_type' => 'batch',
        ]);

        $this->actingAs($user);

        $response = $this->get('/notifications');
        $response->assertStatus(200);
        $response->assertSee('Obat Test akan kadaluwarsa');
    }

    public function test_unauthenticated_user_is_redirected_from_notifications_page()
    {
        $this->get('/notifications')
            ->assertRedirect('/login');
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'admin_gudang',
        ]);

        $notification1 = Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Kadaluwarsa 1',
            'message' => 'Obat Test 1 akan kadaluwarsa',
            'type' => 'expiry',
            'link' => '/admin/monitoring/expiry',
            'reference_id' => 111,
            'reference_type' => 'batch',
        ]);

        $notification2 = Notification::create([
            'user_id' => $user->id,
            'title' => 'Peringatan Kadaluwarsa 2',
            'message' => 'Obat Test 2 akan kadaluwarsa',
            'type' => 'expiry',
            'link' => '/admin/monitoring/expiry',
            'reference_id' => 222,
            'reference_type' => 'batch',
        ]);

        $this->actingAs($user);

        $this->post('/notifications/mark-all-read')
            ->assertRedirect();

        $notification1->refresh();
        $notification2->refresh();

        $this->assertNotNull($notification1->read_at);
        $this->assertNotNull($notification2->read_at);
    }
}
