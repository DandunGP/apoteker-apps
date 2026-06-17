<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashFlow extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'category',
        'sale_id',
        'keterangan',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    // Category constants
    const CATEGORY_PENJUALAN = 'LAPORAN_PENJUALAN';
    const CATEGORY_INKASO = 'INKASO_SUPPLIER';
    const CATEGORY_OPERASIONAL = 'OPERASIONAL';
    const CATEGORY_MODAL_AWAL = 'MODAL_AWAL';

    // Human-readable category labels
    public static function categoryLabels(): array
    {
        return [
            self::CATEGORY_PENJUALAN  => 'Laporan Penjualan',
            self::CATEGORY_INKASO     => 'Inkaso [Pembayaran PBF]',
            self::CATEGORY_OPERASIONAL => 'Operasional',
            self::CATEGORY_MODAL_AWAL  => 'Modal Awal',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Auto-create a cash flow entry when a sale is completed.
     */
    public static function recordSale(Sale $sale): void
    {
        static::create([
            'user_id'    => $sale->user_id,
            'type'       => 'masuk',
            'category'   => self::CATEGORY_PENJUALAN,
            'sale_id'    => $sale->id,
            'keterangan' => 'Penjualan (' . $sale->invoice_number . ')',
            'nominal'    => $sale->total_price,
        ]);
    }

    /**
     * Auto-reverse a cash flow entry when a sale is refunded.
     */
    public static function reverseSale(Sale $sale): void
    {
        static::create([
            'user_id'    => $sale->user_id,
            'type'       => 'keluar',
            'category'   => self::CATEGORY_PENJUALAN,
            'sale_id'    => $sale->id,
            'keterangan' => 'Refund Penjualan (' . $sale->invoice_number . ')',
            'nominal'    => $sale->total_price,
        ]);
    }
}
