<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rombel;
use App\Models\TingkatKelas;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\RombelDetail;
use App\Models\RombelMapel;
use App\Exports\RombelSiswaTemplateExport;
use App\Imports\RombelSiswaImport;
use App\Exports\RombelTemplateExport;
use App\Imports\RombelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas', 'rombelDetails', 'rombelMapels'])->latest()->paginate(10);
        return view('admin.rombel.index', compact('rombel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tingkatKelas = TingkatKelas::all();
        $tahunAjaran = TahunAjaran::all();
        $guru = Guru::all();
        return view('admin.rombel.create', compact('tingkatKelas', 'tahunAjaran', 'guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat_id' => 'required|exists:tingkat_kelas,id',
            'kode_kelas' => 'required|in:A,B,C,D',
            'nama_rombel' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique combination of tahun_ajaran_id, tingkat_id, and kode_kelas
        $exists = Rombel::where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('tingkat_id', $request->tingkat_id)
            ->where('kode_kelas', $request->kode_kelas)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Rombel dengan kombinasi tahun ajaran, tingkat kelas, dan kode kelas ini sudah ada.')
                ->withInput();
        }

        Rombel::create($request->all());

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Data rombel berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas'])->findOrFail($id);
        return view('admin.rombel.show', compact('rombel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rombel = Rombel::findOrFail($id);
        $tingkatKelas = TingkatKelas::all();
        $tahunAjaran = TahunAjaran::all();
        $guru = Guru::all();
        return view('admin.rombel.edit', compact('rombel', 'tingkatKelas', 'tahunAjaran', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat_id' => 'required|exists:tingkat_kelas,id',
            'kode_kelas' => 'required|in:A,B,C,D',
            'nama_rombel' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for unique combination (excluding current record)
        $exists = Rombel::where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('tingkat_id', $request->tingkat_id)
            ->where('kode_kelas', $request->kode_kelas)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Rombel dengan kombinasi tahun ajaran, tingkat kelas, dan kode kelas ini sudah ada.')
                ->withInput();
        }

        $rombel = Rombel::findOrFail($id);
        $rombel->update($request->all());

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Data rombel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rombel = Rombel::findOrFail($id);
        $rombel->delete();

        return redirect()->route('admin.rombel.index')
            ->with('success', 'Data rombel berhasil dihapus.');
    }

    /**
     * Display siswa for the specified rombel.
     */
    public function siswa(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas'])->findOrFail($id);
        $siswa = Siswa::leftJoin('rombel_detail', 'siswa.nisn', '=', 'rombel_detail.nisn')
            ->where('rombel_detail.rombel_id', $id)
            ->select('siswa.*', 'rombel_detail.rombel_id')
            ->paginate(10);
        
        return view('admin.rombel.siswa', compact('rombel', 'siswa'));
    }

    /**
     * Show the import form for siswa in rombel.
     */
    public function siswaImport(string $id)
    {
        $rombel = Rombel::findOrFail($id);
        return view('admin.rombel.siswa_import', compact('rombel'));
    }

    /**
     * Preview the Excel file before importing siswa to rombel.
     */
    public function siswaPreview(Request $request, string $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        $rombel = Rombel::findOrFail($id);
        
        try {
            $import = new RombelSiswaImport($id);
            Excel::import($import, $request->file('file'));
            
            $errors = $import->getErrors();
            $validCount = $import->getValidCount();
            
            return response()->json([
                'success' => true,
                'message' => 'Preview berhasil diproses',
                'total' => $validCount + count($errors),
                'valid_count' => $validCount,
                'errors' => $errors,
                'rombel_id' => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Error previewing rombel siswa import: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    /**
     * Process the Excel file import for siswa to rombel.
     */
    public function siswaProcess(Request $request, string $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new RombelSiswaImport($id);
            
            // First, get the collection to process
            $collection = Excel::toCollection($import, $request->file('file'));
            $rows = $collection->first();
            
            // Process the import
            $importedCount = $import->processImport($rows);
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimport {$importedCount} siswa ke rombel",
                'imported_count' => $importedCount,
                'rombel_id' => $id
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing rombel siswa import: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel file for siswa import.
     */
    public function siswaTemplate(string $id)
    {
        $rombel = Rombel::findOrFail($id);
        
        try {
            $template = new RombelSiswaTemplateExport($rombel);
            $fileName = 'template_siswa_rombel_' . str_replace(' ', '_', $rombel->nama_rombel) . '.xlsx';
            
            return Excel::download($template, $fileName);
        } catch (\Exception $e) {
            Log::error('Error generating rombel siswa template: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }

    /**
     * Remove siswa from rombel.
     */
    public function siswaRemove(string $rombelId, string $nisn)
    {
        try {
            $rombelDetail = RombelDetail::where('rombel_id', $rombelId)
                ->where('nisn', $nisn)
                ->first();
            
            if (!$rombelDetail) {
                return redirect()->back()
                    ->with('error', 'Siswa tidak ditemukan di rombel ini.');
            }
            
            $rombelDetail->delete();
            
            return redirect()->route('admin.rombel.siswa', $rombelId)
                ->with('success', 'Siswa berhasil dihapus dari rombel.');
                
        } catch (\Exception $e) {
            Log::error('Error removing siswa from rombel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus siswa dari rombel: ' . $e->getMessage());
        }
    }

    /**
     * Store a new student to the rombel.
     */
    public function siswaStore(Request $request, string $rombelId)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|string|max:20|exists:siswa,nisn',
        ], [
            'nisn.exists' => 'NISN tidak ditemukan di sistem. Pastikan NISN sudah terdaftar.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check if student already exists in this rombel
            $existingInRombel = RombelDetail::where('rombel_id', $rombelId)
                ->where('nisn', $request->nisn)
                ->first();

            if ($existingInRombel) {
                return redirect()->back()
                    ->with('error', 'Siswa dengan NISN ini sudah ada di rombel ini.')
                    ->withInput();
            }

            // Get the student
            $siswa = Siswa::where('nisn', $request->nisn)->first();

            if (!$siswa) {
                return redirect()->back()
                    ->with('error', 'Siswa tidak ditemukan di sistem.')
                    ->withInput();
            }

            // Add student to rombel
            RombelDetail::create([
                'rombel_id' => $rombelId,
                'nisn' => $siswa->nisn,
            ]);

            return redirect()->route('admin.rombel.siswa', $rombelId)
                ->with('success', 'Siswa ' . $siswa->nama_siswa . ' berhasil ditambahkan ke rombel.');

        } catch (\Exception $e) {
            Log::error('Error adding student to rombel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan siswa ke rombel: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Search student by NISN via AJAX
     */
    public function searchStudent(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:20'
        ]);

        try {
            $student = Siswa::where('nisn', $request->nisn)->first();

            if ($student) {
                // Check if student already exists in this rombel
                $rombelId = $request->rombel_id;
                $existingInRombel = RombelDetail::where('rombel_id', $rombelId)
                    ->where('nisn', $student->nisn)
                    ->first();

                return response()->json([
                    'success' => true,
                    'student' => [
                        'nisn' => $student->nisn,
                        'nama_siswa' => $student->nama_siswa,
                        'jenis_kelamin' => $student->jenis_kelamin,
                        'email' => $student->email,
                    ],
                    'already_in_rombel' => $existingInRombel ? true : false
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa dengan NISN ini tidak ditemukan'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error searching student: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data siswa'
            ], 500);
        }
    }

    /**
     * Show mata pelajaran for the specified rombel.
     */
    public function mapel(string $id)
    {
        $rombel = Rombel::with(['tingkatKelas', 'tahunAjaran', 'waliKelas', 'rombelMapels.mataPelajaran'])->findOrFail($id);
        return view('admin.rombel.mapel', compact('rombel'));
    }

    /**
     * Remove mata pelajaran from rombel.
     */
    public function mapelRemove(string $rombelId, int $mapelId)
    {
        try {
            $rombelMapel = RombelMapel::where('rombel_id', $rombelId)
                ->where('id', $mapelId)
                ->first();
            
            if (!$rombelMapel) {
                return redirect()->back()
                    ->with('error', 'Mata pelajaran tidak ditemukan di rombel ini.');
            }
            
            $rombelMapel->delete();
            
            return redirect()->route('admin.rombel.mapel', $rombelId)
                ->with('success', 'Mata pelajaran berhasil dihapus dari rombel.');
                
        } catch (\Exception $e) {
            Log::error('Error removing mapel from rombel: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus mata pelajaran dari rombel: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified rombel_mapel.
     */
    public function mapelEdit(string $rombelId, int $mapelId)
    {
        $rombel = Rombel::findOrFail($rombelId);
        $rombelMapel = RombelMapel::findOrFail($mapelId);
        $mataPelajaran = \App\Models\MataPelajaran::all();
        
        return view('admin.rombel.mapel_edit', compact('rombel', 'rombelMapel', 'mataPelajaran'));
    }

    /**
     * Update the specified rombel_mapel in storage.
     */
    public function mapelUpdate(Request $request, string $rombelId, int $mapelId)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        ]);

        $rombelMapel = RombelMapel::findOrFail($mapelId);
        $rombelMapel->update([
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ]);
        
        return redirect()->route('admin.rombel.mapel', $rombelId)
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Show the import form for rombel.
     */
    public function import()
    {
        return view('admin.rombel.import');
    }

    /**
     * Preview the Excel file before importing rombel.
     */
    public function preview(Request $request)
    {
        Log::info('Rombel preview method called');
        Log::info('Request data:', $request->all());
        
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);
            
            Log::info('File validation passed');
            Log::info('File details:', [
                'name' => $request->file('file')->getClientOriginalName(),
                'size' => $request->file('file')->getSize(),
                'mime' => $request->file('file')->getMimeType()
            ]);

            $import = new RombelImport(true);
            Log::info('RombelImport instance created');
            
            Excel::import($import, $request->file('file'));
            Log::info('Excel import completed');

            $previewData = $import->getPreviewData();
            $errors = $import->getErrors();
            
            Log::info('Preview data count: ' . count($previewData));
            Log::info('Errors count: ' . count($errors));

            return response()->json([
                'success' => true,
                'data' => $previewData,
                'errors' => $errors,
                'total' => count($previewData),
                'valid_count' => count($previewData) - count($errors)
            ]);
        } catch (\Exception $e) {
            Log::error('Preview error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    /**
     * Process the Excel file import for rombel.
     */
    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new RombelImport(false);
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $failCount = $import->getFailCount();
            $errors = $import->getErrors();

            return response()->json([
                'success' => true,
                'message' => 'Import selesai diproses',
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template Excel file for rombel import.
     */
    public function template()
    {
        Log::info('Rombel template download requested');
        try {
            $template = new RombelTemplateExport();
            Log::info('Template object created successfully');
            
            $response = Excel::download($template, 'template_rombel.xlsx');
            Log::info('Template download response generated');
            
            // Ensure proper headers for download
            if (method_exists($response, 'headers')) {
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment; filename="template_rombel.xlsx"');
                $response->headers->set('Cache-Control', 'max-age=0');
            }
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Error generating template: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }
}