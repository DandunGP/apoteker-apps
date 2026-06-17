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
        // Semua record yang ada sekarang belum pernah divalidasi secara eksplisit
        // (kolom is_validated diset default false oleh migrasi sebelumnya).
        // Kita anggap data lama sudah "lolos" validasi karena sudah ada sebelum fitur ini.
        DB::statement("UPDATE medicine_batches SET is_validated = 1, validated_at = NOW() WHERE is_validated = 0 AND created_at < NOW()");
    }

    public function down(): void
    {
        // tidak di-rollback karena berisiko menghilangkan stok yang real
    }
};
