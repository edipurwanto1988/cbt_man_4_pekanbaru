<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\PosttestHasil;
use App\Models\PretestSession;
use App\Models\PretestPeserta;
use App\Models\PretestSoalTimer;
use App\Models\PretestHasil;
use App\Models\PosttestPeserta;
use App\Models\PosttestCheatLog;
use App\Models\PertanyaanSoal;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class JadwalUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guruId = Auth::guard('guru')->user()->id_guru;
        $tahunAjarans = \App\Models\TahunAjaran::all();
            $bankSoals = BankSoal::with('pretestSession')->where('created_by', $guruId)
                ->orWhere('pengawas_id', $guruId)
                ->with(['tahunAjaran', 'mataPelajaran', 'creator', 'pengawas'])
                 ->orderBy('created_at', 'desc')
                ->get();
        // dd($bankSoals);
        return view('guru.jadwal_ujian.index', compact('bankSoals', 'tahunAjarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guruId = Auth::guard('guru')->user()->id_guru;
        $bankSoals = BankSoal::where('created_by', $guruId)
            ->orWhere('pengawas_id', $guruId)
            ->with(['mataPelajaran'])
            ->get();

        return view('guru.jadwal_ujian.create', compact('bankSoals'));
    }


public function unblockParticipant(Request $request)
{
    try {
        $nisn = $request->input('nisn');
        $bankSoalId = $request->input('bank_soal_id');
        
        $participant = PosttestPeserta::where('nisn', $nisn)
            ->where('bank_soal_id', $bankSoalId)
            ->firstOrFail();
            
        $participant->cheat_status = 'normal';
        $participant->cheat_reason = null;
        
        $participant->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil di-unblock'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal unblock peserta: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soals,id',
        ]);

        // Check if bank_soal is pretest type
        $bankSoal = BankSoal::findOrFail($request->bank_soal_id);
        if ($bankSoal->type_test !== 'pretest') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank soal ini bukan bertipe pretest',
                ], 400);
            }
            return redirect()->back()->with('error', 'Bank soal ini bukan bertipe pretest');
        }

        $guruId = Auth::guard('guru')->user()->id_guru;

        // Check if session already exists for this bank soal
        $existingSession = PretestSession::where('bank_soal_id', $request->bank_soal_id)
            ->whereIn('status', ['waiting', 'running'])
            ->first();
            
        if ($existingSession) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sesi pretest sudah ada',
                    'session_id' => $existingSession->id,
                    'redirect_url' => route('guru.jadwal_ujian.pretest.live', $existingSession->id),
                ]);
            }
            return redirect()->route('guru.jadwal_ujian.pretest.live', $existingSession->id)
                ->with('success', 'Sesi pretest sudah ada');
        }

        // Create pretest session
        $session = PretestSession::create([
            'bank_soal_id' => $request->bank_soal_id,
            'guru_id' => $guruId,
            'status' => 'waiting',
        ]);

        // Get all students from rombels associated with this bank soal
        $rombelIds = $bankSoal->rombels()->pluck('rombel.id');
        
        // Get students through rombel_detail table
        $siswaNisns = \App\Models\RombelDetail::whereIn('rombel_id', $rombelIds)->pluck('nisn');
        $siswas = Siswa::whereIn('nisn', $siswaNisns)->get();

        // Create pretest peserta for each student
        foreach ($siswas as $siswa) {
            PretestPeserta::create([
                'session_id' => $session->id,
                'bank_soal_id' => $request->bank_soal_id,
                'nisn' => $siswa->nisn,
                'status' => 'waiting',
            ]);
        }

        // Get all questions for this bank soal
        $questions = PertanyaanSoal::where('bank_soal_id', $request->bank_soal_id)
            ->orderBy('id', 'asc')
            ->get();

        // Create pretest soal timer for each question
        foreach ($questions as $index => $question) {
            PretestSoalTimer::create([
                'session_id' => $session->id,
                'pertanyaan_id' => $question->id,
                'urutan_soal' => $index + 1,
                'waktu_mulai' => now(),
                'waktu_berakhir' => now(),
                'status' => 'waiting',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesi pretest berhasil dibuat',
                'session_id' => $session->id,
            ]);
        }

        return redirect()->route('guru.jadwal_ujian.show', $session->id)
            ->with('success', 'Sesi pretest berhasil dibuat');
    }

    /**
     * Initialize posttest for a bank soal.
     */
    public function storePosttest(Request $request)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soals,id',
        ]);

        // Check if bank_soal is posttest type
        $bankSoal = BankSoal::findOrFail($request->bank_soal_id);
        if ($bankSoal->type_test !== 'posttest') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bank soal ini bukan bertipe posttest',
                ], 400);
            }
            return redirect()->back()->with('error', 'Bank soal ini bukan bertipe posttest');
        }

        // For posttest, we don't create participant records here
        // Participant records will be created when students start the posttest
        // This follows the principle that posttest is individual-based, not session-based

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Posttest berhasil diinisialisasi',
                'bank_soal_id' => $bankSoal->id,
            ]);
        }

        return redirect()->route('guru.jadwal_ujian.posttest.live', $bankSoal->id)
            ->with('success', 'Posttest berhasil diinisialisasi');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $session = PretestSession::with(['bankSoal', 'guru', 'pesertas.siswa', 'soalTimers.pertanyaanSoal'])
            ->findOrFail($id);

        return view('guru.jadwal_ujian.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $session = PretestSession::findOrFail($id);
        return view('guru.jadwal_ujian.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:waiting,running,finished',
        ]);

        $session = PretestSession::findOrFail($id);
        $oldStatus = $session->status;
        $session->status = $request->status;
        $session->save();

        // If status is changed to running and it was not running before, activate the first question
        if ($request->status === 'running' && $oldStatus !== 'running') {
            // Update all participants status to active
            PretestPeserta::where('session_id', $session->id)
                ->update(['status' => 'aktif']);

            // Activate the first question timer
            $firstQuestion = PretestSoalTimer::where('session_id', $session->id)
                ->orderBy('urutan_soal', 'asc')
                ->first();

            if ($firstQuestion) {
                $firstQuestion->status = 'running';
                $firstQuestion->waktu_mulai = now();
                $firstQuestion->waktu_berakhir = now()->addSeconds(30); // 30 seconds per question
                $firstQuestion->save();
            }
        }

        return redirect()->route('guru.jadwal_ujian.show', $session->id)
            ->with('success', 'Status sesi pretest berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $session = PretestSession::findOrFail($id);
        $session->delete();

        return redirect()->route('guru.jadwal_ujian.index')
            ->with('success', 'Sesi pretest berhasil dihapus');
    }

    /**
     * Show pretest page.
     */
    public function pretest()
    {
        $guruId = Auth::guard('guru')->user()->id_guru;
        
        // Get all bank soals for this guru with their pretest sessions
        $bankSoals = BankSoal::where('created_by', $guruId)
            ->orWhere('pengawas_id', $guruId)
            ->with(['mataPelajaran', 'pretestSession'])
            ->get();

        return view('guru.jadwal_ujian.pretest', compact('bankSoals'));
    }

    /**
     * Start pretest session.
     */
    public function startPretestSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:pretest_session,id',
        ]);

        $session = PretestSession::findOrFail($request->session_id);
        
        // Check if session is already running or finished
        if ($session->status !== 'waiting') {
            return response()->json([
                'success' => false, 
                'message' => 'Sesi pretest sudah dimulai atau selesai',
            ], 400);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            // Update session status and start time
            $session->status = 'running';
            $session->start_time = now();
            $session->save();

            // Update all participants status to active
            PretestPeserta::where('session_id', $session->id);

            // Activate the first question timer
            $firstQuestion = PretestSoalTimer::where('session_id', $session->id)
                ->orderBy('urutan_soal', 'asc')
                ->first();

            if ($firstQuestion) {
                $firstQuestion->status = 'running';
                $firstQuestion->waktu_mulai = now();
                $firstQuestion->waktu_berakhir = now()->addSeconds(30); // 30 seconds per question
                $firstQuestion->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sesi pretest berhasil dimulai',
                'redirect_url' => route('guru.jadwal_ujian.pretest.live', $session->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai sesi pretest: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Start posttest for a bank soal.
     */
    public function startPosttest(Request $request)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soals,id',
        ]);

        $bankSoal = BankSoal::findOrFail($request->bank_soal_id);
        
        // Check if bank_soal is posttest type
        if ($bankSoal->type_test !== 'posttest') {
            return response()->json([
                'success' => false,
                'message' => 'Bank soal ini bukan bertipe posttest',
            ], 400);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            // Update all participants status to active
            PosttestPeserta::where('bank_soal_id', $bankSoal->id)
                ->update(['start_time' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Posttest berhasil dimulai',
                'redirect_url' => route('guru.jadwal_ujian.posttest.live', $bankSoal->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai posttest: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show live posttest page.
     */
    public function pretestLive(string $id)
    {
        $session = PretestSession::with([
            'bankSoal',
            'guru',
            'pesertas.siswa',
            'soalTimers.pertanyaanSoal.jawabanSoals'
        ])->findOrFail($id);

        // Check if soalTimers exist, if not create them
        if ($session->soalTimers->count() === 0) {
            // Get all questions for this bank soal
            $questions = PertanyaanSoal::where('bank_soal_id', $session->bank_soal_id)
                ->orderBy('id', 'asc')
                ->get();

            // Create pretest soal timer for each question
            foreach ($questions as $index => $question) {
                PretestSoalTimer::create([
                    'session_id' => $session->id,
                    'pertanyaan_id' => $question->id,
                    'urutan_soal' => $index + 1,
                    'waktu_mulai' => now(),
                    'waktu_berakhir' => now(),
                    'status' => 'waiting',
                ]);
            }

            // Reload the session with the new soalTimers
            $session->load('soalTimers.pertanyaanSoal.jawabanSoals');
        }

        // Get current active question
        $currentQuestion = PretestSoalTimer::where('session_id', $id)
            ->where('status', 'running')
            ->with('pertanyaanSoal.jawabanSoals')
            ->first();

        // If session is running but no active question, activate the first one
        if ($session->status === 'running' && !$currentQuestion) {
            $firstQuestion = PretestSoalTimer::where('session_id', $id)
                ->orderBy('urutan_soal', 'asc')
                ->first();

            if ($firstQuestion) {
                $firstQuestion->status = 'running';
                $firstQuestion->waktu_mulai = now();
                $firstQuestion->waktu_berakhir = now()->addSeconds(30); // 30 seconds per question
                $firstQuestion->save();

                // Set as current question
                $currentQuestion = $firstQuestion->load('pertanyaanSoal.jawabanSoals');
            }
        }

        // Get next question
        $nextQuestion = null;
        if ($currentQuestion) {
            $nextQuestion = PretestSoalTimer::where('session_id', $id)
                ->where('urutan_soal', $currentQuestion->urutan_soal + 1)
                ->with('pertanyaanSoal')
                ->first();
        }

        // Get participants with their scores
        $participants = PretestPeserta::where('session_id', $id)
            ->with(['siswa', 'pretestHasil'])
            ->get();

        return view('guru.jadwal_ujian.pretest-live', compact('session', 'currentQuestion', 'nextQuestion', 'participants'));
    }

    /**
     * Show live posttest page.
     */
 public function posttestLive(string $id)
{
    $bankSoal = BankSoal::with([
        'mataPelajaran',
        'tahunAjaran',
        'creator'
    ])->findOrFail($id);
    
    // Get participants with their data and results
    $participants = PosttestPeserta::select(
        'posttest_peserta.id AS peserta_id',
        'posttest_peserta.nisn',
        'siswa.nama_siswa',
        'posttest_peserta.status',
        'posttest_peserta.sisa_detik',
        'posttest_peserta.cheat_status',
        'posttest_peserta.cheat_reason'
    )
    ->leftJoin('siswa', 'siswa.nisn', '=', 'posttest_peserta.nisn')
    ->leftJoin('posttest_cheat_log', 'posttest_cheat_log.peserta_id', '=', 'posttest_peserta.id')
    ->where('posttest_peserta.bank_soal_id', $id)
    ->groupBy(
        'posttest_peserta.id',
        'posttest_peserta.nisn',
        'siswa.nama_siswa',
        'posttest_peserta.status',
        'posttest_peserta.sisa_detik',
        'posttest_peserta.cheat_status',
        'posttest_peserta.cheat_reason'
    )
    ->get()
    ->map(function ($participant) use ($id) {
        // Get the last cheat timestamp
        $lastCheat = PosttestCheatLog::where('peserta_id', $participant->peserta_id)
            ->max('timestamp');
        $participant->last_cheat = $lastCheat;
        
        // Get hasil posttest
        $hasil = PosttestHasil::where('bank_soal_id', $id)
            ->where('nisn', $participant->nisn)
            ->first();
        $participant->hasil = $hasil;
        
        return $participant;
    });
    
    return view('guru.jadwal_ujian.posttest-live', compact('bankSoal', 'participants'));
}

    /**
     * Update pretest time (move to next question).
     */
    public function updatePretestTime(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:pretest_session,id',
            'current_question_id' => 'required|exists:pretest_soal_timer,id',
        ]);

        $session = PretestSession::findOrFail($request->session_id);
        $currentQuestion = PretestSoalTimer::findOrFail($request->current_question_id);

        $bankSoal = BankSoal::findOrFail($session->bank_soal_id);

        // Start transaction
        DB::beginTransaction();
        try {
            // Mark current question as finished
            $currentQuestion->status = 'finished';
            $currentQuestion->waktu_berakhir = now();
            $currentQuestion->save();

            // Find next question
            $nextQuestion = PretestSoalTimer::where('session_id', $session->id)
                ->where('urutan_soal', $currentQuestion->urutan_soal + 1)
                ->first();

            if ($nextQuestion) {
                // Activate next question
                $nextQuestion->status = 'running';
                $nextQuestion->waktu_mulai = now();
                $nextQuestion->waktu_berakhir = now()->addSeconds(30); // 30 seconds per question
                $nextQuestion->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Berikutnya adalah soal nomor ' . $nextQuestion->urutan_soal,
                    'redirect_url' => route('guru.jadwal_ujian.pretest.live', $session->id),
                ]);
            } else {
                // No more questions, finish the session
                $session->status = 'finished';
                $session->end_time = now();
                $bankSoal->tanggal_selesai = now();
                $bankSoal->status = 'selesai';
                $bankSoal->save();
                $session->save();

                // Update all participants status to finished
                PretestPeserta::where('session_id', $session->id)
                    ->update(['status' => 'finished']);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Semua soal telah selesai',
                    'redirect_url' => route('guru.jadwal_ujian.pretest.results', $session->id),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui waktu pretest: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle timeout for current question.
     */
    public function handleTimeout(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:pretest_session,id',
            'current_question_id' => 'required|exists:pretest_soal_timer,id',
        ]);

        // This is similar to updatePretestTime but triggered by timeout
        return $this->updatePretestTime($request);
    }

    /**
     * Show pretest results.
     */
    public function showResults(string $id)
    {
        $session = PretestSession::with([
            'bankSoal', 
            'guru', 
            'pesertas.siswa', 
            'hasil.siswa'
        ])->findOrFail($id);

        
        // Calculate rankings
        $rankings = PretestHasil::where('session_id', $id)
            ->with('siswa')
            ->orderBy('total_poin', 'desc')
            ->orderBy('total_waktu_respon', 'asc')
            ->get();

        // Update rankings
        foreach ($rankings as $index => $ranking) {
            $ranking->peringkat = $index + 1;
            $ranking->save();
        }

        return view('guru.jadwal_ujian.pretest-results', compact('session', 'rankings'));
    }

    /**
     * Show posttest page.
     */
    public function posttest()
    {
        $guruId = Auth::guard('guru')->user()->id_guru;
        $bankSoals = BankSoal::where('created_by', $guruId)
            ->orWhere('pengawas_id', $guruId)
            ->with(['tahunAjaran', 'mataPelajaran'])
            ->get();

        return view('guru.jadwal_ujian.posttest', compact('bankSoals'));
    }

    /**
     * Get pretest participants for AJAX request.
     */
    public function getPretestParticipants($sessionId)
    {
        $session = PretestSession::findOrFail($sessionId);
        
        // Check if the current guru owns this session
        $guruId = Auth::guard('guru')->user()->id_guru;
        if ($session->guru_id != $guruId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $participants = PretestPeserta::where('session_id', $sessionId)
            ->with('siswa')
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'nisn' => $participant->nisn,
                    'nama_siswa' => $participant->siswa->nama_siswa ?? 'Unknown',
                    'avatar_url' => $participant->siswa->foto ? asset('storage/uploads/siswa/' . $participant->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown') . '&background=6366f1&color=ffffff&size=48',
                    'status' => $participant->status,
                    'skor_total' => 0, // Will be updated during the test
                ];
            });

        return response()->json([
            'success' => true,
            'participants' => $participants,
            'total_participants' => $participants->count(),
        ]);
    }

    /**
     * Start exam for a bank soal.
     */
    public function startExam(Request $request, $bankSoalId)
    {
        try {
            
            Log::info('Starting pretest for bank soal ID: ' . $bankSoalId);
            
            $bankSoal = BankSoal::findOrFail($bankSoalId);
            Log::info('Bank soal found: ' . $bankSoal->nama_bank);
            
            // Check if bank_soal is pretest type
            if ($bankSoal->type_test !== 'pretest') {
                Log::error('Bank soal is not pretest type: ' . $bankSoal->type_test);
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bank soal ini bukan bertipe pretest',
                    ], 400);
                }
                return redirect()->back()->with('error', 'Bank soal ini bukan bertipe pretest');
            }

            $guruId = Auth::guard('guru')->user()->id_guru;
            Log::info('Guru ID: ' . $guruId);

            // Check if session already exists for this bank soal
            $existingSession = PretestSession::where('bank_soal_id', $bankSoalId)
                ->first();
            
            if ($existingSession) {

                Log::info('Existing session found: ' . $existingSession->id);
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Sesi pretest sudah ada',
                        'session_id' => $existingSession->id,
                        'redirect_url' => route('guru.jadwal_ujian.pretest.live', $existingSession->id),
                    ]);
                }
                return redirect()->route('guru.jadwal_ujian.pretest.live', $existingSession->id)
                    ->with('success', 'Sesi pretest sudah ada');
            }

            // Create pretest session
            $session = PretestSession::create([
                'bank_soal_id' => $bankSoalId,
                'guru_id' => $guruId,
                'status' => 'waiting',
            ]);
            Log::info('Session created with ID: ' . $session->id);

            // Get all students from rombels associated with this bank soal
            $rombelIds = $bankSoal->rombels()->pluck('rombel.id');
            Log::info('Rombel IDs: ' . json_encode($rombelIds));
            
            // Get students through rombel_detail table
            $siswaNisns = \App\Models\RombelDetail::whereIn('rombel_id', $rombelIds)->pluck('nisn');
            $siswas = Siswa::whereIn('nisn', $siswaNisns)->get();
            Log::info('Found ' . $siswas->count() . ' students');

            // Create pretest peserta for each student
            foreach ($siswas as $siswa) {
                PretestPeserta::create([
                    'session_id' => $session->id,
                    'bank_soal_id' => $bankSoalId,
                    'nisn' => $siswa->nisn,
                    'status' => 'waiting',
                ]);
            }

            // Get all questions for this bank soal
            $questions = PertanyaanSoal::where('bank_soal_id', $bankSoalId)
                ->orderBy('id', 'asc')
                ->get();
            Log::info('Found ' . $questions->count() . ' questions');

            // Create pretest soal timer for each question
            foreach ($questions as $index => $question) {
                PretestSoalTimer::create([
                    'session_id' => $session->id,
                    'pertanyaan_id' => $question->id,
                    'urutan_soal' => $index + 1,
                    'waktu_mulai' => now(),
                    'waktu_berakhir' => now(),
                    'status' => 'waiting',
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sesi pretest berhasil dibuat',
                    'session_id' => $session->id,
                    'redirect_url' => route('guru.jadwal_ujian.pretest.live', $session->id),
                ]);
            }

            return redirect()->route('guru.jadwal_ujian.pretest.live', $session->id)
                ->with('success', 'Sesi pretest berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Error in startExam: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function posttestHasil($hasilId)
    {
        $hasil = PosttestHasil::findOrFail($hasilId);

        $siswa = Siswa::where('nisn', $hasil->nisn)->first();

        return view('guru.posttest.show', [
            'hasil' => $hasil,
            'siswa' => $siswa
        ]);
    }


    
    /**
     * Start countdown for exam.
     */
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

    /**
     * Update posttest status.
     */
    public function updatePosttestStatus(Request $request)
    {
        $request->validate([
            'bank_soal_id' => 'required|exists:bank_soals,id',
            'action' => 'required|in:start,finish',
        ]);

        $bankSoal = BankSoal::findOrFail($request->bank_soal_id);
        
        // Check if bank_soal is posttest type
        if ($bankSoal->type_test !== 'posttest') {
            return response()->json([
                'success' => false,
                'message' => 'Bank soal ini bukan bertipe posttest',
            ], 400);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            if ($request->action === 'start') {
                // Update all participants status to active
                PosttestPeserta::where('bank_soal_id', $bankSoal->id)
                    ->where('status', 'waiting')
                    ->update(['status' => '"Aktif"', 'start_time' => now()]);
                
                $message = 'Posttest berhasil dimulai';
            } else if ($request->action === 'finish') {
                // Update all participants status to finished
                PosttestPeserta::where('bank_soal_id', $bankSoal->id)
                    ->where('status', '!=', 'finished')
                    ->update(['status' => 'finished', 'end_time' => now()]);
                
                $message = 'Posttest berhasil diselesaikan';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('guru.jadwal_ujian.posttest.live', $bankSoal->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status posttest: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get posttest participants for AJAX request.
     */
    public function getPosttestParticipantsByBankSoal($bankSoalId)
    {
        $bankSoal = BankSoal::findOrFail($bankSoalId);
        
        // Check if the current guru owns this bank soal
        $guruId = Auth::guard('guru')->user()->id_guru;
        if ($bankSoal->created_by != $guruId && $bankSoal->pengawas_id != $guruId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        $participants = PosttestPeserta::where('bank_soal_id', $bankSoalId)
            ->with('siswa')
            ->get()
            ->map(function ($participant) {
                return [
                    'id' => $participant->id,
                    'nisn' => $participant->nisn,
                    'nama_siswa' => $participant->siswa->nama_siswa ?? 'Unknown',
                    'avatar_url' => $participant->siswa->foto ? asset('storage/uploads/siswa/' . $participant->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown') . '&background=6366f1&color=ffffff&size=48',
                    'status' => $participant->status,
                    'skor_total' => 0, // Will be updated during the test
                ];
            });

        return response()->json([
            'success' => true,
            'participants' => $participants,
            'total_participants' => $participants->count(),
        ]);
    }

    /**
     * Finish posttest.
     */

    public function finishPretest(Request $request, $id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        
        // Check if bank_soal is pretest type
        if ($bankSoal->type_test !== 'pretest') {
            return response()->json([
                'success' => false,
                'message' => 'Bank soal ini bukan bertipe pretest',
            ], 400);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            // Update all participants status to finished
            pretestPeserta::where('bank_soal_id', $bankSoal->id)
                ->update(['status' => 'finished', 'end_time' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'pretest berhasil diselesaikan',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan pretest: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function finishPosttest(Request $request, $id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        
        // Check if bank_soal is posttest type
        if ($bankSoal->type_test !== 'posttest') {
            return response()->json([
                'success' => false,
                'message' => 'Bank soal ini bukan bertipe posttest',
            ], 400);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            // Update all participants status to finished
            PosttestPeserta::where('bank_soal_id', $bankSoal->id)
                ->update(['status' => 'finished', 'end_time' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Posttest berhasil diselesaikan',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan posttest: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check pretest status for a specific bank soal
     *
     * @param int $bankSoalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPretestStatus($bankSoalId)
    {
        try {
            // Check if there's a pretest session for this bank soal
            $pretestSession = PretestSession::where('bank_soal_id', $bankSoalId)
                ->whereIn('status', ['waiting', 'running', 'finished'])
                ->first();
            
            if ($pretestSession) {
                return response()->json([
                    'has_session' => true,
                    'session_id' => $pretestSession->id,
                    'status' => $pretestSession->status
                ]);
            } else {
                return response()->json([
                    'has_session' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}