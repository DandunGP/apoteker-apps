<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->enum('gudang_status', ['pending', 'diterima', 'ditolak'])->default('diterima')->after('tanggal_jatuh_tempo');
            $table->boolean('is_validated')->default(false)->after('gudang_status');
            $table->timestamp('validated_at')->nullable()->after('is_validated');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete()->after('validated_at');
            $table->text('validation_notes')->nullable()->after('validated_by');
            // Per-item physical check fields (stored as JSON per item in faktur)
            $table->string('physical_qty')->nullable()->after('validation_notes');
            $table->string('physical_batch')->nullable()->after('physical_qty');
            $table->string('physical_expiry')->nullable()->after('physical_batch');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable()->after('physical_expiry');
            $table->boolean('kesesuaian')->default(true)->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn([
                'gudang_status', 'is_validated', 'validated_at', 'validated_by',
                'validation_notes', 'physical_qty', 'physical_batch',
                'physical_expiry', 'kondisi', 'kesesuaian'
            ]);
        });
    }
};
