<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'kontak_person', 'no_telepon', 'alamat', 'status'
    ];

    /**
     * Get initials/prefix dynamically (e.g. PT, PB, CV) based on supplier name.
     */
    public function getPrefixAttribute()
    {
        $parts = explode(' ', trim($this->nama));
        if (count($parts) > 0) {
            $first = str_replace(['.', ','], '', $parts[0]);
            if (in_array(strtoupper($first), ['PT', 'CV', 'PBF', 'UD', 'Toko'])) {
                return $first;
            }
        }
        return 'PT'; // Default fallback
    }

    /**
     * Get stripped name (without CV, PT, etc.) for display.
     */
    public function getCleanNameAttribute()
    {
        $name = trim($this->nama);
        $prefixes = ['PT.', 'PT', 'CV.', 'CV', 'PBF.', 'PBF', 'UD.', 'UD'];
        foreach ($prefixes as $p) {
            if (str_starts_with(strtoupper($name), strtoupper($p))) {
                return trim(substr($name, strlen($p)));
            }
        }
        return $name;
    }
}
