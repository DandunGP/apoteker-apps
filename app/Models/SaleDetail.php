<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = [
        'sale_id', 'medicine_id', 'batch_id', 'service_id', 'quantity', 'price', 'subtotal'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch()
    {
        return $this->belongsTo(MedicineBatch::class, 'batch_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
