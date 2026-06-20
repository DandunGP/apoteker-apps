<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill: tandai semua batch LAMA (sebelum fitur validasi) sebagai sudah tervalidasi
     * agar stok tidak menghilang. Batch baru (setelah fitur ini) akan tetap is_validated=false
     * sampai apoteker memvalidasinya.
     */
    public function up(): void
    {
        $now = now()->toDateTimeString();
        DB::table('medicine_batches')
            ->where('is_validated', 0)
            ->where('created_at', '<', $now)
            ->update([
                'is_validated' => 1,
                'validated_at' => $now,
            ]);
    }

    public function down(): void
    {
        // tidak di-rollback karena berisiko menghilangkan stok yang real
    }
};
