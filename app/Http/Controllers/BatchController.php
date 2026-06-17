<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = \App\Models\MedicineBatch::withoutGlobalScopes()->with('medicine')
            ->latest()
            ->get()
            ->groupBy('no_faktur')
            ->map(function($items, $faktur) {
                $first = $items->first();
                $total_stok = $items->sum('stok_sisa');
                
                // Get unique medicines list
                $medicines = $items->map(function($i) { 
                    return $i->medicine->nama ?? 'Unknown'; 
                })->unique()->values();

                // Worst status logic
                $worst_diff = 9999;
                foreach ($items as $item) {
                    $exp = \Carbon\Carbon::parse($item->tanggal_kadaluwarsa);
                    $diff = now()->diffInDays($exp, false);
                    if ($diff < $worst_diff) {
                        $worst_diff = $diff;
                    }
                }

                $status = 'Safe';
                $color = '#10B981';
                $bg = '#ECFDF5';

                if ($worst_diff < 0) {
                    $status = 'Expired';
                    $color = '#EF4444';
                    $bg = '#FEE2E2';
                } elseif ($worst_diff < 90) {
                    $status = 'Near Expiry';
                    $color = '#D97706';
                    $bg = '#FEF3C7';
                }

                return (object)[
                    'id' => $first->id,
                    'no_faktur' => $faktur,
                    'no_batch' => $first->no_batch,
                    'tipe_faktur' => $first->tipe_faktur,
                    'tanggal_masuk' => \Carbon\Carbon::parse($first->tanggal_masuk)->format('d M Y'),
                    'tanggal_jatuh_tempo' => $first->tanggal_jatuh_tempo,
                    'medicines' => $medicines,
                    'total_stok' => $total_stok,
                    'expiry_status' => $status,
                    'status_color' => $color,
                    'status_bg' => $bg,
                    'items_count' => $items->count()
                ];
            })->values();

        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $medicines = \App\Models\Medicine::all();
        $suppliers = \App\Models\Supplier::where('status', 'Aktif')->get();
        return view('admin.batches.create', compact('medicines', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'tipe_faktur' => 'required|in:Lunas,Tempo,Titipan',
            'no_faktur' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.no_batch' => 'required|string',
            'items.*.stok_sisa' => 'required|integer|min:1',
            'items.*.tanggal_kadaluwarsa' => 'required|date|after:tanggal_masuk',
        ]);

        $no_faktur = $request->no_faktur;
        if (empty($no_faktur)) {
            $datePrefix = date('Ymd');
            $randomString = strtoupper(substr(md5(uniqid()), 0, 4));
            $no_faktur = "INV-{$datePrefix}-{$randomString}";
        }

        $tanggal_jatuh_tempo = null;
        if ($request->tipe_faktur === 'Tempo') {
            $tanggal_jatuh_tempo = \Carbon\Carbon::parse($request->tanggal_masuk)->addDays(30)->toDateString();
        }

        foreach ($request->items as $item) {
            \App\Models\MedicineBatch::create([
                'medicine_id'         => $item['medicine_id'],
                'supplier_id'         => $request->supplier_id ?? null,
                'no_batch'            => $item['no_batch'],
                'tanggal_masuk'       => $request->tanggal_masuk,
                'tanggal_kadaluwarsa' => $item['tanggal_kadaluwarsa'],
                'stok_sisa'           => $item['stok_sisa'],
                'stok_awal'           => $item['stok_sisa'], // simpan qty awal dari gudang
                'no_faktur'           => $no_faktur,
                'tipe_faktur'         => $request->tipe_faktur,
                'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
                'gudang_status'       => 'diterima',
                'is_validated'        => false,
            ]);
        }

        return redirect()->route('batches.index')->with('success', count($request->items) . ' medicines successfully added to supply.');
    }
    
    public function print($batchId)
    {
        $batch = \App\Models\MedicineBatch::withoutGlobalScopes()->findOrFail($batchId);
        // Find all items belonging to the same invoice
        $all_batches = \App\Models\MedicineBatch::withoutGlobalScopes()->with('medicine')
            ->where('no_faktur', $batch->no_faktur)
            ->get();

        return view('admin.batches.print', [
            'batch' => $batch, // Still pass the primary batch for invoice header info
            'all_batches' => $all_batches
        ]);
    }
}
