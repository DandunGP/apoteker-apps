<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->string('no_faktur')->nullable()->after('medicine_id');
            $table->enum('tipe_faktur', ['Lunas', 'Tempo', 'Titipan'])->default('Lunas')->after('no_faktur');
            $table->date('tanggal_jatuh_tempo')->nullable()->after('tipe_faktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropColumn(['no_faktur', 'tipe_faktur', 'tanggal_jatuh_tempo']);
        });
    }
};
