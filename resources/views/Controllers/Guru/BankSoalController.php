<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Rombel;
use App\Models\BankSoalRombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Build query
        $query = BankSoal::with(['tahunAjaran', 'mataPelajaran', 'creator', 'pertanyaanSoals'])
            ->withCount('pertanyaanSoals')
            ->where('created_by', Auth::guard('guru')->user()->id_guru);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('kode_bank', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_bank', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply tahun ajaran filter
        if ($request->has('tahun_ajaran_id') && !empty($request->tahun_ajaran_id)) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }
        
        // Apply type test filter
        if ($request->has('type_test') && !empty($request->type_test)) {
            $query->where('type_test', $request->type_test);
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $bankSoals = $query->latest()->paginate(10);
        $tahunAjarans = TahunAjaran::all();
            
        return view('guru.bank_soal.index', compact('bankSoals', 'tahunAjarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataPelajarans = MataPelajaran::all();
        $tahunAjarans = TahunAjaran::all();
        $gurus = Guru::all();
        
        // Get active rombels with active tahun ajaran
        $rombels = Rombel::join('tahun_ajaran', 'tahun_ajaran.id', '=', 'rombel.tahun_ajaran_id')
            ->where('tahun_ajaran.status', 'Aktif')
            ->select('rombel.id', 'rombel.nama_rombel')
            ->get();
        
        return view('guru.bank_soal.create', compact('mataPelajarans', 'tahunAjarans', 'gurus', 'rombels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_bank' => 'required|string|max:50|unique:bank_soals',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'type_test' => 'required|in:pretest,posttest',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'nama_bank' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'durasi_menit' => 'nullable|integer|min:1',
            'max_time' => 'nullable|integer|min:1',
            'bobot_benar_default' => 'nullable|numeric|min:0',
            'bobot_salah_default' => 'nullable|numeric',
            'status' => 'required|in:draft,aktif,selesai',
            'rombel_ids' => 'nullable|array',
            'rombel_ids.*' => 'exists:rombel,id',
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['created_by'] = Auth::guard('guru')->user()->id_guru;
            
            // Auto-set pengawas_id to current logged-in guru if not provided
            if (!isset($data['pengawas_id']) || empty($data['pengawas_id'])) {
                $data['pengawas_id'] = Auth::guard('guru')->user()->id_guru;
            }
            
            $bankSoal = BankSoal::create($data);
            
            // Create rombel relationships if any are selected
            if ($request->has('rombel_ids') && is_array($request->rombel_ids)) {
                foreach ($request->rombel_ids as $rombelId) {
                    BankSoalRombel::create([
                        'bank_soal_id' => $bankSoal->id,
                        'rombel_id' => $rombelId,
                    ]);
                }
            }
        });
        
        return redirect()->route('guru.bank_soal.index')
            ->with('success', 'Bank Soal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankSoal $bankSoal)
    {
        // Allow any authenticated guru to view bank soals
        $bankSoal->load(['tahunAjaran', 'mataPelajaran', 'creator', 'pengawas']);
        
        return view('guru.bank_soal.show', compact('bankSoal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankSoal $bankSoal)
    {
        // Allow any authenticated guru to edit bank soals
        $mataPelajarans = MataPelajaran::all();
        $tahunAjarans = TahunAjaran::all();
        $gurus = Guru::all();
        
        // Get active rombels with active tahun ajaran
        $rombels = Rombel::join('tahun_ajaran', 'tahun_ajaran.id', '=', 'rombel.tahun_ajaran_id')
            ->where('tahun_ajaran.status', 'Aktif')
            ->select('rombel.id', 'rombel.nama_rombel')
            ->get();
        
        // Get selected rombels for this bank soal
        $selectedRombels = BankSoalRombel::where('bank_soal_id', $bankSoal->id)
            ->pluck('rombel_id')
            ->toArray();
        
        return view('guru.bank_soal.edit', compact('bankSoal', 'mataPelajarans', 'tahunAjarans', 'gurus', 'rombels', 'selectedRombels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankSoal $bankSoal)
    {
        // Allow any authenticated guru to update bank soals
        $request->validate([
            'kode_bank' => 'required|string|max:50|unique:bank_soals,kode_bank,' . $bankSoal->id,
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'type_test' => 'required|in:pretest,posttest',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'nama_bank' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'durasi_menit' => 'nullable|integer|min:1',
            'max_time' => 'nullable|integer|min:1',
            'bobot_benar_default' => 'nullable|numeric|min:0',
            'bobot_salah_default' => 'nullable|numeric',
            'status' => 'required|in:draft,aktif,selesai',
            'rombel_ids' => 'nullable|array',
            'rombel_ids.*' => 'exists:rombel,id',
        ]);

        DB::transaction(function () use ($request, $bankSoal) {
            $data = $request->all();
            
            // Auto-set pengawas_id to current logged-in guru if not provided
            if (!isset($data['pengawas_id']) || empty($data['pengawas_id'])) {
                $data['pengawas_id'] = Auth::guard('guru')->user()->id_guru;
            }
            
            // Update bank soal
            $bankSoal->update($data);
            
            // Always delete existing rombel relationships first
            BankSoalRombel::where('bank_soal_id', $bankSoal->id)->delete();
            
            // Create new rombel relationships if any are selected
            if ($request->has('rombel_ids') && is_array($request->rombel_ids)) {
                foreach ($request->rombel_ids as $rombelId) {
                    BankSoalRombel::create([
                        'bank_soal_id' => $bankSoal->id,
                        'rombel_id' => $rombelId,
                    ]);
                }
            }
        });
        
        return redirect()->route('guru.bank_soal.index')
            ->with('success', 'Bank Soal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankSoal $bankSoal)
    {
        // Allow any authenticated guru to delete bank soals
        $bankSoal->delete();
        
        return redirect()->route('guru.bank_soal.index')
            ->with('success', 'Bank Soal berhasil dihapus.');
    }
}