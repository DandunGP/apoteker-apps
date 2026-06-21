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
            $this->error("No medicine batch found in the database. Please ensure the database is seeded.");
            return Command::FAILURE;
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
