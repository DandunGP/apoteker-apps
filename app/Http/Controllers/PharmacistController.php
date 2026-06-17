<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PharmacistController extends Controller
{
    public function verifyStock()
    {
        $medicines = \App\Models\Medicine::with(['batches' => function($q) {
            $q->where('stok_sisa', '>', 0);
        }])->get()->map(function ($medicine) {
            $medicine->total_stock = $medicine->batches->sum('stok_sisa');
            return $medicine;
        });

        return view('apoteker.stock.index', compact('medicines'));
    }

    public function expiryAlerts()
    {
        // Get all batches with stock, ordered by expiry
        $batches = \App\Models\MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->get()
            ->map(function($batch) {
                $expiry = \Carbon\Carbon::parse($batch->tanggal_kadaluwarsa);
                $batch->diff_days = now()->diffInDays($expiry, false);
                $batch->expiry_formatted = $expiry->format('d M Y');
                return $batch;
            });

        $expiredCount = $batches->filter(fn($b) => $b->diff_days < 0)->count();
        $nearExpiryCount = $batches->filter(fn($b) => $b->diff_days >= 0 && $b->diff_days < 90)->count();
        $safeCount = $batches->count() - $expiredCount - $nearExpiryCount;

        return view('apoteker.expiry.index', compact('batches', 'expiredCount', 'nearExpiryCount', 'safeCount'));
    }

    public function salesReport(\Illuminate\Http\Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $sales = \App\Models\Sale::with(['user', 'details.medicine', 'details.service'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $totalRevenue = $sales->sum('total_price');
        $stats = [
            'total_revenue_raw' => $totalRevenue,
            'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'total_revenue_print' => number_format($totalRevenue, 0, ',', '.'),
            'total_transactions' => $sales->count(),
            'total_items' => $sales->sum(fn($s) => $s->details->sum('quantity'))
        ];

        $sales = $sales->map(function($sale) {
            $sale->total_formatted = 'Rp ' . number_format($sale->total_price, 0, ',', '.');
            $sale->total_print = number_format($sale->total_price, 0, ',', '.');
            $sale->time_formatted = $sale->created_at->format('d/m/Y H:i');
            $sale->date_formatted = $sale->created_at->format('d/m/Y');
            return $sale;
        });

        return view('apoteker.report.index', compact('sales', 'stats', 'startDate', 'endDate'));
    }
}
