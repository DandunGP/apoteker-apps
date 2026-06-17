<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        // Admin Gudang sees batches to adjust
        $batches = \App\Models\MedicineBatch::withoutGlobalScopes()->with('medicine')->latest()->get();
        return view('admin.adjustments.index', compact('batches'));
    }

    public function create($id)
    {
        $batch = \App\Models\MedicineBatch::withoutGlobalScopes()->with('medicine')->findOrFail($id);
        return view('admin.adjustments.create', compact('batch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_batch_id' => 'required|exists:medicine_batches,id',
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $batch = \App\Models\MedicineBatch::withoutGlobalScopes()->findOrFail($request->medicine_batch_id);
        $oldStock = $batch->stok_sisa;
        $newStock = $request->new_stock;
        $difference = $newStock - $oldStock;

        // Create log
        \App\Models\StockAdjustment::create([
            'medicine_batch_id' => $batch->id,
            'user_id' => auth()->id(),
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'difference' => $difference,
            'reason' => $request->reason,
        ]);

        // Update batch stock
        $batch->update(['stok_sisa' => $newStock]);

        return redirect()->route('adjustments.index')->with('success', 'Stok berhasil disesuaikan.');
    }

    public function history()
    {
        $adjustments = \App\Models\StockAdjustment::with(['batch' => function($q) {
            $q->withoutGlobalScopes();
        }, 'batch.medicine', 'user'])->latest()->get();
        return view('admin.adjustments.history', compact('adjustments'));
    }
}
