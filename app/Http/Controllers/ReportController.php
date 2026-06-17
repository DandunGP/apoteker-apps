<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\StockAdjustment;
use Carbon\Carbon;
use Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Calculate Dynamic Total Valuation of Inventory
        $medicinesWithStock = Medicine::with('batches')->get();
        $totalValuation = 0;
        foreach ($medicinesWithStock as $med) {
            $stock = $med->batches->sum('stok_sisa');
            $totalValuation += ($stock * $med->harga);
        }

        // 2. Fetch drop downs options
        $categories = Medicine::distinct('kategori')->pluck('kategori');
        $suppliers = Supplier::where('status', 'Aktif')->get();
        
        // 3. Expiry actions risk count
        $now = Carbon::now();
        $kritisCount = MedicineBatch::where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now->copy()->addMonths(3))
            ->count();

        $expiredBatches = MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now->copy()->addMonths(3))
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->get();

        // 4. Handle dynamic datagrid preview if requested
        $previewData = null;
        $reportType = $request->input('report_type');
        
        if ($reportType === 'stock') {
            $cat = $request->input('category', 'all');
            $status = $request->input('status', 'all');
            
            $query = Medicine::with('batches');
            if ($cat !== 'all') {
                $query->where('kategori', $cat);
            }
            
            $items = $query->get()->map(function($m) {
                $stock = $m->batches->sum('stok_sisa');
                return (object)[
                    'kode' => $m->kode,
                    'nama' => $m->nama,
                    'kategori' => $m->kategori,
                    'satuan' => $m->satuan,
                    'stok' => $stock,
                    'min_stok' => $m->min_stok,
                    'harga' => $m->harga,
                    'total_nilai' => $stock * $m->harga,
                    'is_kritis' => $stock <= $m->min_stok
                ];
            });

            if ($status === 'kritis') {
                $items = $items->filter(function($i) { return $i->is_kritis; });
            } elseif ($status === 'aman') {
                $items = $items->filter(function($i) { return !$i->is_kritis; });
            }
            
            $previewData = (object)[
                'type' => 'stock',
                'title' => 'Laporan Stok Inventaris',
                'headers' => ['No', 'Kode Obat', 'Nama Obat', 'Kategori', 'Satuan', 'Stok Saat Ini', 'Stok Minimum', 'Harga Jual', 'Total Nilai Aset'],
                'rows' => $items->values()
            ];
            
        } elseif ($reportType === 'incoming') {
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $supplierId = $request->input('supplier_id', 'all');
            
            $query = MedicineBatch::with('medicine')->where('stok_sisa', '>', 0);
            if ($dateFrom) {
                $query->whereDate('tanggal_masuk', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('tanggal_masuk', '<=', $dateTo);
            }
            
            $items = $query->get();
            
            // Note: Since Supplier relation is on batches, let's filter if selected
            if ($supplierId !== 'all') {
                // Fetch supplier name or match
                $supplier = Supplier::find($supplierId);
                if ($supplier) {
                    $items = $items->filter(function($item) use ($supplier) {
                        return strtolower($item->tipe_faktur) === strtolower($supplier->nama) || str_contains(strtolower($item->no_faktur), strtolower($supplier->nama));
                    });
                }
            }
            
            $rows = $items->map(function($item) {
                return (object)[
                    'no_faktur' => $item->no_faktur,
                    'nama_obat' => $item->medicine->nama ?? 'Unknown',
                    'no_batch' => $item->no_batch,
                    'tanggal_masuk' => Carbon::parse($item->tanggal_masuk)->format('d M Y'),
                    'tanggal_kadaluwarsa' => Carbon::parse($item->tanggal_kadaluwarsa)->format('d M Y'),
                    'tipe_faktur' => $item->tipe_faktur,
                    'stok_sisa' => $item->stok_sisa
                ];
            });
            
            $previewData = (object)[
                'type' => 'incoming',
                'title' => 'Laporan Obat Masuk',
                'headers' => ['No', 'No. Faktur', 'Nama Obat', 'No. Batch', 'Tanggal Masuk', 'Tanggal Kadaluwarsa', 'Tipe', 'Sisa Stok'],
                'rows' => $rows->values()
            ];
            
        } elseif ($reportType === 'sales') {
            $dateFrom = $request->input('date_from');
            $dateTo = $request->input('date_to');
            $payMethod = $request->input('payment_method', 'all');
            
            $query = Sale::with('user');
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            if ($payMethod !== 'all') {
                $query->where('payment_method', $payMethod);
            }
            
            $items = $query->latest()->get()->map(function($s) {
                return (object)[
                    'invoice' => $s->invoice_number,
                    'kasir' => $s->user->name ?? 'Kasir',
                    'tanggal' => Carbon::parse($s->created_at)->format('d M Y, H:i'),
                    'metode' => strtoupper($s->payment_method),
                    'total' => $s->total_price
                ];
            });
            
            $previewData = (object)[
                'type' => 'sales',
                'title' => 'Laporan Rekap Penjualan',
                'headers' => ['No', 'No. Invoice', 'Kasir Penerima', 'Tanggal Transaksi', 'Metode Bayar', 'Total Transaksi'],
                'rows' => $items->values()
            ];
            
        } elseif ($reportType === 'expiry') {
            $risk = $request->input('risk', '3');
            
            $months = intval($risk);
            $items = MedicineBatch::with('medicine')
                ->where('stok_sisa', '>', 0)
                ->where('tanggal_kadaluwarsa', '<=', $now->copy()->addMonths($months))
                ->orderBy('tanggal_kadaluwarsa', 'asc')
                ->get()
                ->map(function($b) use ($now) {
                    $days = $now->diffInDays(Carbon::parse($b->tanggal_kadaluwarsa), false);
                    return (object)[
                        'nama' => $b->medicine->nama ?? 'Unknown',
                        'no_batch' => $b->no_batch,
                        'tanggal_kadaluwarsa' => Carbon::parse($b->tanggal_kadaluwarsa)->format('d M Y'),
                        'sisa_hari' => $days <= 0 ? 'KADALUWARSA' : $days . ' Hari',
                        'status' => $days <= 0 ? 'Expired' : ($days <= 90 ? 'Kritis (< 3 Bln)' : 'Waspada (< 6 Bln)')
                    ];
                });
                
            $previewData = (object)[
                'type' => 'expiry',
                'title' => 'Laporan Risiko Kadaluwarsa',
                'headers' => ['No', 'Nama Obat', 'No. Batch', 'Tanggal Kadaluwarsa', 'Sisa Hari', 'Status Risiko'],
                'rows' => $items->values()
            ];
        }

        return view('admin.reports.index', compact('totalValuation', 'categories', 'suppliers', 'kritisCount', 'previewData', 'expiredBatches'));
    }

    public function exportStock(Request $request)
    {
        $cat = $request->input('category', 'all');
        $status = $request->input('status', 'all');
        $format = $request->input('format', 'csv');

        $query = Medicine::with('batches');
        if ($cat !== 'all') {
            $query->where('kategori', $cat);
        }
        
        $items = $query->get()->map(function($m) {
            $stock = $m->batches->sum('stok_sisa');
            return (object)[
                'kode' => $m->kode,
                'nama' => $m->nama,
                'kategori' => $m->kategori,
                'satuan' => $m->satuan,
                'stok' => $stock,
                'min_stok' => $m->min_stok,
                'harga' => $m->harga,
                'total_nilai' => $stock * $m->harga,
                'is_kritis' => $stock <= $m->min_stok
            ];
        });

        if ($status === 'kritis') {
            $items = $items->filter(function($i) { return $i->is_kritis; });
        } elseif ($status === 'aman') {
            $items = $items->filter(function($i) { return !$i->is_kritis; });
        }

        $items = $items->values();

        if ($format === 'csv') {
            return $this->downloadCsv(
                'Laporan_Stok_Inventaris_' . date('Ymd'),
                ['No', 'Kode Obat', 'Nama Obat', 'Kategori Kategori', 'Satuan Kemasan', 'Stok Saat Ini', 'Stok Pengaman Minimum', 'Harga Jual Satuan (Rp)', 'Total Estimasi Aset (Rp)'],
                $items->map(function($i, $idx) {
                    return [
                        $idx + 1,
                        $i->kode,
                        $i->nama,
                        $i->kategori,
                        $i->satuan,
                        $i->stok,
                        $i->min_stok,
                        $i->harga,
                        $i->total_nilai
                    ];
                })->toArray()
            );
        }

        // Return print ready HTML for direct print/PDF save
        return view('admin.reports.print-stock', compact('items', 'cat', 'status'));
    }

    public function exportIncoming(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $supplierId = $request->input('supplier_id', 'all');
        $format = $request->input('format', 'csv');

        $query = MedicineBatch::with('medicine')->where('stok_sisa', '>', 0);
        if ($dateFrom) {
            $query->whereDate('tanggal_masuk', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('tanggal_masuk', '<=', $dateTo);
        }
        
        $items = $query->get();
        if ($supplierId !== 'all') {
            $supplier = Supplier::find($supplierId);
            if ($supplier) {
                $items = $items->filter(function($item) use ($supplier) {
                    return strtolower($item->tipe_faktur) === strtolower($supplier->nama) || str_contains(strtolower($item->no_faktur), strtolower($supplier->nama));
                });
            }
        }

        $items = $items->values();

        if ($format === 'csv') {
            return $this->downloadCsv(
                'Laporan_Obat_Masuk_' . date('Ymd'),
                ['No', 'Nomor Invoice / Faktur', 'Nama Produk Obat', 'Nomor Kode Batch', 'Tanggal Penerimaan', 'Tanggal Kadaluwarsa', 'Tipe Faktur Pengiriman', 'Jumlah Sisa Stok (Unit)'],
                $items->map(function($i, $idx) {
                    return [
                        $idx + 1,
                        $i->no_faktur,
                        $i->medicine->nama ?? 'Unknown',
                        $i->no_batch,
                        Carbon::parse($i->tanggal_masuk)->format('d-m-Y'),
                        Carbon::parse($i->tanggal_kadaluwarsa)->format('d-m-Y'),
                        $i->tipe_faktur,
                        $i->stok_sisa
                    ];
                })->toArray()
            );
        }

        return view('admin.reports.print-incoming', compact('items', 'dateFrom', 'dateTo'));
    }

    public function exportSales(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $payMethod = $request->input('payment_method', 'all');
        $format = $request->input('format', 'csv');

        $query = Sale::with('user');
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        if ($payMethod !== 'all') {
            $query->where('payment_method', $payMethod);
        }
        
        $items = $query->latest()->get();

        if ($format === 'csv') {
            return $this->downloadCsv(
                'Laporan_Penjualan_' . date('Ymd'),
                ['No', 'Nomor Invoice Penjualan', 'Nama Staf Kasir', 'Tanggal Transaksi', 'Metode Pembayaran', 'Total Nilai Penjualan (Rp)'],
                $items->map(function($i, $idx) {
                    return [
                        $idx + 1,
                        $i->invoice_number,
                        $i->user->name ?? 'Kasir',
                        Carbon::parse($i->created_at)->format('d-m-Y H:i'),
                        strtoupper($i->payment_method),
                        $i->total_price
                    ];
                })->toArray()
            );
        }

        return view('admin.reports.print-sales', compact('items', 'dateFrom', 'dateTo', 'payMethod'));
    }

    public function exportExpiry(Request $request)
    {
        $risk = $request->input('risk', '3');
        $format = $request->input('format', 'pdf'); // Expiry only supports PDF in screenshot but let's support CSV too!

        $now = Carbon::now();
        $months = intval($risk);
        $items = MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now->copy()->addMonths($months))
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->get();

        if ($format === 'csv') {
            return $this->downloadCsv(
                'Laporan_Risiko_Kadaluwarsa_' . date('Ymd'),
                ['No', 'Nama Produk Obat', 'Nomor Kode Batch', 'Tanggal Kadaluwarsa', 'Sisa Masa Aktif (Hari)', 'Status Tingkat Risiko'],
                $items->map(function($i, $idx) use ($now) {
                    $days = $now->diffInDays(Carbon::parse($i->tanggal_kadaluwarsa), false);
                    return [
                        $idx + 1,
                        $i->medicine->nama ?? 'Unknown',
                        $i->no_batch,
                        Carbon::parse($i->tanggal_kadaluwarsa)->format('d-m-Y'),
                        $days <= 0 ? 'KADALUWARSA' : $days . ' Hari',
                        $days <= 0 ? 'Expired' : ($days <= 90 ? 'Kritis (< 3 Bln)' : 'Waspada (< 6 Bln)')
                    ];
                })->toArray()
            );
        }

        return view('admin.reports.print-expiry', compact('items', 'risk'));
    }

    private function downloadCsv($filename, $headers, $rows)
    {
        $callback = function() use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to open correctly in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }

    public function processExpiryAction(Request $request)
    {
        $actionType = $request->input('action_type', 'retur');
        
        $now = Carbon::now();
        $batches = MedicineBatch::where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now->copy()->addMonths(3))
            ->get();
            
        $processedCount = 0;
        foreach ($batches as $batch) {
            $oldStock = $batch->stok_sisa;
            if ($oldStock > 0) {
                // Update batch stock to 0
                $batch->stok_sisa = 0;
                $batch->save();
                
                // Create StockAdjustment log for full traceability
                StockAdjustment::create([
                    'medicine_batch_id' => $batch->id,
                    'user_id' => auth()->id(),
                    'old_stock' => $oldStock,
                    'new_stock' => 0,
                    'difference' => -$oldStock,
                    'reason' => $actionType === 'retur' ? 'Retur ke Supplier (Kadaluwarsa)' : 'Pemusnahan Obat Kadaluwarsa'
                ]);
                $processedCount++;
            }
        }
        
        $msg = $actionType === 'retur' 
            ? "Berhasil memproses retur ke supplier untuk {$processedCount} batch obat kadaluwarsa/kritis. Stok obat terkait telah dinolkan." 
            : "Berhasil mengonfirmasi pemusnahan {$processedCount} batch obat kadaluwarsa/kritis. Berita acara telah dicatat dan stok telah dinolkan.";
            
        return redirect()->route('admin.reports.index')->with('success', $msg);
    }

    public function returPemusnahanIndex(Request $request)
    {
        $adjustments = StockAdjustment::with(['batch.medicine', 'user'])
            ->where(function ($query) {
                $query->where('reason', 'like', '%Retur%')
                      ->orWhere('reason', 'like', '%Pemusnahan%');
            })
            ->latest()
            ->get();

        return view('admin.reports.retur-pemusnahan', compact('adjustments'));
    }

    public function printReturPemusnahanDocument($id)
    {
        $adj = StockAdjustment::with(['batch.medicine', 'user'])->findOrFail($id);
        
        if (str_contains($adj->reason, 'Retur')) {
            // Nota Retur Resmi
            return view('admin.reports.print-retur-doc', compact('adj'));
        } else {
            // Berita Acara Pemusnahan Resmi
            return view('admin.reports.print-pemusnahan-doc', compact('adj'));
        }
    }
}
