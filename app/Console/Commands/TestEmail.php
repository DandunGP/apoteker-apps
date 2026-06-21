<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\MedicineBatch;
use App\Mail\ExpiryNotificationMail;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test expiry notification email to the specified address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Sending test email to {$email}...");

        // Try to fetch the first user, or create a mock one
        $user = User::first() ?? new User([
            'name' => 'User Tester',
            'email' => $email
        ]);
        $user->email = $email; // Ensure it sends to target

        // Fetch a batch to mock the mail data
        $batch = MedicineBatch::with('medicine')->first();
        if (!$batch) {
            $medicine = \App\Models\Medicine::first();
            if (!$medicine) {
                $this->error("No medicines found. Please run db:seed first.");
                return Command::FAILURE;
            }
            $batch = MedicineBatch::create([
                'medicine_id' => $medicine->id,
                'no_batch' => 'BATCH-TEST-999',
                'stok_awal' => 100,
                'stok_sisa' => 45,
                'tanggal_masuk' => now()->format('Y-m-d'),
                'tanggal_kadaluwarsa' => now()->addDays(20)->format('Y-m-d'),
                'is_validated' => 1,
            ]);
            $batch->load('medicine');
        }

        $medicineName = $batch->medicine->nama ?? 'Obat Test';
        $message = "Obat {$medicineName} (Batch {$batch->no_batch}) akan kadaluwarsa dalam 15 hari. Sisa stok: {$batch->stok_sisa}.";

        try {
            Mail::to($email)->send(new ExpiryNotificationMail($user, $batch, $message));
            $this->info("Test email successfully sent!");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
