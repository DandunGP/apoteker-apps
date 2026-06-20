<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckExpiringMedicines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expiring-medicines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan medicine batches nearing expiry and create database notifications for warehouse admins and pharmacists';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $threeMonthsFromNow = $now->copy()->addMonths(3);

        // Fetch batches that are active and expire in <= 3 months
        $expiringBatches = \App\Models\MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $threeMonthsFromNow)
            ->get();

        $users = \App\Models\User::whereIn('role', ['admin_gudang', 'apoteker'])->get();

        if ($expiringBatches->isEmpty()) {
            $this->info('No expiring medicine batches found.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiringBatches as $batch) {
            $medicineName = $batch->medicine->nama ?? 'Obat';
            $expiryDate = \Carbon\Carbon::parse($batch->tanggal_kadaluwarsa);
            $daysLeft = $now->diffInDays($expiryDate, false);
            $unit = $batch->medicine->satuan ?? 'Box';

            if ($daysLeft <= 0) {
                $message = "Obat {$medicineName} (Batch {$batch->no_batch}) sudah kadaluwarsa sejak {$expiryDate->translatedFormat('d M Y')}. Sisa stok: {$batch->stok_sisa} {$unit}.";
            } else {
                $message = "Obat {$medicineName} (Batch {$batch->no_batch}) akan kadaluwarsa dalam {$daysLeft} hari ({$expiryDate->translatedFormat('d M Y')}). Sisa stok: {$batch->stok_sisa} {$unit}.";
            }

            foreach ($users as $user) {
                // Clear any existing unread notification for the same batch to bubble the fresh alert
                \App\Models\Notification::where('user_id', $user->id)
                    ->where('reference_id', $batch->id)
                    ->where('reference_type', 'batch')
                    ->whereNull('read_at')
                    ->delete();

                // Create fresh notification
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Peringatan Kadaluwarsa: ' . $medicineName,
                    'message' => $message,
                    'type' => 'expiry',
                    'link' => '/admin/monitoring/expiry',
                    'reference_id' => $batch->id,
                    'reference_type' => 'batch',
                ]);
            }
            $count++;
        }

        $this->info("Successfully generated notifications for {$count} expiring batches.");
        return Command::SUCCESS;
    }
}
