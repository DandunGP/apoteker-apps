<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id', 'supplier_id', 'no_batch', 'tanggal_masuk', 'tanggal_kadaluwarsa',
        'stok_sisa', 'stok_awal',
        'no_faktur', 'tipe_faktur', 'tanggal_jatuh_tempo',
        'gudang_status', 'is_validated', 'validated_at', 'validated_by',
        'validation_notes', 'physical_qty', 'physical_batch',
        'physical_expiry', 'kondisi', 'kesesuaian'
    ];

    protected $casts = [
        'is_validated' => 'boolean',
        'kesesuaian'   => 'boolean',
        'validated_at' => 'datetime',
    ];

    protected static function booted()
    {
        // By default, only load/consider batches that have been validated by the Pharmacist
        static::addGlobalScope('validated', function ($builder) {
            $builder->where('is_validated', true);
        });
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    public function validator()
    {
        return $this->belongsTo(\App\Models\User::class, 'validated_by');
    }

    /**
     * Scope: hanya batch yang sudah divalidasi apoteker — gunakan ini di SEMUA query stok
     */
    public function scopeValidated($query)
    {
        return $query->where('is_validated', true);
    }

    /**
     * Scope: batch yang belum divalidasi (menunggu apoteker)
     */
    public function scopePendingValidation($query)
    {
        return $query->where('is_validated', false);
    }
}
