<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Imports\GuruImport;
use App\Exports\GuruTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guru = Guru::paginate(10);
        return view('admin.guru.index', compact('guru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_guru' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16|min:16',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        
        Guru::create($data);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_guru' => 'required|string|max:255',
            'nik' => 'nullable|string|max:16|min:16',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $guru = Guru::findOrFail($id);
        $data = $request->all();
        
        // Only update password if it's provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        
        $guru->update($data);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Show the import form.
     */
    public function import()
    {
        return view('admin.guru.import');
    }

    /**
     * Show the import form for importfix.
     */
    public function importfix()
    {
        return view('admin.guru.importfix');
    }

    /**
     * Preview the Excel file before importing.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        Log::info('Guru preview method called');
        Log::info('Request data:', $request->all());
        
        try {
            $import = new GuruImport(true);
            Log::info('GuruImport instance created');
            
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
            $import = new GuruImport(false);
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
        Log::info('Guru template download requested');
        try {
            $template = new GuruTemplateExport();
            Log::info('Template object created successfully');
            
            $response = Excel::download($template, 'template_guru.xlsx');
            Log::info('Template download response generated');
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Error generating template: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }

}