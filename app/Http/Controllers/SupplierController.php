<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kontak_person', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sort option
        $sort = $request->get('sort', 'nama_asc');
        if ($sort === 'nama_desc') {
            $query->orderBy('nama', 'desc');
        } else {
            $query->orderBy('nama', 'asc');
        }

        $suppliers = $query->get();

        $metrics = [
            'total_supplier' => Supplier::count(),
            'total_aktif' => Supplier::where('status', 'Aktif')->count(),
            'total_non_aktif' => Supplier::where('status', 'Non-Aktif')->count(),
        ];

        return view('admin.suppliers.index', compact('suppliers', 'metrics', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kontak_person' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'alamat' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kontak_person' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'alamat' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
