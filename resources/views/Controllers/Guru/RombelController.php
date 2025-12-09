<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\TingkatKelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get active tahun ajaran as default
        $activeTahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
        $selectedTahunAjaranId = $request->input('tahun_ajaran_id', $activeTahunAjaran ? $activeTahunAjaran->id : null);
        
        // Build query with join to filter by tahun ajaran status
        $query = Rombel::join('tahun_ajaran', 'tahun_ajaran.id', '=', 'rombel.tahun_ajaran_id')
            ->with(['tingkatKelas', 'tahunAjaran', 'waliKelas'])
            ->select('rombel.*');
            
        // Filter by tahun ajaran if selected
        if ($selectedTahunAjaranId) {
            $query->where('rombel.tahun_ajaran_id', $selectedTahunAjaranId);
        }
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('rombel.nama_rombel', 'like', '%' . $search . '%');
        }
        
        $rombels = $query->latest('rombel.created_at')->paginate(10);
        $tahunAjarans = TahunAjaran::all();
        
        return view('guru.rombel.index', compact('rombels', 'tahunAjarans', 'selectedTahunAjaranId'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas'])->findOrFail($id);
        return view('guru.rombel.show', compact('rombel'));
    }

    /**
     * Display siswa for the specified rombel.
     */
    public function siswa(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas'])->findOrFail($id);
        $siswa = \App\Models\Siswa::leftJoin('rombel_detail', 'siswa.nisn', '=', 'rombel_detail.nisn')
            ->where('rombel_detail.rombel_id', $id)
            ->select('siswa.*', 'rombel_detail.rombel_id')
            ->paginate(10);
        
        return view('guru.rombel.siswa', compact('rombel', 'siswa'));
    }

    /**
     * Show mata pelajaran for the specified rombel.
     */
    public function mapel(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas', 'rombelMapels.mataPelajaran'])->findOrFail($id);
        return view('guru.rombel.mapel', compact('rombel'));
    }
}