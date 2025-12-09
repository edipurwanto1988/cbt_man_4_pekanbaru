<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\JawabanSoal;
use App\Models\PertanyaanSoal;
use App\Models\PosttestHasil;
use App\Models\BankSoal;
use App\Models\BankSoalRombel;
use App\Models\PretestHasil;
use App\Models\PretestSession;
use App\Models\PretestPeserta;
use App\Models\PosttestPeserta;
use App\Models\PretestSoalTimer;

use App\Models\Rombel;
use App\Models\RombelDetail;
use App\Models\Siswa;
use App\Models\PretestLog;
use App\Models\PosttestLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Integer;

class ExamController extends Controller
{
    /**
     * Display available exams for the logged-in student
     */
    public function index()
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get available bank soals for this student based on their rombel
            $availableExams = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', '=', 'bank_soals.mapel_id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->leftJoin('siswa', 'siswa.nisn', '=', 'rombel_detail.nisn')
                ->select(
                    'bank_soals.id',
                    'bank_soals.kode_bank',
                    'bank_soals.nama_bank',
                    'mata_pelajaran.nama_mapel',
                    'bank_soals.type_test',
                    'bank_soals.tanggal_mulai',
                    'bank_soals.tanggal_selesai',
                    'bank_soals.durasi_menit'
                )
                ->where('bank_soals.status', 'Aktif')
                ->where('siswa.nisn', $nisn)
                ->where(function ($query) {
                    $query->whereNull('bank_soals.tanggal_mulai')
                        ->orWhere('bank_soals.tanggal_mulai', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('bank_soals.tanggal_selesai')
                        ->orWhere('bank_soals.tanggal_selesai', '>=', now());
                })
                ->distinct()
                ->get();

            // Check for pretest sessions and posttest time range for each exam
            foreach ($availableExams as $exam) {
                if (strtolower($exam->type_test) === 'pretest') {
                    $hasSession = PretestSession::where('bank_soal_id', $exam->id)->exists();
                    $exam->has_pretest_session = $hasSession;
                    $exam->can_start = $hasSession;
                } else {
                    // For non-pretest (including posttest), check time range
                    $exam->has_pretest_session = true; // Not relevant for non-pretest
                    $exam->can_start = $this->isWithinTimeRange($exam);
                }
            }
            
            return view('participant.exams.index', compact('availableExams'));

        } catch (\Exception $e) {
            Log::error('Error in ExamController@index: ' . $e->getMessage());
            return redirect()->route('participant.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar ujian: ' . $e->getMessage());
        }
    }

    /**
     * Start a specific exam
     */
    public function start($bankSoalId)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify if this student has access to this exam
            $hasAccess = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->where('bank_soals.id', $bankSoalId)
                ->where('bank_soals.status', 'Aktif')
                ->where('rombel_detail.nisn', $nisn)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Anda tidak memiliki akses ke ujian ini');
            }

            // Get bank soal details
            $bankSoal = BankSoal::with(['pertanyaanSoals', 'mataPelajaran'])
                ->findOrFail($bankSoalId);

            // Check if exam is within time window
            if ($bankSoal->tanggal_mulai && $bankSoal->tanggal_mulai > now()) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Ujian ini belum dimulai');
            }

            if ($bankSoal->tanggal_selesai && $bankSoal->tanggal_selesai < now()) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Ujian ini telah berakhir');
            }

            // Get appropriate instructions based on exam type
            $instructionKey = strtolower($bankSoal->type_test) === 'pretest'
                ? 'Instruksi_Pretest'
                : 'Instruksi_Posttest';

            $instruction = Setting::where('key', $instructionKey)->value('value') ?? '';

            return view('participant.exams.start', compact('bankSoal', 'instruction'));

        } catch (\Exception $e) {
            return redirect()->route('participant.exams.index')
                ->with('error', 'Terjadi kesalahan saat memulai ujian: ' . $e->getMessage());
        }
    }

    public function startCountdown(Request $request, $bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);

        // Check if there's an active session for this bank soal
        $session = PretestSession::where('bank_soal_id', $bankSoalId)
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sesi pretest aktif untuk bank soal ini',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Countdown dimulai',
            'session_id' => $session->id,
            'kode_sesi' => $session->kode_sesi,
        ]);
    }


 public function endExam(Request $request, $bankSoalId)
{
    try {
        $nisn = Auth::guard('siswa')->user()->nisn;
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
            ->where('nisn', $nisn)
            ->firstOrFail();

        // Ambil semua log jawaban peserta untuk ujian ini
        $logs = PosttestLog::where('bank_soal_id', $bankSoalId)
            ->where('nisn', $nisn)
            ->get();

        // Hitung statistik dari log
        $total_benar = $logs->where('jawaban_benar_salah', 1)->count();
        $total_salah = $logs->where('jawaban_benar_salah', 0)->where('jawaban_esai', '!=', null)->count();
        $total_kosong = $logs->where('jawaban_pilihan', null)->where('jawaban_esai', null)->count();
        
        // Hitung skor berdasarkan bobot soal
        $skor = $logs->sum(function($log) {
            if ($log->is_benar == 1) {
                return $log->pertanyaan->bobot ?? 0; // Asumsi ada relasi ke pertanyaan
            }
            return 0;
        });

      // Hitung durasi pengerjaan
        $waktuMulai = Carbon::parse($participant->waktu_mulai);
        $waktuSekarang = Carbon::now();
        $durasi_detik = $waktuMulai->diffInSeconds($waktuSekarang);
        
        // Format waktu pengerjaan menggunakan diff()->format() seperti kode lama
        $waktuPengerjaan = $waktuMulai->diff($waktuSekarang)->format('%H:%I:%S');

        // Simpan atau update hasil posttest
        $postTest = PosttestHasil::updateOrCreate(
            [
                'bank_soal_id' => $bankSoalId,
                'nisn' => $nisn
            ],
            [
                'pertanyaan_id' => $bankSoalId, // Sesuaikan dengan kebutuhan
                'total_benar' => $total_benar,
                'total_salah' => $total_salah,
                'total_kosong' => $total_kosong,
                'skor' => $skor,
                'durasi_detik' => $durasi_detik
            ]
        );

        // Update status peserta menjadi selesai
        $participant->update([
            'status' => 'finished',
            'waktu_selesai' => Carbon::now()
        ]);

        // Hitung total questions
        $totalQuestions = $total_salah + $total_benar + $total_kosong;
        $answeredQuestions = $totalQuestions - $total_kosong;
        
         

        // Redirect ke halaman finish view
        return view('participant.exams.finished', [
            'bankSoal' => $bankSoal,
            'totalQuestions' => $totalQuestions,
            'answeredQuestions' => $answeredQuestions,
            'timeSpent' => $waktuPengerjaan,
            'resultUrl' => route('participant.exams.result', [$bankSoalId])
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    /**
     * Take a specific exam
     */
    public function take($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;


            // Verify if this student has access to this exam
            $hasAccess = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->where('bank_soals.id', $bankSoalId)
                ->where('bank_soals.status', 'Aktif')
                ->where('rombel_detail.nisn', $nisn)
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Anda tidak memiliki akses ke ujian ini');
            }

            // Get bank soal details
            $bankSoal = BankSoal::with(['pertanyaanSoals', 'mataPelajaran'])
                ->findOrFail($bankSoalId);

            // Check if exam is within time window
            if ($bankSoal->tanggal_mulai && $bankSoal->tanggal_mulai > now()) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Ujian ini belum dimulai');
            }

            if ($bankSoal->tanggal_selesai && $bankSoal->tanggal_selesai < now()) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Ujian ini telah berakhir');
            }

            // If it's a pretest, redirect to waiting room
            if (strtolower($bankSoal->type_test) === 'pretest') {
                // Get or create pretest session
                $pretestSession = PretestSession::where('bank_soal_id', $bankSoalId)->first();

                if (!$pretestSession) {
                    return redirect()->route('participant.exams.index')
                        ->with('error', 'Sesi pretest belum dimulai oleh guru');
                }

                // Get student info for avatar
                $siswa = Siswa::where('nisn', $nisn)->first();
                $avatarUrl = 'https://api.dicebear.com/8.x/adventurer/svg?seed=' . urlencode($siswa->nama_siswa ?? $nisn);

                // Check if participant already exists
                $participant = PretestPeserta::where('session_id', $pretestSession->id)
                    ->where('nisn', $nisn)
                    ->first();

                if (!$participant) {
                    // Create new participant
                    PretestPeserta::create([
                        'session_id' => $pretestSession->id,
                        'bank_soal_id' => $bankSoalId,
                        'nisn' => $nisn,
                        'status' => 'waiting',
                    ]);
                }

                // Get all participants for this session
                $participants = PretestPeserta::with('siswa')
                    ->where('session_id', $pretestSession->id)
                    ->where('nisn', $nisn)
                    ->get();

                return view('participant.exams.waiting-room', compact('bankSoal', 'pretestSession', 'participants'));
            }

            // If it's a posttest, check if participant exists and create if needed
            if (strtolower($bankSoal->type_test) === 'posttest') {
                // Check if participant already exists
                $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                    ->where('nisn', $nisn)
                    ->first();

                if (!$participant) {
                    // Create new participant with initial sisa_detik
                    $initialSisaDetik = $bankSoal->durasi_menit * 60; // Convert minutes to seconds
                    PosttestPeserta::create([
                        'bank_soal_id' => $bankSoalId,
                        'nisn' => $nisn,
                        'start_time' => now(),
                        'sisa_detik' => $initialSisaDetik,
                        'status' => 'ongoing',
                    ]);

                    // Set remaining time to initial value
                    $remainingTime = $initialSisaDetik;
                } else {
                    // Check if exam is already finished or time is up
                    if ($participant->sisa_detik <= 0 || $participant->status === 'finished') {
                        return redirect()->route('participant.exams.index')
                            ->with('error', 'Ujian sudah selesai');
                    }

                    // Set remaining time from participant data
                    $remainingTime = $participant->sisa_detik;
                }

                // Get all questions for this bank soal
                $questions = $bankSoal->pertanyaanSoals()->with('jawabanSoals')->get();
                // dd($questions);
                if ($questions->isEmpty()) {
                    return redirect()->route('participant.exams.index')
                        ->with('error', 'Tidak ada soal yang tersedia untuk ujian ini');
                }

                return view('participant.exams.posttest', compact('bankSoal', 'questions', 'remainingTime', 'participant'));
            }

            // For other exam types, redirect back with a message that the exam functionality is being developed
            return redirect()->route('participant.exams.index')
                ->with('success', 'Ujian akan segera dimulai. Fitur ujian sedang dalam pengembangan.');

        } catch (\Exception $e) {
            return redirect()->route('participant.exams.index')
                ->with('error', 'Terjadi kesalahan saat mengambil ujian: ' . $e->getMessage());
        }
    }

    /**
     * Get waiting room participants via AJAX
     */

    public function history()
    {
        $nisn = Auth::guard('siswa')->user()->nisn;

        // Ambil semua riwayat pretest
        $pretestHistory = PretestHasil::with('bankSoal')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua riwayat posttest
        $posttestHistory = PosttestHasil::with('bankSoal')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('participant.history.index', [
            'pretestHistory' => $pretestHistory,
            'posttestHistory' => $posttestHistory,
        ]);
    }
    public function resultPage($sessionId)
    {

        // Ambil semua hasil pretest berdasarkan session
        $results = PretestHasil::with('siswa')->where('session_id', $sessionId)
            ->orderBy('total_poin', 'desc')
            ->orderBy('total_benar', 'desc')
            ->get();

        // dd($results);   
        return view('participant.exams.result', [
            'results' => $results
        ]);
    }

    private function acumulate($sessionId, $nisn)
    {
        // Ambil semua log siswa
        $logs = PretestLog::where('session_id', $sessionId)
            ->where('nisn', $nisn)
            ->get();

        $pretestSession = PretestSession::findOrFail($sessionId);

        if ($logs->isEmpty()) {
            return; // belum ada log
        }

        // Hitung total
        $totalScore = $logs->sum('poin');

        $totalCorrect = $logs->sum('benar');
        $totalQuestions = $logs->count();

        // Cek apakah hasil sudah ada
        $existing = PretestHasil::where('session_id', $sessionId)
            ->where('nisn', $nisn)
            ->first();

        if ($existing) {
            // UPDATE jika sudah ada
            $existing->update([
                'total_poin' => $totalScore,
                'total_benar' => $totalCorrect,
                'total_salah' => $totalQuestions - $totalCorrect,
            ]);
        } else {
            // CREATE jika belum ada
            PretestHasil::create([
                'bank_soal_id' => $pretestSession->bank_soal_id,
                'session_id' => $sessionId,
                'nisn' => $nisn,
                'total_poin' => $totalScore,
                'total_benar' => $totalCorrect,
                'total_salah' => $totalQuestions - $totalCorrect,
            ]);
        }
    }


    public function getWaitingRoomParticipants($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify if this student has access to this exam
            $hasAccess = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->where('bank_soals.id', $bankSoalId)
                ->where('bank_soals.status', 'Aktif')
                ->where('rombel_detail.nisn', $nisn)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke ujian ini'], 403);
            }

            // Get pretest session
            $pretestSession = PretestSession::where('bank_soal_id', $bankSoalId)->first();

            if (!$pretestSession) {
                return response()->json(['error' => 'Sesi pretest tidak ditemukan'], 404);
            }

            // Get all participants for this session
            $participants = PretestPeserta::with('siswa')
                ->where('session_id', $pretestSession->id)
                ->get();

            // Format participants data
            $participantsData = $participants->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'nisn' => $participant->nisn,
                    'nama_siswa' => $participant->siswa->nama_siswa ?? 'Peserta',
                    'avatar_url' => $participant->siswa->foto ? asset('storage/uploads/siswa/' . $participant->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->siswa->nama_siswa ?? 'Peserta') . '&background=6366f1&color=ffffff&size=48',
                    'skor_total' => 0, // Will be updated during the test
                ];
            });

            return response()->json([
                'success' => true,
                'participants' => $participantsData,
                'total_participants' => $participants->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check pretest session status via AJAX
     */
    public function checkPretestSessionStatus($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify if this student has access to this exam
            $hasAccess = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->where('bank_soals.id', $bankSoalId)
                ->where('bank_soals.status', 'Aktif')
                ->where('rombel_detail.nisn', $nisn)
                ->exists();

            if (!$hasAccess) {
                return response()->json(['error' => 'Anda tidak memiliki akses ke ujian ini'], 403);
            }

            // Get pretest session
            $pretestSession = PretestSession::where('bank_soal_id', $bankSoalId)->first();

            if (!$pretestSession) {
                return response()->json([
                    'success' => true,
                    'status' => 'not_started',
                    'message' => 'Sesi pretest belum dimulai'
                ]);
            }

            // Check if session is running
            if ($pretestSession->status === 'running') {
                // Get current question with answers
                $currentQuestion = null;
                if ($pretestSession->soal_aktif_id) {
                    $currentQuestion = DB::table('pertanyaan_soals')
                        ->where('id', $pretestSession->soal_aktif_id)
                        ->first();

                    if ($currentQuestion) {
                        // Get answers for this question
                        $answers = DB::table('jawaban_soals')
                            ->where('pertanyaan_id', $currentQuestion->id)
                            ->orderBy('opsi')
                            ->get();

                        // Format answers
                        $formattedAnswers = [];
                        foreach ($answers as $answer) {
                            $formattedAnswers[$answer->opsi] = $answer->isi_jawaban;
                        }

                        $currentQuestion->answers = $formattedAnswers;
                    }
                }

                return response()->json([
                    'success' => true,
                    'status' => 'running',
                    'session_id' => $pretestSession->id,
                    'current_step' => $pretestSession->step_soal ?? 1,
                    'current_question' => $currentQuestion,
                    'redirect_url' => route('participant.exams.take-live', $pretestSession->id)
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => 'waiting',
                'message' => 'Menunggu sesi dimulai'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Take live pretest exam
     */
    public function takeLive($sessionId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get pretest session
            $pretestSession = PretestSession::with('bankSoal.pertanyaanSoals.jawabanSoals')->findOrFail($sessionId);

            // Verify participant exists
            $participant = PretestPeserta::where('session_id', $sessionId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Anda tidak terdaftar sebagai peserta dalam sesi ini');
            }

            // Check if session is running
            if ($pretestSession->status !== 'running') {
                return redirect()->route('participant.exams.take', $pretestSession->bank_soal_id)
                    ->with('error', 'Sesi belum dimulai atau telah berakhir');
            }

            // Get current question
            $currentQuestion = null;
            if ($pretestSession->soal_aktif_id) {
                $currentQuestion = $pretestSession->bankSoal->pertanyaanSoals()
                    ->with('jawabanSoals')
                    ->where('id', $pretestSession->soal_aktif_id)
                    ->first();
            }

            return view('participant.exams.take-live', compact('pretestSession', 'currentQuestion', 'participant'));

        } catch (\Exception $e) {
            return redirect()->route('participant.exams.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get current question for live exam
     */
    public function getCurrentLiveQuestion($sessionId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get pretest session
            $pretestSession = PretestSession::with('bankSoal.pertanyaanSoals')->findOrFail($sessionId);

            // Verify participant exists
            $participant = PretestPeserta::where('session_id', $sessionId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return response()->json(['error' => 'Anda tidak terdaftar sebagai peserta'], 403);
            }

            // Get step_soal (default to 1 if not set)
            $stepSoal = $pretestSession->step_soal ?? 1;

            // Get the latest PretestSoalTimer for this session
            $soalTimer = PretestSoalTimer::where('session_id', $sessionId)
                ->orderBy('waktu_mulai', 'desc')
                ->first();

            // Get current question based on soalTimer's pertanyaan_id
            $currentQuestion = null;
            if ($soalTimer && $pretestSession->bankSoal) {
                $currentQuestion = $pretestSession->bankSoal->pertanyaanSoals
                    ->where('id', $soalTimer->pertanyaan_id)
                    ->first();
            }

            // Format question data
            $questionData = null;
            if ($currentQuestion) {
                $answers = [];
                foreach ($currentQuestion->jawabanSoals as $jawaban) {
                    $answers[$jawaban->opsi] = $jawaban->isi_jawaban;
                }

                $questionData = [
                    'id' => $currentQuestion->id,
                    'pertanyaan' => $currentQuestion->pertanyaan,
                    'answers' => $currentQuestion->jawabanSoals,
                    'max_time' => $pretestSession->bankSoal->max_time ?? 30,
                    'start_time' => $soalTimer->waktu_mulai ?? null,
                    'timer_id' => $soalTimer->id ?? null,
                ];
            }

            // Create response with cookie
            $response = response()->json([
                'success' => true,
                'question' => $questionData,
                'test' => $pretestSession,
                'step' => $soalTimer->urutan_soal,
                'total_questions' => $pretestSession->bankSoal->pertanyaanSoals->count(),
                'session_status' => $pretestSession->status,
                'current_question' => $currentQuestion,
                'soal_timer' => $soalTimer
            ]);

            // Save step_soal to cookie
            $cookieName = "pretest_step_{$sessionId}_{$nisn}";
            $response->cookie($cookieName, $stepSoal, 120); // 120 minutes expiry

            return $response;

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Submit answer for live exam
     */
    public function submitAnswer($sessionId, Request $request)
    {
        try {
            // Get current logged-in student NISN

            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get pretest session
            $pretestSession = PretestSession::findOrFail($sessionId);

            // Verify participant exists
            $participant = PretestPeserta::where('session_id', $sessionId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return response()->json(['error' => 'Anda tidak terdaftar sebagai peserta'], 403);
            }

            $answerId = (int) $request->answer;
            $jawaban = JawabanSoal::find($answerId);

            // Validate request
            $isTimeout = $request->boolean('is_timeout', false);
            $rules = [
                'time_taken' => 'nullable|numeric|min:0'
            ];

            if (!$isTimeout) {
                $rules['answer'] = 'required';
            } else {
                $rules['answer'] = 'nullable';
            }

            $request->validate($rules);

            $soalTimer = PretestSoalTimer::where('session_id', $sessionId)
                ->orderBy('waktu_mulai', 'desc')
                ->first();

            // Cek apakah ada timer (berarti ada soal aktif)
            if (!$soalTimer) {
                return response()->json(['error' => 'Tidak ada soal aktif'], 400);
            }
            // Check if already answered this question
            $existingLog = PretestLog::where('bank_soal_id', $pretestSession->bank_soal_id)
                ->where('session_id', $nisn)
                ->where('pertanyaan_id', $soalTimer->pertanyaan_id)
                ->first();

            if ($existingLog) {
                return response()->json(['error' => 'Anda sudah menjawab soal ini'], 400);
            }

            // Get the correct answer
            $correctAnswer = DB::table('jawaban_soals')
                ->where('pertanyaan_id', $soalTimer->pertanyaan_id)
                ->where('is_benar', true)
                ->first();

            // Calculate base score
            $answer = $request->answer;
            $isCorrect = $jawaban->is_benar ?? false;
            $baseScore = $isCorrect ? 1 : 0;

            // Calculate time-based bonus
            $bonusScore = 0;
            $startTime = Carbon::parse($soalTimer->waktu_mulai); // waktu mulai soal
            $now = Carbon::now();

            // Hitung detik yang sudah terpakai
            $timeTaken = $startTime->diffInSeconds($now);

            $maxTime = $pretestSession->bankSoal->max_time ?? 30; // total waktu soal (detik)
            $bonusScore = 0;

            if ($isCorrect && $timeTaken > 0 && $maxTime > 0) {
                // Sisa waktu = maxTime - timeTaken
                $remainingTime = max(0, $maxTime - $timeTaken);

                // Formula bonus
                $bonusScore = round(($remainingTime / $maxTime) * 1000);

                $bonusScore = max(0, $bonusScore); // jaga-jaga agar tidak minus
            }
            // Total score = base score + bonus score
            $totalScore = $baseScore + $bonusScore;



            // Create pretest log
            PretestLog::create([
                'nisn' => $nisn,
                'session_id' => $sessionId,
                'bank_soal_id' => $pretestSession->bank_soal_id,
                'jawaban_nisn' => $nisn,
                'jawaban_id' => $request->answer,
                'pertanyaan_id' => $soalTimer->pertanyaan_id,
                'benar' => $jawaban->is_benar,
                'waktu_mulai' => $pretestSession->mulai_at ?? now(),
                'waktu_respon' => now(),
                'skor_kecepatan' => $bonusScore,
                'poin' => $totalScore,
            ]);


            $this->acumulate($sessionId, $nisn);
            return response()->json([
                'success' => true,
                'message' => $isTimeout ? 'Waktu habis, jawaban tidak disimpan' : 'Jawaban berhasil disimpan',
                'is_correct' => $isCorrect,
                'is_timeout' => $isTimeout,
                'base_score' => $baseScore,
                'bonus_points' => $bonusScore,
                'total_score' => $totalScore,
                'time_taken' => $timeTaken,
                'max_time' => $maxTime
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Submit posttest exam
     */
    public function submitPosttest($bankSoalId, Request $request)
    {
        try {
            // Logged student
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get bank soal with questions
            $bankSoal = BankSoal::with('pertanyaanSoals.jawabanSoals')->findOrFail($bankSoalId);

            // Verify the student is registered as participant (PosttestPeserta table)
            $registered = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                ->where('nisn', $nisn)
                ->exists();

            if (!$registered) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Anda tidak terdaftar sebagai peserta dalam ujian ini');
            }

            // Get answers and durations
            $answers = json_decode($request->answers, true) ?: [];
            $durations = json_decode($request->durations, true) ?: [];

            if (empty($answers)) {
                return redirect()->route('participant.exams.index')
                    ->with('error', 'Tidak ada jawaban yang disimpan');
            }

            DB::beginTransaction();

            // Counting results & saving logs
            $totalQuestions = $bankSoal->pertanyaanSoals->count();
            $correct = 0;
            $totalScore = 0;
            $totalDurationSeconds = 0;

            foreach ($answers as $questionId => $selectedOption) {
                $correctAnswer = DB::table('jawaban_soals')
                    ->where('pertanyaan_id', $questionId)
                    ->where('is_benar', true)
                    ->first();

                $isCorrect = $correctAnswer && $correctAnswer->opsi === $selectedOption;

                if ($isCorrect) {
                    $correct++;
                    $totalScore += (100 / max(1, $totalQuestions));
                }

                $duration = isset($durations[$questionId]) ? intval($durations[$questionId]) : 0;
                $totalDurationSeconds += $duration;

                // Save per-question log
                PosttestLog::create([
                    'nisn' => $nisn,
                    'bank_soal_id' => $bankSoalId,
                    'pertanyaan_id' => $questionId,
                    'jawaban_pilihan' => $selectedOption,
                    'jawaban_benar_salah' => $isCorrect ? 1 : 0,
                    'jawaban_esai' => null,
                    'skor' => $isCorrect ? (100 / max(1, $totalQuestions)) : 0,
                    'is_benar' => $isCorrect ? 1 : 0,
                    'durasi_detik' => $duration,
                ]);
            }

            $finalScore = $totalScore;
            $totalWrong = $totalQuestions - $correct;
            $totalEmpty = 0; // if you want to calculate unanswered separately, adjust logic above

            // Create PostTestHasil record (or update if exists)
            $postTestHasil = PostTestHasil::firstOrNew(
                ['bank_soal_id' => $bankSoalId, 'nisn' => $nisn]
            );

            $postTestHasil->bank_soal_id = $bankSoalId;
            $postTestHasil->nisn = $nisn;
            $postTestHasil->total_benar = $correct;
            $postTestHasil->total_salah = $totalWrong;
            $postTestHasil->total_kosong = $totalEmpty;
            $postTestHasil->nilai_akhir = $finalScore;
            $postTestHasil->waktu_pengerjaan = $totalDurationSeconds;
            $postTestHasil->save();

            DB::commit();

            return redirect()->route('participant.exams.index')
                ->with('success', 'Ujian selesai! Skor Anda: ' . number_format($finalScore, 2) . '%');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('participant.exams.index')
                ->with('error', 'Terjadi kesalahan saat menyelesaikan ujian: ' . $e->getMessage());
        }
    }



    /**
     * Save duration data for posttest
     */
    public function saveDuration($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify participant exists
            $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return response()->json(['error' => 'Anda tidak terdaftar sebagai peserta'], 403);
            }

            // Get durations from request
            $durations = $request->durations ?: [];

            // Update or create posttest logs with duration data
            foreach ($durations as $questionId => $duration) {
                // Check if log already exists for this question
                $existingLog = PosttestLog::where('nisn', $nisn)
                    ->where('bank_soal_id', $bankSoalId)
                    ->where('pertanyaan_id', $questionId)
                    ->first();

                if ($existingLog) {
                    // Update existing log with duration
                    $existingLog->durasi_detik = $duration;
                    $existingLog->save();
                } else {
                    // Create a new log with duration and default values
                    PosttestLog::create([
                        'nisn' => $nisn,
                        'bank_soal_id' => $bankSoalId,
                        'pertanyaan_id' => $questionId,
                        'jawaban_pilihan' => null, // Will be updated when student submits answer
                        'jawaban_benar_salah' => 0,
                        'jawaban_esai' => null,
                        'skor' => 0,
                        'is_benar' => 0,
                        'durasi_detik' => $duration,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data durasi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Auto save answer for posttest
     */

    public function cheat($bankSoalId, Request $request)
    {


        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify participant exists


            $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                ->where('nisn', $nisn)->first();



            if (!$participant) {
                return response()->json(['error' => 'Anda tidak terdaftar sebagai peserta'], 403);
            }

            $participant->update(attributes: [
                'cheat_status' => 'blocked'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Anda ketahuan curang',
                'data' => $participant
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function autoSaveAnswer($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify participant exists
            $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return response()->json(['error' => 'Anda tidak terdaftar sebagai peserta'], 403);
            }

            // Validate request
            $request->validate([
                'question_id' => 'required|exists:pertanyaan_soals,id',
                'answer' => 'nullable|string|in:A,B,C,D,E',
                'duration' => 'nullable|numeric|min:0'
            ]);

            $questionId = $request->question_id;
            $answer = $request->answer;
            $duration = $request->duration ?? 0;

            // Get the correct answer for this question
            $correctAnswer = DB::table('jawaban_soals')
                ->where('pertanyaan_id', $questionId)
                ->where('is_benar', true)
                ->first();

            // Check if answer is correct
            $isCorrect = $correctAnswer && $correctAnswer->opsi === $answer;

            // Calculate score (assuming each question has equal weight)
            $totalQuestions = DB::table('pertanyaan_soals')
                ->where('bank_soal_id', $bankSoalId)
                ->count();

            $questionScore = $totalQuestions > 0 ? (100 / $totalQuestions) : 0;
            $score = $isCorrect ? $questionScore : 0;

            // Check if log already exists for this question
            $existingLog = PosttestLog::where('nisn', $nisn)
                ->where('bank_soal_id', $bankSoalId)
                ->where('pertanyaan_id', $questionId)
                ->first();

            if ($existingLog) {
                // Update existing log
                $existingLog->jawaban_pilihan = $answer;
                $existingLog->jawaban_benar_salah = $isCorrect ? 1 : 0;
                $existingLog->skor = $score;
                $existingLog->is_benar = $isCorrect ? 1 : 0;
                $existingLog->durasi_detik = $duration;
                $existingLog->save();
            } else {
                // Create new log
                PosttestLog::create([
                    'nisn' => $nisn,
                    'bank_soal_id' => $bankSoalId,
                    'pertanyaan_id' => $questionId,
                    'jawaban_pilihan' => $answer,
                    'jawaban_benar_salah' => $isCorrect ? 1 : 0,
                    'jawaban_esai' => null, // Can be filled with essay answer if needed
                    'skor' => $score,
                    'is_benar' => $isCorrect ? 1 : 0,
                    'durasi_detik' => $duration,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan',
                'is_correct' => $isCorrect,
                'score' => $score
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if the exam is within the time range
     */
    private function isWithinTimeRange($exam)
    {
        $now = now();

        // Check if exam type is posttest
        if (strtolower($exam->type_test) === 'posttest') {
            // Check if both start and end dates are set
            if ($exam->tanggal_mulai && $exam->tanggal_selesai) {
                $startTime = \Carbon\Carbon::parse($exam->tanggal_mulai);
                $endTime = \Carbon\Carbon::parse($exam->tanggal_selesai);

                // Check if current time is within the range
                return $now->between($startTime, $endTime);
            }

            // If only start date is set, check if current time is after start time
            if ($exam->tanggal_mulai) {
                $startTime = \Carbon\Carbon::parse($exam->tanggal_mulai);
                return $now->gte($startTime);
            }

            // If only end date is set, check if current time is before end time
            if ($exam->tanggal_selesai) {
                $endTime = \Carbon\Carbon::parse($exam->tanggal_selesai);
                return $now->lte($endTime);
            }

            // For non-posttest exams or if no dates are set, return true
            return true;
        }
    }

    /**
     * Submit nickname for waiting room
     */
    public function submitNickname($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Verify if this student has access to this exam
            $hasAccess = DB::table('bank_soals')
                ->leftJoin('bank_soal_rombel', 'bank_soal_rombel.bank_soal_id', '=', 'bank_soals.id')
                ->leftJoin('rombel', 'rombel.id', '=', 'bank_soal_rombel.rombel_id')
                ->leftJoin('rombel_detail', 'rombel_detail.rombel_id', '=', 'rombel.id')
                ->where('bank_soals.id', $bankSoalId)
                ->where('bank_soals.status', 'Aktif')
                ->where('rombel_detail.nisn', $nisn)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda tidak memiliki akses ke ujian ini'
                ], 403);
            }

            // Validate request
            $request->validate([
                'nickname' => 'required|string|min:3|max:15'
            ]);

            // Get pretest session
            $pretestSession = PretestSession::where('bank_soal_id', $bankSoalId)->first();

            if (!$pretestSession) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi pretest tidak ditemukan'
                ], 404);
            }

            // Get or create participant
            $participant = PretestPeserta::where('session_id', $pretestSession->id)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                // Create new participant
                $participant = PretestPeserta::create([
                    'session_id' => $pretestSession->id,
                    'bank_soal_id' => $bankSoalId,
                    'nisn' => $nisn,
                    'status' => 'waiting',
                ]);
            }

            // Note: The nickname functionality would require adding a nickname field to the PretestPeserta model
            // For now, we'll just update the status to active
            $participant->status = 'active';
            $participant->save();

            return response()->json([
                'success' => true,
                'message' => 'Nickname berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update remaining time for posttest
     */
    public function updateRemainingTime($bankSoalId, Request $request)
    {
        try {
            // Get current logged-in student NISN
            $nisn = Auth::guard('siswa')->user()->nisn;

            // Get participant
            $participant = PosttestPeserta::where('bank_soal_id', $bankSoalId)
                ->where('nisn', $nisn)
                ->first();

            if (!$participant) {
                return response()->json(['error' => 'Peserta tidak ditemukan'], 404);
            }

            // Check if exam is already finished
            if ($participant->status === 'finished') {
                return response()->json([
                    'success' => true,
                    'finished' => true,
                    'message' => 'Ujian sudah selesai'
                ]);
            }

            // Get remaining time from request if available
            $clientRemainingTime = $request->input('remaining_time');

            // Calculate remaining time
            $currentTime = now();
            $startTime = $participant->start_time;
            $durationMinutes = $participant->bankSoal->durasi_menit ?? 60; // Default 60 minutes if not set

            // Calculate elapsed time in seconds
            $elapsedSeconds = $currentTime->diffInSeconds($startTime);

            // Calculate remaining time in seconds
            $totalDurationSeconds = $durationMinutes * 60;
            $calculatedRemainingSeconds = max(0, $totalDurationSeconds - $elapsedSeconds);

            // Use client time if it's valid and close to server calculation (within 5 seconds difference)
            $remainingSeconds = $calculatedRemainingSeconds;
            if ($clientRemainingTime !== null && is_numeric($clientRemainingTime)) {
                $clientTime = intval($clientRemainingTime);
                $timeDifference = abs($clientTime - $calculatedRemainingSeconds);

                // Use client time if difference is less than 5 seconds
                if ($timeDifference < 5) {
                    $remainingSeconds = $clientTime;
                }
            }

            // Update remaining time
            $participant->sisa_detik = $remainingSeconds;

            // Check if time is up
            if ($remainingSeconds <= 0) {
                $participant->status = 'finished';
                $participant->end_time = now();
            }

            $participant->save();

            return response()->json([
                'success' => true,
                'finished' => $remainingSeconds <= 0,
                'remaining_time' => $remainingSeconds,
                'message' => $remainingSeconds <= 0 ? 'Waktu ujian habis' : 'Waktu diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Optional: Add method to show exam results
    public function showResult($bankSoalId, )
    {
        $examSession = PosttestPeserta::where('bank_soal_id', $bankSoalId)
            ->where('peserta_id', auth()->id())
            ->latest()
            ->first();

        // Check if user owns this exam session
        if ($examSession->peserta_id !== auth()->id()) {
            abort(403);
        }

        return view('participant.exams.result', [
            'examSession' => $examSession,
        ]);
    }

    // Optional: Add feedback method
    public function storeFeedback(Request $request, $bankSoalId)
    {
        $request->validate([
            'feedback' => 'nullable|string|max:1000',
        ]);

        $examSession = PosttestPeserta::where('bank_soal_id', $bankSoalId)
            ->where('peserta_id', auth()->id())
            ->latest()
            ->first();

        if ($examSession) {
            $examSession->update([
                'feedback' => $request->input('feedback'),
            ]);
        }

        return redirect()
            ->route('participant.exams.index')
            ->with('success', 'Terima kasih atas feedback Anda!');
    }
}

