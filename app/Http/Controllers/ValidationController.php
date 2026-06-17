<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineBatch;
use Carbon\Carbon;

class ValidationController extends Controller
{
    /**
     * Halaman utama Validasi Obat Masuk
     */
    public function index()
    {
        // Group batches by no_faktur — only unvalidated ones
        $rawBatches = MedicineBatch::withoutGlobalScopes()->with(['medicine', 'supplier'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        // Group by no_faktur
        $grouped = $rawBatches->groupBy('no_faktur');

        $fakturList = $grouped->map(function ($items, $faktur) {
            $first        = $items->first();
            $allValidated = $items->every(fn($i) => $i->is_validated);
            $anyValidated = $items->some(fn($i)  => $i->is_validated);

            // Determine warehouse status (worst of all items)
            $gudangStatus = 'diterima';
            foreach ($items as $item) {
                if ($item->gudang_status === 'pending') { $gudangStatus = 'pending'; break; }
            }

            // Is high urgency? — any item expiring within 90 days
            $highUrgency = $items->some(function ($item) {
                $diff = now()->diffInDays(Carbon::parse($item->tanggal_kadaluwarsa), false);
                return $diff >= 0 && $diff <= 90;
            });

            return (object)[
                'no_faktur'     => $faktur,
                'supplier'      => $first->supplier ?? null,
                'tanggal_masuk' => Carbon::parse($first->tanggal_masuk)->format('d M Y'),
                'tipe_faktur'   => $first->tipe_faktur,
                'items_count'   => $items->count(),
                'gudang_status' => $gudangStatus,
                'is_validated'  => $allValidated,
                'any_validated' => $anyValidated,
                'high_urgency'  => $highUrgency,
                'items'         => $items,
                'first_id'      => $first->id,
            ];
        })->values();

        // KPI Stats
        $menunggu    = $fakturList->filter(fn($f) => !$f->is_validated)->count();
        $urgensi     = $fakturList->filter(fn($f) => !$f->is_validated && $f->high_urgency)->count();
        $validated   = $fakturList->filter(fn($f) => $f->is_validated)->count();

        return view('apoteker.validation.index', compact(
            'fakturList', 'menunggu', 'urgensi', 'validated'
        ));
    }

    /**
     * Panel validasi — tampilkan item dari 1 faktur (no_faktur via query param ?no=...)
     */
    public function panel(Request $request)
    {
        $no_faktur = $request->query('no');

        if (!$no_faktur) {
            return response()->json(['error' => 'no_faktur diperlukan'], 422);
        }

        $items = MedicineBatch::withoutGlobalScopes()->with(['medicine', 'supplier'])
            ->where('no_faktur', $no_faktur)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['error' => 'Faktur tidak ditemukan'], 404);
        }

        $first = $items->first();

        return response()->json([
            'no_faktur'     => $no_faktur,
            'supplier'      => $first->supplier->nama ?? '-',
            'tipe_faktur'   => $first->tipe_faktur,
            'tanggal_masuk' => Carbon::parse($first->tanggal_masuk)->format('d M Y'),
            'items'         => $items->map(fn($i) => [
                'id'                   => $i->id,
                'nama'                 => $i->medicine->nama ?? 'Unknown',
                'kategori'             => $i->medicine->kategori ?? '-',
                'qty_system'           => $i->stok_awal ?: $i->stok_sisa, // QTY asli dari input gudang
                'stok_sisa'            => $i->stok_sisa,
                'satuan'               => $i->medicine->satuan ?? 'Pcs',
                'no_batch'             => $i->no_batch,
                'tanggal_kadaluwarsa'  => Carbon::parse($i->tanggal_kadaluwarsa)->format('d M Y'),
                'physical_qty'         => $i->physical_qty ?? ($i->stok_awal ?: $i->stok_sisa),
                'physical_batch'       => $i->physical_batch ?? $i->no_batch,
                'physical_expiry'      => $i->physical_expiry ?? Carbon::parse($i->tanggal_kadaluwarsa)->format('m/y'),
                'kondisi'              => $i->kondisi ?? 'Baik',
                'kesesuaian'           => $i->kesesuaian ?? true,
                'is_validated'         => $i->is_validated,
                'gudang_status'        => $i->gudang_status,
            ])->values(),
        ]);
    }

    /**
     * Selesaikan Validasi — no_faktur dari JSON body
     */
    public function confirm(Request $request)
    {
        $no_faktur = $request->input('no_faktur');

        if (!$no_faktur) {
            return response()->json(['success' => false, 'message' => 'no_faktur diperlukan'], 422);
        }

        $request->validate([
            'items'                   => 'required|array',
            'items.*.id'              => 'required|exists:medicine_batches,id',
            'items.*.physical_qty'    => 'required',
            'items.*.physical_batch'  => 'required',
            'items.*.physical_expiry' => 'required',
            'items.*.kondisi'         => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'items.*.kesesuaian'      => 'required|in:0,1',
        ]);

        foreach ($request->items as $itemData) {
            $batch = MedicineBatch::withoutGlobalScopes()->find($itemData['id']);
            if ($batch && $batch->no_faktur === $no_faktur) {
                $batch->update([
                    'physical_qty'     => $itemData['physical_qty'],
                    'physical_batch'   => $itemData['physical_batch'],
                    'physical_expiry'  => $itemData['physical_expiry'],
                    'kondisi'          => $itemData['kondisi'],
                    'kesesuaian'       => (bool) $itemData['kesesuaian'],
                    'is_validated'     => true,
                    'validated_at'     => now(),
                    'validated_by'     => auth()->id(),
                    'validation_notes' => $request->notes ?? null,
                    'gudang_status'    => 'diterima',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Faktur {$no_faktur} berhasil divalidasi oleh " . auth()->user()->name . ".",
        ]);
    }

    /**
     * Tunda Validasi — no_faktur dari JSON body
     */
    public function defer(Request $request)
    {
        $no_faktur = $request->input('no_faktur');

        if (!$no_faktur) {
            return response()->json(['success' => false, 'message' => 'no_faktur diperlukan'], 422);
        }

        MedicineBatch::withoutGlobalScopes()->where('no_faktur', $no_faktur)->update([
            'gudang_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Validasi faktur {$no_faktur} ditunda. Status diset ke PENDING.",
        ]);
    }
}
