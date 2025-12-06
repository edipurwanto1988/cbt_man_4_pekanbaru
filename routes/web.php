<?php


use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\BankSoalController;
use App\Http\Controllers\Participant\AuthController as ParticipantAuthController;
use App\Http\Controllers\Participant\ExamController;
use App\Http\Controllers\Guru\AuthController as GuruAuthController;
use App\Http\Controllers\Guru\BankSoalController as GuruBankSoalController;
use App\Http\Controllers\Guru\PertanyaanSoalController;
use App\Http\Controllers\Guru\RombelController as GuruRombelController;
use App\Http\Controllers\Guru\JadwalUjianController;


// Temporary route to create admin user
Route::get('/create-admin', function() {
    $email = 'admin@edipurwanto.com';
    $password = '12345678';
    $name = 'Edi Purwanto';
    
    // Check if admin already exists
    $existingAdmin = \App\Models\Admin::where('email', $email)->first();
    
    if ($existingAdmin) {
        $existingAdmin->password = \Illuminate\Support\Facades\Hash::make($password);
        $existingAdmin->save();
        return "Admin password updated successfully! Email: $email, Password: $password";
    } else {
        // Create new admin
        $admin = \App\Models\Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => 'admin',
        ]);
        
        return "Admin user created successfully! Email: $email, Password: $password";
    }
});

// Halaman utama untuk login siswa
Route::get('/', [ParticipantAuthController::class, 'showLoginForm'])->name('participant.login');
Route::get('/login', [ParticipantAuthController::class, 'showLoginForm'])->name('participant.login.get');
Route::post('/login', [ParticipantAuthController::class, 'login'])->name('participant.login.post');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', function () {
            $siswaCount = \App\Models\Siswa::count();
            $pertanyaanCount = \App\Models\PertanyaanSoal::count();
            $aktifCount = \App\Models\BankSoal::where('status', 'Aktif')->count();
            return view('admin.dashboard', compact('siswaCount', 'pertanyaanCount', 'aktifCount'));
        })->name('dashboard');
        
        // Guru management routes (for all admin roles)
        Route::get('guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
        Route::get('guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
        
        // Admin management routes (only for admin role)
        Route::middleware(['admin.role:admin'])->group(function () {
            Route::resource('admins', AdminController::class);
            
            // Mata Pelajaran management routes (only for admin role)
            Route::resource('mata_pelajaran', MataPelajaranController::class);
            
            // Tahun Ajaran management routes (only for admin role)
            Route::resource('tahun_ajaran', TahunAjaranController::class);
            
            // Guru import routes (only for admin role)
            Route::get('guru/import', [GuruController::class, 'import'])->name('guru.import');
            Route::get('guru/importfix', [GuruController::class, 'importfix'])->name('guru.importfix');
            Route::post('guru/preview', [GuruController::class, 'preview'])->name('guru.preview');
            Route::post('guru/process', [GuruController::class, 'process'])->name('guru.process');
            Route::get('guru/template', [GuruController::class, 'template'])->name('guru.template');
            
            // Siswa management routes (only for admin role)
            Route::get('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
            Route::post('siswa/preview', [SiswaController::class, 'preview'])->name('siswa.preview');
            Route::post('siswa/process', [SiswaController::class, 'process'])->name('siswa.process');
            Route::get('siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
            Route::resource('siswa', SiswaController::class);
            
            // Settings management routes (only for admin role)
            Route::resource('settings', SettingsController::class);
            Route::post('settings/update-multiple', [SettingsController::class, 'updateMultiple'])->name('settings.updateMultiple');
            Route::post('settings/upload-file', [SettingsController::class, 'uploadFile'])->name('settings.uploadFile');
            Route::post('settings/remove-file', [SettingsController::class, 'removeFile'])->name('settings.removeFile');
            
            // Bank Soal management routes (only for admin role)
            Route::resource('bank_soals', BankSoalController::class);
        });
        
        // Rombel management routes (for both admin and guru roles)
        Route::resource('rombel', RombelController::class);
        Route::get('rombel/import', [RombelController::class, 'import'])->name('rombel.import');
        Route::post('rombel/preview', [RombelController::class, 'preview'])->name('rombel.preview');
        Route::post('rombel/process', [RombelController::class, 'process'])->name('rombel.process');
        Route::get('rombel/template', [RombelController::class, 'template'])->name('rombel.template');
        Route::get('rombel/{rombel}/siswa', [RombelController::class, 'siswa'])->name('rombel.siswa');
        Route::post('rombel/{rombel}/siswa', [RombelController::class, 'siswaStore'])->name('rombel.siswa.store');
        Route::get('rombel/{rombel}/siswa/search', [RombelController::class, 'searchStudent'])->name('rombel.siswa.search');
        Route::get('rombel/{rombel}/siswa/import', [RombelController::class, 'siswaImport'])->name('rombel.siswa.import');
        Route::post('rombel/{rombel}/siswa/preview', [RombelController::class, 'siswaPreview'])->name('rombel.siswa.preview');
        Route::post('rombel/{rombel}/siswa/process', [RombelController::class, 'siswaProcess'])->name('rombel.siswa.process');
        Route::get('rombel/{rombel}/siswa/template', [RombelController::class, 'siswaTemplate'])->name('rombel.siswa.template');
        Route::delete('rombel/{rombel}/siswa/{nisn}', [RombelController::class, 'siswaRemove'])->name('rombel.siswa.remove');
        Route::get('rombel/{rombel}/mapel', [RombelController::class, 'mapel'])->name('rombel.mapel');
        Route::get('rombel/{rombel}/mapel/{mapel}/edit', [RombelController::class, 'mapelEdit'])->name('rombel.mapel.edit');
        Route::put('rombel/{rombel}/mapel/{mapel}', [RombelController::class, 'mapelUpdate'])->name('rombel.mapel.update');
        Route::delete('rombel/{rombel}/mapel/{mapel}', [RombelController::class, 'mapelRemove'])->name('rombel.mapel.remove');
        
        // Test route for debugging
        Route::get('siswa/test', function() {
            return response()->json([
                'message' => 'Siswa routes are working',
                'routes' => [
                    'import' => route('admin.siswa.import'),
                    'preview' => route('admin.siswa.preview'),
                    'process' => route('admin.siswa.process'),
                    'template' => route('admin.siswa.template'),
                    'index' => route('admin.siswa.index')
                ]
            ]);
        })->name('admin.siswa.test');
        
        // Test route for debugging guru
        Route::get('guru/test', function() {
            return response()->json([
                'message' => 'Guru routes are working',
                'routes' => [
                    'import' => route('admin.guru.import'),
                    'preview' => route('admin.guru.preview'),
                    'process' => route('admin.guru.process'),
                    'template' => route('admin.guru.template'),
                    'index' => route('admin.guru.index')
                ]
            ]);
        })->name('admin.guru.test');
        
        // Simple test route for import
        Route::get('guru/import-test', function() {
            return 'Import route is working!';
        })->name('admin.guru.import-test');
    });
});

// Participant routes
Route::prefix('participant')->name('participant.')->group(function () {
    Route::get('/login', [ParticipantAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [ParticipantAuthController::class, 'login'])->name('login.post');
    
    Route::middleware('auth:siswa')->group(function () {
        Route::get('/', function () {
            return view('participant.dashboard');
        })->name('dashboard');
        
        Route::post('/logout', [ParticipantAuthController::class, 'logout'])->name('logout');
        
        // Exam routes
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/start/{bankSoalId}', [ExamController::class, 'start'])->name('exams.start');
        Route::get('/exams/take/{bankSoalId}', [ExamController::class, 'take'])->name('exams.take');
        Route::get('/exadms/take/{bankSoalId}', [ExamController::class, 'take'])->name('exams.calculate');
        Route::get('/exams/start-countdown/{bankSoalId}', [ExamController::class, 'startCountdown'])->name('exams.start-countdown');
        Route::get('/exams/waiting-room/{bankSoalId}/participants', [ExamController::class, 'getWaitingRoomParticipants'])->name('exams.waiting-room.participants');
        Route::get('/exams/waiting-room/{bankSoalId}/status', [ExamController::class, 'checkPretestSessionStatus'])->name('exams.waiting-room.status');
        Route::post('/exams/waiting-room/{bankSoalId}/nickname', [ExamController::class, 'submitNickname'])->name('exams.waiting-room.nickname');
        Route::get('/exams/take-live/{sessionId}', [ExamController::class, 'takeLive'])->name('exams.take-live');
        Route::get('/exams/take-live/{sessionId}/question', [ExamController::class, 'getCurrentLiveQuestion'])->name('exams.take-live.question');
        Route::get('/exams/results/{sessionId}', [ExamController::class, 'resultPage'])->name('exams.result');
        Route::post('/exams/submit-answer/{sessionId}', [ExamController::class, 'submitAnswer'])->name('exams.submit-answer');
        
        Route::post('/exams/submit-posttest/{bankSoalId}', [ExamController::class, 'submitPosttest'])->name('exams.submitPosttest');
        Route::post('/exams/save-duration/{bankSoalId}', [ExamController::class, 'saveDuration'])->name('exams.saveDuration');
        Route::post('/exams/auto-save-answer/{bankSoalId}', [ExamController::class, 'autoSaveAnswer'])->name('exams.autoSaveAnswer');
        Route::post('/exams/cheat/{bankSoalId}', [ExamController::class, 'cheat'])->name('exams.cheat');
        Route::post('/exams/update-remaining-time/{bankSoalId}', [ExamController::class, 'updateRemainingTime'])->name('exams.updateRemainingTime');
        Route::get('/history', [ExamController::class, 'history'])
        ->name('history.index');


        // End page and results
        Route::get('/exams/{bankSoalId}', [ExamController::class, 'showResult'])->name('exams.result');
    
    });
});

// Guru routes
Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('/login', [GuruAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [GuruAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [GuruAuthController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::middleware('auth:guru')->group(function () {
        Route::get('/', function () {
            return view('guru.dashboard');
        })->name('dashboard');
        
        // Bank Soal management routes
        Route::resource('bank_soal', GuruBankSoalController::class);
        
        // Import routes
        Route::get('bank_soal/{bankSoalId}/pertanyaan_soal/template', [PertanyaanSoalController::class, 'template'])->name('pertanyaan_soal.template');
        Route::post('bank_soal/{bankSoalId}/pertanyaan_soal/import', [PertanyaanSoalController::class, 'import'])->name('pertanyaan_soal.import');
        
        // Pertanyaan Soal management routes
        Route::get('bank_soal/{bankSoalId}/pertanyaan_soal', [PertanyaanSoalController::class, 'index'])->name('pertanyaan_soal.index');
        Route::get('bank_soal/{bankSoalId}/pertanyaan_soal/create', [PertanyaanSoalController::class, 'create'])->name('pertanyaan_soal.create');
        Route::post('bank_soal/{bankSoalId}/pertanyaan_soal', [PertanyaanSoalController::class, 'store'])->name('pertanyaan_soal.store');
        Route::get('bank_soal/{bankSoalId}/pertanyaan_soal/{pertanyaanSoal}', [PertanyaanSoalController::class, 'show'])->name('pertanyaan_soal.show')->where('pertanyaanSoal', '[0-9]+');
        Route::get('bank_soal/{bankSoalId}/pertanyaan_soal/{pertanyaanSoal}/edit', [PertanyaanSoalController::class, 'edit'])->name('pertanyaan_soal.edit')->where('pertanyaanSoal', '[0-9]+');
        Route::put('bank_soal/{bankSoalId}/pertanyaan_soal/{pertanyaanSoal}', [PertanyaanSoalController::class, 'update'])->name('pertanyaan_soal.update')->where('pertanyaanSoal', '[0-9]+');
        Route::delete('bank_soal/{bankSoalId}/pertanyaan_soal/{pertanyaanSoal}', [PertanyaanSoalController::class, 'destroy'])->name('pertanyaan_soal.destroy')->where('pertanyaanSoal', '[0-9]+');
        Route::delete('bank_soal/{bankSoalId}/pertanyaan_soal', [PertanyaanSoalController::class, 'destroyMultiple'])->name('pertanyaan_soal.destroy_multiple');
        
        // Rombel management routes
        Route::resource('rombel', GuruRombelController::class);
        Route::get('rombel/{rombel}/siswa', [GuruRombelController::class, 'siswa'])->name('rombel.siswa');
        Route::get('rombel/{rombel}/siswa/import', [GuruRombelController::class, 'siswaImport'])->name('rombel.siswa.import');
        Route::post('rombel/{rombel}/siswa/preview', [GuruRombelController::class, 'siswaPreview'])->name('rombel.siswa.preview');
        Route::post('rombel/{rombel}/siswa/process', [GuruRombelController::class, 'siswaProcess'])->name('rombel.siswa.process');
        Route::get('rombel/{rombel}/siswa/template', [GuruRombelController::class, 'siswaTemplate'])->name('rombel.siswa.template');
        Route::delete('rombel/{rombel}/siswa/{nisn}', [GuruRombelController::class, 'siswaRemove'])->name('rombel.siswa.remove');
        Route::get('rombel/{rombel}/mapel', [GuruRombelController::class, 'mapel'])->name('rombel.mapel');
        Route::get('rombel/{rombel}/mapel/{mapel}/edit', [GuruRombelController::class, 'mapelEdit'])->name('rombel.mapel.edit');
        Route::put('rombel/{rombel}/mapel/{mapel}', [GuruRombelController::class, 'mapelUpdate'])->name('rombel.mapel.update');
        Route::delete('rombel/{rombel}/mapel/{mapel}', [GuruRombelController::class, 'mapelRemove'])->name('rombel.mapel.remove');
        
        // Jadwal Ujian routes
        Route::get('jadwal_ujian', [JadwalUjianController::class, 'index'])->name('jadwal_ujian.index');
        Route::get('jadwal_ujian/create', [JadwalUjianController::class, 'create'])->name('jadwal_ujian.create');
        Route::post('jadwal_ujian', [JadwalUjianController::class, 'store'])->name('jadwal_ujian.store');
        Route::get('jadwal_ujian/{id}', [JadwalUjianController::class, 'show'])->name('jadwal_ujian.show');
        Route::get('jadwal_ujian/{id}/edit', [JadwalUjianController::class, 'edit'])->name('jadwal_ujian.edit');
        Route::put('jadwal_ujian/{id}', [JadwalUjianController::class, 'update'])->name('jadwal_ujian.update');
        Route::delete('jadwal_ujian/{id}', [JadwalUjianController::class, 'destroy'])->name('jadwal_ujian.destroy');
        
        // Pretest routes
        Route::get('jadwal_ujian/pretest', [JadwalUjianController::class, 'pretest'])->name('jadwal_ujian.pretest');
        Route::post('jadwal_ujian/start-pretest-session', [JadwalUjianController::class, 'startPretestSession'])->name('jadwal_ujian.startPretestSession');
        Route::get('jadwal_ujian/pretest-live/{id}', [JadwalUjianController::class, 'pretestLive'])->name('jadwal_ujian.pretest.live');
        Route::post('jadwal_ujian/update-pretest-time', [JadwalUjianController::class, 'updatePretestTime'])->name('jadwal_ujian.updatePretestTime');
        Route::post('jadwal_ujian/handle-timeout', [JadwalUjianController::class, 'handleTimeout'])->name('jadwal_ujian.handleTimeout');
        Route::get('jadwal_ujian/pretest-results/{id}', [JadwalUjianController::class, 'showResults'])->name('jadwal_ujian.pretest.results');
        Route::get('jadwal_ujian/pretest/{sessionId}/participants', [JadwalUjianController::class, 'getPretestParticipants'])->name('jadwal_ujian.pretest.participants');
        
        // Teacher control routes for waiting room
        Route::post('jadwal_ujian/start/{bankSoalId}', [JadwalUjianController::class, 'startExam'])->name('jadwal_ujian.start');
        Route::post('jadwal_ujian/start-countdown/{bankSoalId}', [JadwalUjianController::class, 'startCountdown'])->name('jadwal_ujian.start-countdown');
        Route::get('jadwal_ujian/check-pretest-status/{bankSoalId}', [JadwalUjianController::class, 'checkPretestStatus'])->name('guru.jadwal_ujian.checkPretestStatus');
        
        // Posttest routes
        Route::get('jadwal_ujian/posttest', [JadwalUjianController::class, 'posttest'])->name('jadwal_ujian.posttest');
        Route::post('jadwal_ujian/store-posttest', [JadwalUjianController::class, 'storePosttest'])->name('jadwal_ujian.storePosttest');
        Route::post('jadwal_ujian/start-posttest-session', [JadwalUjianController::class, 'startPosttestSession'])->name('jadwal_ujian.startPosttestSession');
        Route::post('jadwal_ujian/start-posttest', [JadwalUjianController::class, 'startPosttest'])->name('jadwal_ujian.startPosttest');
        Route::get('jadwal_ujian/posttest-live/{id}', [JadwalUjianController::class, 'posttestLive'])->name('jadwal_ujian.posttest.live');
        Route::post('jadwal_ujian/update-posttest-time', [JadwalUjianController::class, 'updatePosttestTime'])->name('jadwal_ujian.updatePosttestTime');
        Route::get('jadwal_ujian/posttest/{sessionId}/participants', [JadwalUjianController::class, 'getPosttestParticipants'])->name('jadwal_ujian.posttest.participants');
        Route::post('jadwal_ujian/posttest/{id}/finish', [JadwalUjianController::class, 'finishPosttest'])->name('jadwal_ujian.posttest.finish');
        Route::post('jadwal_ujian/pretest/{id}/finish', [JadwalUjianController::class, 'finishPretest'])->name('jadwal_ujian.pretest.finish');
    });
});
