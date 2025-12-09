<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\PertanyaanSoal;
use App\Models\JawabanSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PertanyaanSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        $pertanyaanSoals = PertanyaanSoal::where('bank_soal_id', $bankSoalId)
            ->orderBy('id', 'asc')
            ->get();
            
        return view('guru.pertanyaan_soal.index', compact('bankSoal', 'pertanyaanSoals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        return view('guru.pertanyaan_soal.create', compact('bankSoal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        // If type_test is 'pretest', only allow 'pilihan_ganda' as jenis_soal
        $jenisSoalRules = $bankSoal->type_test === 'pretest'
            ? 'required|in:pilihan_ganda'
            : 'required|in:pilihan_ganda,esai,benar_salah';
        
        // Base validation rules
        $validationRules = [
            'jenis_soal' => $jenisSoalRules,
            'pertanyaan' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bobot_benar' => 'nullable|numeric|min:0',
            'bobot_salah' => 'nullable|numeric',
        ];
        
        // Add validation rules based on jenis_soal
        if ($request->jenis_soal === 'pilihan_ganda') {
            $validationRules['jawaban'] = 'required|array|min:2';
            $validationRules['jawaban.*'] = 'required|string|max:255';
            $validationRules['is_benar'] = 'required|string';
            $validationRules['gambar_jawaban.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } elseif ($request->jenis_soal === 'benar_salah') {
            $validationRules['jawaban_benar_salah'] = 'required|in:T,F';
        }
        
        $request->validate($validationRules);

        // Use transaction to ensure data integrity
        return DB::transaction(function () use ($request, $bankSoalId) {
            $data = $request->all();
            $data['bank_soal_id'] = $bankSoalId;
            
            // Handle image upload
            if ($request->hasFile('gambar_soal')) {
                $image = $request->file('gambar_soal');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/pertanyaan_soals'), $imageName);
                $data['gambar_soal'] = $imageName;
            }
            
            // Create pertanyaan soal
            $pertanyaanSoal = PertanyaanSoal::create($data);
            
            // Handle jawaban based on jenis_soal
            if ($request->jenis_soal === 'pilihan_ganda') {
                $this->saveJawabanPilihanGanda($request, $pertanyaanSoal);
            } elseif ($request->jenis_soal === 'benar_salah') {
                $this->saveJawabanBenarSalah($request, $pertanyaanSoal);
            }
            
            return redirect()->route('guru.pertanyaan_soal.index', $bankSoalId)
                ->with('success', 'Pertanyaan Soal berhasil ditambahkan.');
        });
    }
    
    /**
     * Save jawaban for pilihan_ganda type
     */
    private function saveJawabanPilihanGanda(Request $request, PertanyaanSoal $pertanyaanSoal)
    {
        $jawabanData = $request->jawaban;
        $isBenarValue = $request->is_benar;
        $gambarJawaban = $request->file('gambar_jawaban') ?? [];
        
        foreach ($jawabanData as $opsi => $isiJawaban) {
            // Skip empty jawaban
            if (empty(trim($isiJawaban))) {
                continue;
            }
            
            $jawaban = new JawabanSoal();
            $jawaban->pertanyaan_id = $pertanyaanSoal->id;
            $jawaban->opsi = $opsi;
            $jawaban->isi_jawaban = $isiJawaban;
            $jawaban->is_benar = ($opsi === $isBenarValue);
            
            // Handle gambar jawaban if exists
            if (isset($gambarJawaban[$opsi]) && $gambarJawaban[$opsi]) {
                $image = $gambarJawaban[$opsi];
                $imageName = 'jawaban_' . $opsi . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/jawaban_soals'), $imageName);
                $jawaban->gambar_jawaban = $imageName;
            }
            
            $jawaban->save();
        }
    }
    
    /**
     * Save jawaban for benar_salah type
     */
    private function saveJawabanBenarSalah(Request $request, PertanyaanSoal $pertanyaanSoal)
    {
        $jawabanBenar = new JawabanSoal();
        $jawabanBenar->pertanyaan_id = $pertanyaanSoal->id;
        $jawabanBenar->opsi = 'T';
        $jawabanBenar->isi_jawaban = 'Benar';
        $jawabanBenar->is_benar = ($request->jawaban_benar_salah === 'T');
        $jawabanBenar->save();
        
        $jawabanSalah = new JawabanSoal();
        $jawabanSalah->pertanyaan_id = $pertanyaanSoal->id;
        $jawabanSalah->opsi = 'F';
        $jawabanSalah->isi_jawaban = 'Salah';
        $jawabanSalah->is_benar = ($request->jawaban_benar_salah === 'F');
        $jawabanSalah->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($bankSoalId, $id)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        $pertanyaanSoal = PertanyaanSoal::findOrFail($id);
        
        // Check if the pertanyaan soal belongs to the bank soal
        if ($pertanyaanSoal->bank_soal_id != $bankSoalId) {
            abort(404, 'Pertanyaan Soal not found.');
        }
        
        $pertanyaanSoal->load(['bankSoal', 'jawabanSoals']);
        
        return view('guru.pertanyaan_soal.show', compact('bankSoal', 'pertanyaanSoal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($bankSoalId, $id)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        $pertanyaanSoal = PertanyaanSoal::findOrFail($id);
        
        // Check if the pertanyaan soal belongs to the bank soal
        if ($pertanyaanSoal->bank_soal_id != $bankSoalId) {
            abort(404, 'Pertanyaan Soal not found.');
        }
        
        // Load the jawabanSoals relationship
        $pertanyaanSoal->load('jawabanSoals');
        
        return view('guru.pertanyaan_soal.edit', compact('bankSoal', 'pertanyaanSoal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bankSoalId, $id)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        $pertanyaanSoal = PertanyaanSoal::findOrFail($id);
        
        // Check if the pertanyaan soal belongs to the bank soal
        if ($pertanyaanSoal->bank_soal_id != $bankSoalId) {
            abort(404, 'Pertanyaan Soal not found.');
        }
        
        // If type_test is 'pretest', only allow 'pilihan_ganda' as jenis_soal
        $jenisSoalRules = $bankSoal->type_test === 'pretest'
            ? 'required|in:pilihan_ganda'
            : 'required|in:pilihan_ganda,esai,benar_salah';
        
        // Base validation rules
        $validationRules = [
            'jenis_soal' => $jenisSoalRules,
            'pertanyaan' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bobot_benar' => 'nullable|numeric|min:0',
            'bobot_salah' => 'nullable|numeric',
        ];
        
        // Add validation rules based on jenis_soal
        if ($request->jenis_soal === 'pilihan_ganda') {
            $validationRules['jawaban'] = 'required|array|min:2';
            $validationRules['jawaban.*'] = 'required|string|max:255';
            $validationRules['is_benar'] = 'required|string';
            $validationRules['gambar_jawaban.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } elseif ($request->jenis_soal === 'benar_salah') {
            $validationRules['jawaban_benar_salah'] = 'required|in:T,F';
        }
        
        $request->validate($validationRules);

        // Use transaction to ensure data integrity
        return DB::transaction(function () use ($request, $bankSoalId, $pertanyaanSoal) {
            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('gambar_soal')) {
                // Delete old image if exists
                if ($pertanyaanSoal->gambar_soal && file_exists(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal))) {
                    unlink(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal));
                }
                
                $image = $request->file('gambar_soal');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/pertanyaan_soals'), $imageName);
                $data['gambar_soal'] = $imageName;
            }
            
            // Update pertanyaan soal
            $pertanyaanSoal->update($data);
            
            // Handle jawaban based on jenis_soal
            if ($request->jenis_soal === 'pilihan_ganda') {
                // Delete existing jawaban soals
                JawabanSoal::where('pertanyaan_id', $pertanyaanSoal->id)->delete();
                
                // Save new jawaban soals
                $this->saveJawabanPilihanGanda($request, $pertanyaanSoal);
            } elseif ($request->jenis_soal === 'benar_salah') {
                // Delete existing jawaban soals
                JawabanSoal::where('pertanyaan_id', $pertanyaanSoal->id)->delete();
                
                // Save new jawaban soals
                $this->saveJawabanBenarSalah($request, $pertanyaanSoal);
            }
            
            return redirect()->route('guru.pertanyaan_soal.index', $bankSoalId)
                ->with('success', 'Pertanyaan Soal berhasil diperbarui.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bankSoalId, $id)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        $pertanyaanSoal = PertanyaanSoal::findOrFail($id);
        
        // Check if the pertanyaan soal belongs to the bank soal
        if ($pertanyaanSoal->bank_soal_id != $bankSoalId) {
            abort(404, 'Pertanyaan Soal not found.');
        }
        
        // Delete image if exists
        if ($pertanyaanSoal->gambar_soal && file_exists(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal))) {
            unlink(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal));
        }
        
        $pertanyaanSoal->delete();
        
        return redirect()->route('guru.pertanyaan_soal.index', $bankSoalId)
            ->with('success', 'Pertanyaan Soal berhasil dihapus.');
    }
    
    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request, $bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'integer|exists:pertanyaan_soals,id'
        ]);
        
        $selectedItems = $request->selected_items;
        
        return DB::transaction(function () use ($selectedItems, $bankSoalId) {
            // Get all pertanyaan soals to delete
            $pertanyaanSoals = PertanyaanSoal::whereIn('id', $selectedItems)
                ->where('bank_soal_id', $bankSoalId)
                ->get();
            
            foreach ($pertanyaanSoals as $pertanyaanSoal) {
                // Delete image if exists
                if ($pertanyaanSoal->gambar_soal && file_exists(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal))) {
                    unlink(public_path('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal));
                }
                
                // Delete related jawaban soals
                JawabanSoal::where('pertanyaan_id', $pertanyaanSoal->id)->delete();
                
                // Delete the pertanyaan soal
                $pertanyaanSoal->delete();
            }
            
            return redirect()->route('guru.pertanyaan_soal.index', $bankSoalId)
                ->with('success', count($selectedItems) . ' Pertanyaan Soal berhasil dihapus.');
        });
    }
    
    /**
     * Download template for import
     */
    public function template($bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'jenis_soal');
        $sheet->setCellValue('B1', 'pertanyaan');
        $sheet->setCellValue('C1', 'opsi_a');
        $sheet->setCellValue('D1', 'opsi_b');
        $sheet->setCellValue('E1', 'opsi_c');
        $sheet->setCellValue('F1', 'opsi_d');
        $sheet->setCellValue('G1', 'opsi_e');
        $sheet->setCellValue('H1', 'jawaban_benar');
        $sheet->setCellValue('I1', 'bobot_benar');
        $sheet->setCellValue('J1', 'bobot_salah');
        
        // Set sample data
        $sheet->setCellValue('A2', 'pilihan_ganda');
        $sheet->setCellValue('B2', 'Contoh pertanyaan pilihan ganda');
        $sheet->setCellValue('C2', 'Opsi A');
        $sheet->setCellValue('D2', 'Opsi B');
        $sheet->setCellValue('E2', 'Opsi C');
        $sheet->setCellValue('F2', 'Opsi D');
        $sheet->setCellValue('G2', 'Opsi E');
        $sheet->setCellValue('H2', 'A');
        $sheet->setCellValue('I2', '1.00');
        $sheet->setCellValue('J2', '0.00');
        
        $sheet->setCellValue('A3', 'benar_salah');
        $sheet->setCellValue('B3', 'Contoh pertanyaan benar salah');
        $sheet->setCellValue('H3', 'T');
        $sheet->setCellValue('I3', '1.00');
        $sheet->setCellValue('J3', '0.00');
        
        $sheet->setCellValue('A4', 'esai');
        $sheet->setCellValue('B4', 'Contoh pertanyaan esai');
        $sheet->setCellValue('I4', '1.00');
        $sheet->setCellValue('J4', '0.00');
        
        // Auto-size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create writer and save to temporary file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'template_pertanyaan_soal_' . date('YmdHis') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        // Return file as download
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Import pertanyaan soal from Excel file
     */
    public function import(Request $request, $bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);
        
        $file = $request->file('file');
        
        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            unset($rows[0]);
            
            $successCount = 0;
            $failCount = 0;
            $errors = [];
            
            DB::transaction(function () use ($rows, $bankSoalId, &$successCount, &$failCount, &$errors) {
                foreach ($rows as $index => $row) {
                    $rowNum = $index + 2; // Add 2 because we skipped header and arrays are 0-indexed
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    try {
                        // Validate required fields
                        if (empty($row[0]) || empty($row[1])) {
                            throw new \Exception('jenis_soal dan pertanyaan wajib diisi');
                        }
                        
                        // Validate jenis_soal
                        if (!in_array($row[0], ['pilihan_ganda', 'esai', 'benar_salah'])) {
                            throw new \Exception('jenis_soal harus berisi: pilihan_ganda, esai, atau benar_salah');
                        }
                        
                        // For pilihan_ganda, validate jawaban_benar and options
                        if ($row[0] === 'pilihan_ganda') {
                            if (empty($row[7]) || !in_array($row[7], ['A', 'B', 'C', 'D', 'E'])) {
                                throw new \Exception('jawaban_benar harus berisi salah satu dari: A, B, C, D, E');
                            }
                            
                            // Check that at least 2 options are provided
                            $optionCount = 0;
                            for ($i = 2; $i <= 6; $i++) {
                                if (!empty($row[$i])) {
                                    $optionCount++;
                                }
                            }
                            
                            if ($optionCount < 2) {
                                throw new \Exception('Minimal harus ada 2 opsi jawaban');
                            }
                        }
                        
                        // For benar_salah, validate jawaban_benar
                        if ($row[0] === 'benar_salah') {
                            if (empty($row[7]) || !in_array($row[7], ['T', 'F'])) {
                                throw new \Exception('jawaban_benar harus berisi T (Benar) atau F (Salah)');
                            }
                        }
                        
                        // Create pertanyaan soal
                        $pertanyaanSoal = PertanyaanSoal::create([
                            'bank_soal_id' => $bankSoalId,
                            'jenis_soal' => $row[0],
                            'pertanyaan' => $row[1],
                            'bobot_benar' => !empty($row[8]) ? floatval($row[8]) : 1.00,
                            'bobot_salah' => !empty($row[9]) ? floatval($row[9]) : 0.00,
                        ]);
                        
                        // Create jawaban soals based on jenis_soal
                        if ($row[0] === 'pilihan_ganda') {
                            // Create options A-E if they exist
                            $options = ['A', 'B', 'C', 'D', 'E'];
                            foreach ($options as $i => $option) {
                                $colIndex = $i + 2; // Column index for option (C=2, D=3, etc.)
                                
                                if (!empty($row[$colIndex])) {
                                    JawabanSoal::create([
                                        'pertanyaan_id' => $pertanyaanSoal->id,
                                        'opsi' => $option,
                                        'isi_jawaban' => $row[$colIndex],
                                        'is_benar' => ($option === $row[7]),
                                    ]);
                                }
                            }
                        } elseif ($row[0] === 'benar_salah') {
                            // Create T (Benar) option
                            JawabanSoal::create([
                                'pertanyaan_id' => $pertanyaanSoal->id,
                                'opsi' => 'T',
                                'isi_jawaban' => 'Benar',
                                'is_benar' => ($row[7] === 'T'),
                            ]);
                            
                            // Create F (Salah) option
                            JawabanSoal::create([
                                'pertanyaan_id' => $pertanyaanSoal->id,
                                'opsi' => 'F',
                                'isi_jawaban' => 'Salah',
                                'is_benar' => ($row[7] === 'F'),
                            ]);
                        }
                        
                        $successCount++;
                    } catch (\Exception $e) {
                        $failCount++;
                        $errors[] = [
                            'row' => $rowNum,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            });
            
            return response()->json([
                'success' => true,
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }
}