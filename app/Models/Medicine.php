<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'kode', 'nama', 'kategori', 'satuan', 'harga', 'min_stok'
    ];

    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }

    /**
     * Hanya batch yang sudah divalidasi apoteker — GUNAKAN INI untuk perhitungan stok
     */
    public function validatedBatches()
    {
        return $this->hasMany(MedicineBatch::class)->where('is_validated', true);
    }

    public function getSatuanAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $namaLower = strtolower($this->nama);
        $katLower = strtolower($this->kategori);

        if (str_contains($namaLower, 'drop') || str_contains($namaLower, 'sirup') || str_contains($namaLower, 'solution') || str_contains($namaLower, 'liquid') || str_contains($namaLower, 'botol') || str_contains($namaLower, '30ml')) {
            return 'Botol';
        } elseif (str_contains($katLower, 'alkes') || str_contains($katLower, 'alat kesehatan') || str_contains($namaLower, 'masker') || str_contains($namaLower, 'alat') || str_contains($namaLower, 'spuit') || str_contains($namaLower, 'jarum') || str_contains($namaLower, 'handscoon')) {
            return 'Box';
        } elseif (str_contains($namaLower, 'tablet') || str_contains($namaLower, 'kapsul') || str_contains($namaLower, 'paracetamol') || str_contains($namaLower, 'strip')) {
            return 'Strip';
        } else {
            return 'Box';
        }
    }
}
