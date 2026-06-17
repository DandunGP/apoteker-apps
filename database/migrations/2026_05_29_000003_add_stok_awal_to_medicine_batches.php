<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->integer('stok_awal')->default(0)->after('stok_sisa');
        });

        // Backfill: untuk data lama, asumsikan stok_awal = stok_sisa saat ini
        DB::statement('UPDATE medicine_batches SET stok_awal = stok_sisa WHERE stok_awal = 0');
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropColumn('stok_awal');
        });
    }
};
