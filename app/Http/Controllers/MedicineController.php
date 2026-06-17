<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Total SKU
        $totalSku = \App\Models\Medicine::count();

        // 2. Stok Rendah
        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();
        $stokRendahCount = 0;
        foreach ($medicinesWithStock as $m) {
            $totalStock = $m->batches->sum('stok_sisa');
            if ($totalStock <= $m->min_stok) {
                $stokRendahCount++;
            }
        }

        // 3. Akan Kadaluwarsa
        $nearExpiryCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', \Carbon\Carbon::now()->addDays(90))
            ->count();

        // 4. Kategori Count
        $kategoriCount = \App\Models\Medicine::distinct('kategori')->count('kategori');

        // 5. Query and map medicines
        $medicines = \App\Models\Medicine::with('batches')->get()->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            $med->total_stock = $totalStock;
            
            
            $med->harga_formatted = 'Rp ' . number_format($med->harga, 0, ',', '.');
            return $med;
        });

        $metrics = [
            'total_sku' => $totalSku,
            'stok_rendah' => $stokRendahCount,
            'akan_kadaluwarsa' => $nearExpiryCount,
            'total_kategori' => $kategoriCount,
        ];

        return view('admin.medicines.index', compact('medicines', 'metrics'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:medicines,kode',
            'nama' => 'required',
            'kategori' => 'required',
            'satuan' => 'required|in:Strip,Botol,Box',
            'harga' => 'required|numeric',
            'min_stok' => 'required|integer',
        ]);

        \App\Models\Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine added successfully.');
    }

    public function edit(\App\Models\Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, \App\Models\Medicine $medicine)
    {
        $request->validate([
            'kode' => 'required|unique:medicines,kode,' . $medicine->id,
            'nama' => 'required',
            'kategori' => 'required',
            'satuan' => 'required|in:Strip,Botol,Box',
            'harga' => 'required|numeric',
            'min_stok' => 'required|integer',
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(\App\Models\Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }

    public function export()
    {
        $medicines = \App\Models\Medicine::with('batches')->get()->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            $med->total_stock = $totalStock;
            
            $med->status_label = $totalStock <= $med->min_stok ? 'Stok Rendah' : 'Stok Aman';
            $med->status_color = $totalStock <= $med->min_stok ? '#DC2626' : '#15803D';
            $med->status_bg = $totalStock <= $med->min_stok ? '#FEF2F2' : '#DCFCE7';
            
            return $med;
        });

        return view('admin.medicines.export-print', compact('medicines'));
    }

    public function monitoringStock(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'terendah'); 
        $searchQuery = $request->input('search');

        // 1. Calculate All metrics (always base on all database records to be dynamic)
        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();
        
        $stokKritisCount = 0;
        foreach ($medicinesWithStock as $m) {
            $totalStock = $m->batches->sum('stok_sisa');
            if ($totalStock <= $m->min_stok) {
                $stokKritisCount++;
            }
        }

        $hampirExpiredCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [\Carbon\Carbon::now(), \Carbon\Carbon::now()->addDays(90)])
            ->count();

        $totalItemAktif = \App\Models\Medicine::count();

        // 2. Query and Map items
        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            
            // Closest expiring active batch
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateRaw = null;
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateRaw = $closestBatch->tanggal_kadaluwarsa;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->translatedFormat('d M Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'id' => $med->id,
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok,
                'closest_batch_no' => $closestBatch ? $closestBatch->no_batch : null
            ];
        });

        // 3. Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        // 4. Apply Sorting
        if ($sortOption === 'terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'tertinggi') {
            $items = $items->sortByDesc('total_stock');
        } elseif ($sortOption === 'kadaluwarsa') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        }

        // 5. Paginate items
        $currentPage  = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage      = 5;
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginatedItems->appends(request()->all());

        // Get all distinct categories for category dropdown
        $categories = \App\Models\Medicine::distinct('kategori')->pluck('kategori');

        // Find the closest expiring batch globally for the FIFO Alert Banner
        $globalClosestExpiredBatch = \App\Models\MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->first();

        $metrics = [
            'stok_kritis' => $stokKritisCount,
            'hampir_expired' => $hampirExpiredCount,
            'total_aktif' => $totalItemAktif
        ];

        return view('admin.medicines.monitoring', compact(
            'paginatedItems', 
            'metrics', 
            'statusFilter', 
            'categoryFilter', 
            'sortOption', 
            'searchQuery', 
            'categories',
            'globalClosestExpiredBatch'
        ));
    }

    public function monitoringStockExport(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'terendah'); 
        $searchQuery = $request->input('search');

        // 1. Calculate All metrics (always base on all database records to be dynamic)
        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();
        
        $stokKritisCount = 0;
        foreach ($medicinesWithStock as $m) {
            $totalStock = $m->batches->sum('stok_sisa');
            if ($totalStock <= $m->min_stok) {
                $stokKritisCount++;
            }
        }

        $hampirExpiredCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [\Carbon\Carbon::now(), \Carbon\Carbon::now()->addDays(90)])
            ->count();

        $totalItemAktif = \App\Models\Medicine::count();

        // 2. Query and Map items
        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            
            // Closest expiring active batch
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateRaw = null;
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateRaw = $closestBatch->tanggal_kadaluwarsa;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->translatedFormat('d M Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok
            ];
        });

        // 3. Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        // 4. Apply Sorting
        if ($sortOption === 'terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'tertinggi') {
            $items = $items->sortByDesc('total_stock');
        } elseif ($sortOption === 'kadaluwarsa') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        }

        $items = $items->values();

        $metrics = [
            'stok_kritis' => $stokKritisCount,
            'hampir_expired' => $hampirExpiredCount,
            'total_aktif' => $totalItemAktif
        ];

        return view('admin.medicines.monitoring-export', compact(
            'items', 
            'metrics', 
            'statusFilter', 
            'categoryFilter', 
            'sortOption',
            'searchQuery'
        ));
    }

    public function monitoringStockLabels(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'terendah'); 
        $searchQuery = $request->input('search');

        // 1. Get filtered items
        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();

        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->format('d/m/Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok
            ];
        });

        // Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        if ($sortOption === 'terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'tertinggi') {
            $items = $items->sortByDesc('total_stock');
        } elseif ($sortOption === 'kadaluwarsa') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        }

        $items = $items->values();

        return view('admin.medicines.monitoring-labels', compact('items'));
    }

    public function monitoringExpiry(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'kadaluwarsa_terdekat'); 
        $searchQuery = $request->input('search');

        // 1. Calculate All metrics (always base on all database records to be dynamic)
        $now = \Carbon\Carbon::now();
        
        $sudahExpiredCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now)
            ->count();

        $kurang3BulanCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [$now->copy()->addDay(), $now->copy()->addMonths(3)])
            ->count();

        $kurang6BulanCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [$now->copy()->addDay(), $now->copy()->addMonths(6)])
            ->count();

        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();

        // 2. Query and Map items
        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            
            // Closest expiring active batch
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateRaw = null;
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateRaw = $closestBatch->tanggal_kadaluwarsa;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->translatedFormat('d M Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'id' => $med->id,
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok,
                'closest_batch_no' => $closestBatch ? $closestBatch->no_batch : null
            ];
        });

        // 3. Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        // 4. Apply Sorting
        if ($sortOption === 'kadaluwarsa_terdekat') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        } elseif ($sortOption === 'kadaluwarsa_terjauh') {
            $items = $items->sortByDesc(function($item) {
                return $item->days_to_expiry ?? -999999;
            });
        } elseif ($sortOption === 'stok_terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'stok_tertinggi') {
            $items = $items->sortByDesc('total_stock');
        }

        // 5. Paginate items
        $currentPage  = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage      = 5;
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $paginatedItems->appends(request()->all());

        // Get all distinct categories for category dropdown
        $categories = \App\Models\Medicine::distinct('kategori')->pluck('kategori');

        // Find the closest expiring batch globally for the FIFO Alert Banner
        $globalClosestExpiredBatch = \App\Models\MedicineBatch::with('medicine')
            ->where('stok_sisa', '>', 0)
            ->orderBy('tanggal_kadaluwarsa', 'asc')
            ->first();

        $metrics = [
            'sudah_kadaluwarsa' => $sudahExpiredCount,
            'hampir_kadaluwarsa' => $kurang3BulanCount,
            'akan_kadaluwarsa_6' => $kurang6BulanCount
        ];

        return view('admin.medicines.expiry', compact(
            'paginatedItems', 
            'metrics', 
            'statusFilter', 
            'categoryFilter', 
            'sortOption', 
            'searchQuery', 
            'categories',
            'globalClosestExpiredBatch'
        ));
    }

    public function monitoringExpiryExport(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'kadaluwarsa_terdekat'); 
        $searchQuery = $request->input('search');

        // 1. Calculate All metrics (always base on all database records to be dynamic)
        $now = \Carbon\Carbon::now();
        
        $sudahExpiredCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->where('tanggal_kadaluwarsa', '<=', $now)
            ->count();

        $kurang3BulanCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [$now->copy()->addDay(), $now->copy()->addMonths(3)])
            ->count();

        $kurang6BulanCount = \App\Models\MedicineBatch::where('stok_sisa', '>', 0)
            ->whereBetween('tanggal_kadaluwarsa', [$now->copy()->addDay(), $now->copy()->addMonths(6)])
            ->count();

        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();

        // 2. Query and Map items
        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            
            // Closest expiring active batch
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateRaw = null;
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateRaw = $closestBatch->tanggal_kadaluwarsa;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->translatedFormat('d M Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok
            ];
        });

        // 3. Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        // 4. Apply Sorting
        if ($sortOption === 'kadaluwarsa_terdekat') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        } elseif ($sortOption === 'kadaluwarsa_terjauh') {
            $items = $items->sortByDesc(function($item) {
                return $item->days_to_expiry ?? -999999;
            });
        } elseif ($sortOption === 'stok_terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'stok_tertinggi') {
            $items = $items->sortByDesc('total_stock');
        }

        $items = $items->values();

        $metrics = [
            'sudah_kadaluwarsa' => $sudahExpiredCount,
            'hampir_kadaluwarsa' => $kurang3BulanCount,
            'akan_kadaluwarsa_6' => $kurang6BulanCount
        ];

        return view('admin.medicines.expiry-export', compact(
            'items', 
            'metrics', 
            'statusFilter', 
            'categoryFilter', 
            'sortOption',
            'searchQuery'
        ));
    }

    public function monitoringExpiryLabels(Request $request)
    {
        $statusFilter = $request->input('status', 'all'); 
        $categoryFilter = $request->input('category', 'all'); 
        $sortOption = $request->input('sort', 'kadaluwarsa_terdekat'); 
        $searchQuery = $request->input('search');

        // 1. Get filtered items
        $medicinesWithStock = \App\Models\Medicine::with('batches')->get();

        $items = $medicinesWithStock->map(function($med) {
            $totalStock = $med->batches->sum('stok_sisa');
            $closestBatch = $med->batches->where('stok_sisa', '>', 0)->sortBy('tanggal_kadaluwarsa')->first();
            
            $batchNo = '-';
            $expiryDateFormatted = '-';
            $daysToExpiry = null;
            
            if ($closestBatch) {
                $batchNo = $closestBatch->no_batch;
                $expiryDateFormatted = \Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa)->format('d/m/Y');
                $daysToExpiry = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($closestBatch->tanggal_kadaluwarsa), false);
            }
            
            return (object)[
                'kode' => $med->kode,
                'nama' => $med->nama,
                'kategori' => $med->kategori,
                'satuan' => $med->satuan ?? 'Box',
                'total_stock' => $totalStock,
                'min_stok' => $med->min_stok,
                'no_batch' => $batchNo,
                'tanggal_kadaluwarsa' => $expiryDateFormatted,
                'days_to_expiry' => $daysToExpiry,
                'is_kritis' => $totalStock <= $med->min_stok
            ];
        });

        // Apply Filters
        if ($searchQuery) {
            $q = strtolower($searchQuery);
            $items = $items->filter(function($item) use ($q) {
                return str_contains(strtolower($item->nama), $q) || str_contains(strtolower($item->kode), $q);
            });
        }

        if ($statusFilter === 'menipis') {
            $items = $items->filter(function($item) {
                return $item->is_kritis;
            });
        } elseif ($statusFilter === 'kadaluwarsa') {
            $items = $items->filter(function($item) {
                return $item->days_to_expiry !== null && $item->days_to_expiry <= 90;
            });
        }

        if ($categoryFilter && $categoryFilter !== 'all') {
            $items = $items->filter(function($item) use ($categoryFilter) {
                return strtolower($item->kategori) === strtolower($categoryFilter);
            });
        }

        if ($sortOption === 'kadaluwarsa_terdekat') {
            $items = $items->sortBy(function($item) {
                return $item->days_to_expiry ?? 999999;
            });
        } elseif ($sortOption === 'kadaluwarsa_terjauh') {
            $items = $items->sortByDesc(function($item) {
                return $item->days_to_expiry ?? -999999;
            });
        } elseif ($sortOption === 'stok_terendah') {
            $items = $items->sortBy('total_stock');
        } elseif ($sortOption === 'stok_tertinggi') {
            $items = $items->sortByDesc('total_stock');
        }

        $items = $items->values();

        return view('admin.medicines.monitoring-labels', compact('items'));
    }
}
