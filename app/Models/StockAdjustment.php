<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MedicineBatch;
use App\Models\User;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_batch_id', 'user_id', 'old_stock', 'new_stock', 'difference', 'reason'];

    public function batch()
    {
        return $this->belongsTo(MedicineBatch::class, 'medicine_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
