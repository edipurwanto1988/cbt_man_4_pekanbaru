<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::latest()->paginate(10);
        return view('admin.tahun_ajaran.index', compact('tahunAjaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9',
            'semester' => 'required|in:1,2',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string|max:255',
        ]);

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'status' => $request->boolean('status'),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.tahun_ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun_ajaran.show', compact('tahunAjaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('admin.tahun_ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:9',
            'semester' => 'required|in:1,2',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tahunAjaran->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'status' => $request->boolean('status'),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.tahun_ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()->route('admin.tahun_ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus');
    }
}