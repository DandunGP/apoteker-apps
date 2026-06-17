<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $period   = $request->get('period', 'harian');
        $dateFrom = $request->get('date_from', '');
        $dateTo   = $request->get('date_to', '');
        $type     = $request->get('type', 'semua');

        // ---------- Build date range ----------
        if (!empty($dateFrom) && !empty($dateTo)) {
            $rangeFrom = Carbon::parse($dateFrom)->startOfDay();
            $rangeTo   = Carbon::parse($dateTo)->endOfDay();
            $period    = 'custom';
        } else {
            switch ($period) {
                case 'mingguan':
                    $rangeFrom = Carbon::now()->startOfWeek();
                    break;
                case 'bulanan':
                    $rangeFrom = Carbon::now()->startOfMonth();
                    break;
                default:
                    $rangeFrom = Carbon::now()->startOfDay();
                    $period    = 'harian';
                    break;
            }
            $rangeTo = Carbon::now()->endOfDay();
        }

        // ---------- SUMMARY STATS ----------
        // Total Masuk = semua penjualan (non-refunded) dalam rentang + masuk manual (non-sale)
        $salesMasuk = Sale::whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->where('status', '!=', 'refunded')
            ->sum('total_price');

        $manualMasuk = CashFlow::where('type', 'masuk')
            ->whereNull('sale_id')                  // manual entries only, avoid double-count
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->sum('nominal');

        $totalMasuk = $salesMasuk + $manualMasuk;

        // Total Keluar = semua entri keluar dari cash_flows dalam rentang
        $totalKeluar = CashFlow::where('type', 'keluar')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->sum('nominal');

        // Tambahkan refund sebagai pengurang (jika tidak masuk tabel keluar)
        $refundedKeluar = Sale::whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->where('status', 'refunded')
            ->sum('total_price');
        $totalKeluar += $refundedKeluar;

        $saldo = $totalMasuk - $totalKeluar;

        // Last month comparison
        $lmFrom   = Carbon::now()->subMonth()->startOfMonth();
        $lmTo     = Carbon::now()->subMonth()->endOfMonth();
        $lmMasuk  = Sale::whereDate('created_at', '>=', $lmFrom->toDateString())
            ->whereDate('created_at', '<=', $lmTo->toDateString())
            ->where('status', '!=', 'refunded')
            ->sum('total_price');
        $lmKeluar = CashFlow::where('type', 'keluar')
            ->whereBetween('created_at', [$lmFrom, $lmTo])
            ->sum('nominal');

        // Fallback demo jika belum ada data sama sekali
        if ($totalMasuk == 0 && $totalKeluar == 0) {
            $totalMasuk  = 0;
            $totalKeluar = 0;
            $saldo       = 0;
            $lmMasuk     = 0;
            $lmKeluar    = 0;
        }

        $masukPct  = $lmMasuk  > 0 ? round((($totalMasuk  - $lmMasuk)  / $lmMasuk)  * 100, 1) : 0;
        $keluarPct = $lmKeluar > 0 ? round((($totalKeluar - $lmKeluar) / $lmKeluar) * 100, 1) : 0;

        $stats = [
            'total_masuk'    => 'Rp ' . number_format($totalMasuk,  0, ',', '.'),
            'total_keluar'   => 'Rp ' . number_format($totalKeluar, 0, ',', '.'),
            'saldo'          => 'Rp ' . number_format(abs($saldo),  0, ',', '.'),
            'saldo_positive' => $saldo >= 0,
            'masuk_pct'      => $masukPct,
            'keluar_pct'     => $keluarPct,
        ];

        // ---------- TABLE ENTRIES ----------
        // Gabungkan: sales sebagai entri MASUK + cash_flows manual
        // 1. Ambil sales dari rentang → jadikan virtual CashFlow entries
        $salesEntries = Sale::with('user')
            ->whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->latest()
            ->get()
            ->map(function ($sale) {
                return (object)[
                    'id'               => 'sale_' . $sale->id,
                    'type'             => $sale->status === 'refunded' ? 'keluar' : 'masuk',
                    'category'         => 'LAPORAN_PENJUALAN',
                    'category_label'   => 'Laporan Penjualan',
                    'keterangan'       => ($sale->status === 'refunded' ? 'Refund ' : '') . 'Penjualan (' . $sale->invoice_number . ')',
                    'nominal'          => $sale->total_price,
                    'nominal_formatted'=> 'Rp ' . number_format($sale->total_price, 0, ',', '.'),
                    'time_formatted'   => $sale->created_at->format('d/m/Y H:i'),
                    'sale_id'          => $sale->id,
                    'created_at'       => $sale->created_at,
                    'is_auto'          => true,
                ];
            });

        // 2. Ambil manual cash_flows (non-sale) dari rentang
        $manualEntries = CashFlow::with('user', 'sale')
            ->whereNull('sale_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])
            ->latest()
            ->get()
            ->map(function ($cf) {
                $cf->nominal_formatted = 'Rp ' . number_format($cf->nominal, 0, ',', '.');
                $cf->time_formatted    = $cf->created_at->format('d/m/Y H:i');
                $cf->category_label    = CashFlow::categoryLabels()[$cf->category] ?? $cf->category;
                $cf->is_auto           = false;
                return $cf;
            });

        // 3. Gabungkan dan sort terbaru dulu
        $allEntries = $salesEntries->concat($manualEntries)
            ->sortByDesc('created_at')
            ->values();

        // 4. Filter tipe jika diminta
        if ($type !== 'semua') {
            $allEntries = $allEntries->filter(fn($e) => $e->type === $type)->values();
        }

        // ---------- Manual pagination ----------
        $perPage     = 5;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $pageItems   = $allEntries->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $pageItems,
            $allEntries->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginated->appends($request->all());

        $categories  = CashFlow::categoryLabels();
        $lastUpdated = CashFlow::latest()->first()?->created_at?->format('d M Y, H:i')
                    ?? Sale::latest()->first()?->created_at?->format('d M Y, H:i')
                    ?? now()->format('d M Y, H:i');

        return view('kasir.cashflow.index', compact(
            'paginated', 'stats', 'period', 'dateFrom', 'dateTo', 'type',
            'categories', 'lastUpdated'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:masuk,keluar',
            'category'   => 'required|string',
            'keterangan' => 'required|string|max:255',
            'nominal'    => 'required|numeric|min:1',
        ]);

        CashFlow::create([
            'user_id'    => auth()->id(),
            'type'       => $request->type,
            'category'   => $request->category,
            'keterangan' => $request->keterangan,
            'nominal'    => $request->nominal,
        ]);

        return redirect()
            ->route('kasir.cashflow')
            ->with('success', 'Arus kas berhasil dicatat.');
    }

    public function export(Request $request)
    {
        $period   = $request->get('period', 'harian');
        $dateFrom = $request->get('date_from', '');
        $dateTo   = $request->get('date_to', '');
        $type     = $request->get('type', 'semua');

        // Same date range logic as index
        if (!empty($dateFrom) && !empty($dateTo)) {
            $rangeFrom = Carbon::parse($dateFrom)->startOfDay();
            $rangeTo   = Carbon::parse($dateTo)->endOfDay();
            $period    = 'custom';
        } else {
            switch ($period) {
                case 'mingguan':
                    $rangeFrom = Carbon::now()->startOfWeek();
                    break;
                case 'bulanan':
                    $rangeFrom = Carbon::now()->startOfMonth();
                    break;
                default:
                    $rangeFrom = Carbon::now()->startOfDay();
                    $period    = 'harian';
                    break;
            }
            $rangeTo = Carbon::now()->endOfDay();
        }

        // Summary stats
        $salesMasuk  = \App\Models\Sale::whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->where('status', '!=', 'refunded')->sum('total_price');
        $manualMasuk = CashFlow::where('type', 'masuk')->whereNull('sale_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])->sum('nominal');
        $totalMasuk  = $salesMasuk + $manualMasuk;

        $totalKeluar = CashFlow::where('type', 'keluar')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])->sum('nominal');
        $refundKeluar = \App\Models\Sale::whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->where('status', 'refunded')->sum('total_price');
        $totalKeluar += $refundKeluar;
        $saldo = $totalMasuk - $totalKeluar;

        // Table entries (same logic as index)
        $salesEntries = \App\Models\Sale::with('user')
            ->whereDate('created_at', '>=', $rangeFrom->toDateString())
            ->whereDate('created_at', '<=', $rangeTo->toDateString())
            ->latest()->get()
            ->map(fn($sale) => (object)[
                'type'           => $sale->status === 'refunded' ? 'keluar' : 'masuk',
                'category'       => 'LAPORAN_PENJUALAN',
                'category_label' => 'Laporan Penjualan',
                'keterangan'     => ($sale->status === 'refunded' ? 'Refund ' : '') . 'Penjualan (' . $sale->invoice_number . ')',
                'nominal'        => $sale->total_price,
                'nominal_formatted' => 'Rp ' . number_format($sale->total_price, 0, ',', '.'),
                'time_formatted' => $sale->created_at->format('d/m/Y H:i'),
                'is_auto'        => true,
                'created_at'     => $sale->created_at,
            ]);

        $manualEntries = CashFlow::with('user')->whereNull('sale_id')
            ->whereBetween('created_at', [$rangeFrom, $rangeTo])->latest()->get()
            ->map(function ($cf) {
                $cf->nominal_formatted = 'Rp ' . number_format($cf->nominal, 0, ',', '.');
                $cf->time_formatted    = $cf->created_at->format('d/m/Y H:i');
                $cf->category_label    = CashFlow::categoryLabels()[$cf->category] ?? $cf->category;
                $cf->is_auto           = false;
                return $cf;
            });

        $allEntries = $salesEntries->concat($manualEntries)->sortByDesc('created_at')->values();

        if ($type !== 'semua') {
            $allEntries = $allEntries->filter(fn($e) => $e->type === $type)->values();
        }

        $periodLabel = match($period) {
            'mingguan' => 'Mingguan',
            'bulanan'  => 'Bulanan',
            'custom'   => $rangeFrom->format('d M Y') . ' – ' . $rangeTo->format('d M Y'),
            default    => 'Harian (' . now()->format('d M Y') . ')',
        };

        $stats = [
            'total_masuk'  => 'Rp ' . number_format($totalMasuk,  0, ',', '.'),
            'total_keluar' => 'Rp ' . number_format($totalKeluar, 0, ',', '.'),
            'saldo'        => 'Rp ' . number_format(abs($saldo),  0, ',', '.'),
            'saldo_positive' => $saldo >= 0,
        ];

        return view('kasir.cashflow.export', compact(
            'allEntries', 'stats', 'periodLabel', 'rangeFrom', 'rangeTo', 'type'
        ));
    }
}

