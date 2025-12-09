<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mataPelajaran = MataPelajaran::latest()->paginate(10);
        return view('admin.mata_pelajaran.index', compact('mataPelajaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rombelId = $request->query('rombel_id');
        $rombel = null;

        if ($rombelId) {
            $rombel = \App\Models\Rombel::find($rombelId);
        }

        return view('admin.mata_pelajaran.create', compact('rombel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->name
        ]);

        return redirect()->route('admin.mata_pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata_pelajaran.show', compact('mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata_pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        $mataPelajaran->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('admin.mata_pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();

        return redirect()->route('admin.mata_pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus');
    }
}