<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // type: 'masuk' | 'keluar'
            $table->enum('type', ['masuk', 'keluar']);
            // category: auto-linked or manual
            $table->string('category'); // e.g. LAPORAN_PENJUALAN, INKASO_SUPPLIER, OPERASIONAL, MODAL_AWAL
            // nullable link to sale for auto-generated entries
            $table->foreignId('sale_id')->nullable()->constrained('sales')->onDelete('set null');
            $table->string('keterangan'); // description shown in table
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
