<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $medicines = \App\Models\Medicine::with(['batches' => function ($query) {
            $query->where('stok_sisa', '>', 0)->orderBy('tanggal_kadaluwarsa', 'asc');
        }])->get()->map(function ($medicine) {
            $medicine->total_stock = $medicine->batches->sum('stok_sisa');
            return $medicine;
        })->sortByDesc('total_stock')->values();

        $services = \App\Models\Service::all();

        return view('kasir.pos.index', compact('medicines', 'services'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.type' => 'required|in:medicine,service',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:tunai',
        ]);

        \DB::beginTransaction();
        try {
            $total_price = 0;
            $invoice_number = 'INV-' . strtoupper(\Str::random(10));
            
            $sale = \App\Models\Sale::create([
                'user_id' => auth()->id(),
                'invoice_number' => $invoice_number,
                'total_price' => 0, // Placeholder
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->items as $item) {
                if ($item['type'] === 'medicine') {
                    $medicine = \App\Models\Medicine::findOrFail($item['id']);
                    $requested_qty = $item['quantity'];
                    
                    // FEFO: Get batches ordered by expiry date
                    $batches = \App\Models\MedicineBatch::where('medicine_id', $medicine->id)
                        ->where('stok_sisa', '>', 0)
                        ->orderBy('tanggal_kadaluwarsa', 'asc')
                        ->get();

                    $total_available = $batches->sum('stok_sisa');
                    if ($total_available < $requested_qty) {
                        throw new \Exception("Stok tidak mencukupi untuk obat: " . $medicine->nama);
                    }

                    $remaining_to_deduct = $requested_qty;
                    foreach ($batches as $batch) {
                        if ($remaining_to_deduct <= 0) break;

                        $deduct_qty = min($batch->stok_sisa, $remaining_to_deduct);
                        
                        \App\Models\SaleDetail::create([
                            'sale_id' => $sale->id,
                            'medicine_id' => $medicine->id,
                            'batch_id' => $batch->id,
                            'quantity' => $deduct_qty,
                            'price' => $medicine->harga,
                            'subtotal' => $deduct_qty * $medicine->harga,
                        ]);

                        $batch->stok_sisa -= $deduct_qty;
                        $batch->save();

                        $total_price += ($deduct_qty * $medicine->harga);
                        $remaining_to_deduct -= $deduct_qty;
                    }
                } else {
                    // It's a service
                    $service = \App\Models\Service::findOrFail($item['id']);
                    $qty = $item['quantity'];

                    \App\Models\SaleDetail::create([
                        'sale_id' => $sale->id,
                        'service_id' => $service->id,
                        'quantity' => $qty,
                        'price' => $service->harga,
                        'subtotal' => $qty * $service->harga,
                    ]);

                    $total_price += ($qty * $service->harga);
                }
            }

            $sale->update(['total_price' => $total_price]);

            // Auto-record cash flow for this completed sale
            \App\Models\CashFlow::recordSale($sale->fresh());
            
            \DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'invoice' => $invoice_number]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function history()
    {
        $date          = request('date');
        $status        = request('status', 'all');
        $paymentMethod = request('payment_method', 'all');

        // ── Table query (with optional filters) ──────────────────────────
        $query = \App\Models\Sale::with(['user', 'details.medicine', 'details.service'])->latest();

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($paymentMethod && $paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        $sales = $query->get()->map(function ($sale) {
            $sale->total_formatted = 'Rp ' . number_format($sale->total_price, 0, ',', '.');
            $sale->time_formatted  = $sale->created_at->format('d M Y, H:i');
            $sale->items_count     = $sale->details->sum('quantity');
            $sale->status          = strtoupper($sale->status ?? 'completed');
            $sale->payment_method_label = strtoupper($sale->payment_method ?? 'tunai');
            return $sale;
        });

        // ── Stats: all-time when no filter, filtered when filter active ──────
        $hasFilter = !empty($date) || ($status && $status !== 'all') || ($paymentMethod && $paymentMethod !== 'all');

        $statsQuery = \App\Models\Sale::query();

        if (!empty($date)) {
            $statsQuery->whereDate('created_at', $date);
        }

        if ($status && $status !== 'all') {
            $statsQuery->where('status', $status);
        }

        if ($paymentMethod && $paymentMethod !== 'all') {
            $statsQuery->where('payment_method', $paymentMethod);
        }

        // For revenue (Total Penjualan) always exclude refunded
        $revenueQuery = clone $statsQuery;
        if (!$status || $status === 'all') {
            $revenueQuery->where('status', '!=', 'refunded');
        }

        $totalSales   = $revenueQuery->sum('total_price');
        $totalOrders  = $statsQuery->count();
        $avgBasket    = $totalOrders > 0 ? ($totalSales / $totalOrders) : 0;

        // Label context
        $labelParts = [];
        if (!empty($date)) {
            $labelParts[] = \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
        }
        if ($status && $status !== 'all') {
            $labelParts[] = ucfirst($status);
        }
        if ($paymentMethod && $paymentMethod !== 'all') {
            $labelParts[] = strtoupper($paymentMethod);
        }
        $statsLabel = $hasFilter ? implode(', ', $labelParts) : 'Semua Waktu';

        $stats = [
            'total_sales_today'  => 'Rp ' . number_format($totalSales,  0, ',', '.'),
            'total_orders_today' => $totalOrders . ' Transaksi',
            'avg_basket_value'   => 'Rp ' . number_format($avgBasket, 0, ',', '.'),
            'stats_date_label'   => $statsLabel,
        ];

        // ── Pagination ───────────────────────────────────────────────────────
        $currentPage  = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage      = 5;
        $currentItems = $sales->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedSales = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $sales->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginatedSales->appends(request()->all());

        return view('kasir.sales.history', compact('paginatedSales', 'stats', 'date', 'status', 'paymentMethod'));
    }

    public function printReceipt(\App\Models\Sale $sale)
    {
        $sale->load(['user', 'details.medicine', 'details.service']);
        return view('kasir.sales.receipt', compact('sale'));
    }

    public function refund(\App\Models\Sale $sale)
    {
        if ($sale->status === 'refunded') {
            return response()->json(['success' => false, 'message' => 'Transaksi ini sudah direfund sebelumnya.'], 422);
        }

        \DB::beginTransaction();
        try {
            // Restore medicine stocks back to their respective batches
            foreach ($sale->details as $detail) {
                if ($detail->medicine_id && $detail->batch_id) {
                    $batch = \App\Models\MedicineBatch::withoutGlobalScopes()->find($detail->batch_id);
                    if ($batch) {
                        $batch->stok_sisa += $detail->quantity;
                        $batch->save();
                    }
                }
            }

            // Update status to refunded
            $sale->status = 'refunded';
            $sale->save();

            // Auto-record reverse cash flow for this refund
            \App\Models\CashFlow::reverseSale($sale);

            \DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil direfund. Stok obat telah dikembalikan ke inventaris.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function searchMedicines(Request $request)
    {
        $q = $request->get('q', '');
        $selectedCategory = $request->get('category', '');
        $selectedStock = $request->get('stock_status', '');

        $query = \App\Models\Medicine::with(['batches' => function($query) {
            $query->orderBy('tanggal_kadaluwarsa', 'asc');
        }]);

        if (!empty($q)) {
            $query->where(function($sub) use ($q) {
                $sub->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('kategori', 'like', '%' . $q . '%')
                    ->orWhere('kode', 'like', '%' . $q . '%');
            });
        }

        if (!empty($selectedCategory)) {
            $query->where('kategori', $selectedCategory);
        }

        $categories = \App\Models\Medicine::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        $medicines = $query->get()->map(function($m) {
            $m->total_stock = $m->batches->sum('stok_sisa');
            
            $primaryBatch = $m->batches->where('stok_sisa', '>', 0)->first();
            
            if ($primaryBatch) {
                $m->batch_no = $primaryBatch->no_batch;
                $m->expiry_date = $primaryBatch->tanggal_kadaluwarsa;
                
                $days = now()->diffInDays(\Carbon\Carbon::parse($primaryBatch->tanggal_kadaluwarsa), false);
                $m->days_to_expiry = $days;
                
                if ($days < 0) {
                    $m->expiry_status = 'EXPIRED';
                } elseif ($days <= 90) {
                    $m->expiry_status = 'EXPIRING_SOON';
                } else {
                    $m->expiry_status = 'AMAN';
                }
            } else {
                $m->batch_no = 'N/A';
                $m->expiry_date = null;
                $m->days_to_expiry = null;
                $m->expiry_status = 'NO_STOCK';
            }
            
            return $m;
        });

        if (!empty($selectedStock)) {
            $medicines = $medicines->filter(function($m) use ($selectedStock) {
                if ($selectedStock === 'kritis') {
                    return $m->total_stock <= $m->min_stok && $m->total_stock > 0;
                } elseif ($selectedStock === 'habis') {
                    return $m->total_stock == 0;
                } elseif ($selectedStock === 'hampir_ed') {
                    return $m->expiry_status === 'EXPIRING_SOON';
                } elseif ($selectedStock === 'aman') {
                    return $m->total_stock > $m->min_stok && $m->expiry_status === 'AMAN';
                }
                return true;
            });
        }

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = $medicines->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedMedicines = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $medicines->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );

        $paginatedMedicines->appends($request->all());

        return view('kasir.search.index', compact('paginatedMedicines', 'categories', 'q', 'selectedCategory', 'selectedStock'));
    }
}
