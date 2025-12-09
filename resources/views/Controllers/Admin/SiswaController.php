<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Imports\SiswaImport;
use App\Exports\SiswaTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::latest()->paginate(10);
        return view('admin.siswa.index', compact('siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:255|unique:siswa',
            'password' => 'required|string|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        Siswa::create($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->nisn . ',nisn',
            'nama_siswa' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:255|unique:siswa,email,' . $siswa->nisn . ',nisn',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->all();
        
        // Only hash password if it's provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Show the import form.
     */
    public function import()
    {
        return view('admin.siswa.import');
    }

    /**
     * Preview the Excel file before importing.
     */
    public function preview(Request $request)
    {
        Log::info('Preview method called');
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

            $import = new SiswaImport(true);
            Log::info('SiswaImport instance created');
            
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
     * Process the Excel file import.
     */
    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new SiswaImport(false);
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
     * Download template Excel file.
     */
    public function template()
    {
        Log::info('Siswa template download requested');
        try {
            $template = new SiswaTemplateExport();
            Log::info('Template object created successfully');
            
            $response = Excel::download($template, 'template_siswa.xlsx');
            Log::info('Template download response generated');
            
            // Ensure proper headers for download
            if (method_exists($response, 'headers')) {
                $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                $response->headers->set('Content-Disposition', 'attachment; filename="template_siswa.xlsx"');
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