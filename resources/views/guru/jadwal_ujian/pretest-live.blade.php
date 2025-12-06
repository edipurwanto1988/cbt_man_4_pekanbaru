@extends('layouts.guru')

@section('title', 'Live Pretest - ' . $session->bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Live Pretest: {{ $session->bankSoal->nama_bank }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Sesi: {{ $session->id }} | Status: <span class="font-semibold {{ $session->status == 'waiting' ? 'text-yellow-500' : ($session->status == 'running' ? 'text-green-500' : 'text-red-500') }}">{{ ucfirst($session->status) }}</span> | Total Soal: {{ \App\Models\PertanyaanSoal::where('bank_soal_id', $session->bankSoal->id)->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Query: SELECT COUNT(*) AS total_soal FROM pertanyaan_soals WHERE bank_soal_id = '{{ $session->bankSoal->id }}'</p>
        </div>
        <a href="{{ route('guru.jadwal_ujian.pretest') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="ri-arrow-left-line mr-2"></i>Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Question Section -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Soal {{ $currentQuestion ? $currentQuestion->urutan_soal : '-' }}</h2>
                        <div class="flex items-center gap-2">
                            <i class="ri-timer-line"></i>
                            <span id="countdown" class="font-mono text-lg">30</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($currentQuestion)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                {{ strip_tags($currentQuestion->pertanyaanSoal->pertanyaan) }}
                            </h3>
                            
                            @if($currentQuestion->pertanyaanSoal->gambar_soal)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/uploads/soal/' . $currentQuestion->pertanyaanSoal->gambar_soal) }}"
                                         alt="Gambar Soal"
                                         class="max-w-full h-auto rounded-lg">
                                </div>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($currentQuestion->pertanyaanSoal->jawabanSoals as $jawaban)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border-2 border-transparent hover:border-blue-500 transition cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                                            {{ $loop->index + 1 }}
                                        </div>
                                        <span class="text-gray-800 dark:text-white">{{ strip_tags($jawaban->isi_jawaban) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="ri-question-line text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400 mb-2">Tidak ada soal aktif</h3>
                            <p class="text-gray-400 dark:text-gray-500">silahkan siap siap untuk mengikuti pretest</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Participants Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4 text-white">
                    <h2 class="text-xl font-bold">Peserta ({{ $participants->count() }})</h2>
                </div>
                
                <div class="p-4">
                    <div id="participantsList" class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($participants as $participant)
                            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <img src="{{ 'https://api.dicebear.com/7.x/big-ears/svg?seed=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown')  }}" 
                                     alt="{{ $participant->siswa->nama_siswa }}"
                                     class="w-10 h-10 rounded-full border-2 border-white shadow-md animate-bounce"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($participant->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=48'">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $participant->siswa->nama_siswa }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $participant->status }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ $participant->pretestHasil->total_poin ?? 0 }} poin
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Control Buttons -->
            <div class="mt-4 space-y-3">
                @if($session->status == 'running')
                      @if($nextQuestion)
        {{-- Masih ada soal berikutnya --}}
        <button onclick="nextQuestion()" 
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-4 px-4 rounded-lg font-medium transition">
            <i class="ri-skip-forward-line mr-2"></i> Soal Berikutnya
        </button>
    @else
        {{-- Tidak ada soal berikutnya, tampilkan tombol AKHIRI --}}
        <button onclick="endPretest()" 
            class="w-full bg-red-500 hover:bg-red-600 text-white py-4 px-4 rounded-lg font-medium transition">
            <i class="ri-stop-circle-line mr-2"></i> Akhiri Pretest
        </button>
    @endif
                @endif
                
                @if($session->status == 'waiting')
                    <button onclick="startPretestSession()" class="w-full bg-green-500 hover:bg-green-600 text-white py-4 px-4 rounded-lg font-medium transition">
                        <i class="ri-play-line mr-2"></i> Mulai Pretest
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let countdownInterval;
    let timeLeft = parseInt({{ $session->bankSoal->max_time ?? 3600 }});
    console.log(timeLeft) // 3600
    document.addEventListener('DOMContentLoaded', function() {
        // Start countdown if there's an active question
        @if($currentQuestion)
            if (document.getElementById('countdown')) {
                startCountdown();
            }
        @endif
        
        // Update participants every 3 seconds
        setInterval(updateParticipants, 3000);
    });
    
    function startCountdown() {
        updateCountdownDisplay();
        
        countdownInterval = setInterval(() => {         
            timeLeft--;
            updateCountdownDisplay();
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                handleTimeout();
            }
        }, 1000);
    }
    
    function updateCountdownDisplay() {
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            countdownElement.textContent = timeLeft;
            
            // Change color when time is running out
            if (timeLeft <= 10) {
                countdownElement.classList.add('text-red-500');
            } else {
                countdownElement.classList.remove('text-red-500');
            }
        }
    }
    
    function startPretestSession() {
        if (confirm('Apakah Anda yakin ingin memulai sesi pretest?')) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("guru.jadwal_ujian.startPretestSession") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: {{ $session->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Gagal memulai sesi pretest: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }
    }
    
    function nextQuestion() {
        if (confirm('Apakah Anda yakin ingin melanjutkan ke soal berikutnya?')) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("guru.jadwal_ujian.updatePretestTime") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: {{ $session->id }},
                    current_question_id: {{ $currentQuestion ? $currentQuestion->id : 0 }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Gagal melanjutkan ke soal berikutnya: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }
    }
    
    function handleTimeout() {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route("guru.jadwal_ujian.handleTimeout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                session_id: {{ $session->id }},
                current_question_id: {{ $currentQuestion ? $currentQuestion->id : 0 }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    location.reload();
                }
            } else {
                alert('Gagal memproses timeout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
    
    function endPretest() {
        if (confirm('Apakah Anda yakin ingin mengakhiri pretest?')) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("guru.jadwal_ujian.updatePretestTime") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: {{ $session->id }},
                    current_question_id: {{ $currentQuestion ? $currentQuestion->id : 0 }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = '{{ route("guru.jadwal_ujian.pretest.results", $session->id) }}';
                    }
                } else {
                    alert('Gagal mengakhiri pretest: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        }
    }
    
    function updateParticipants() {
        fetch('{{ route("guru.jadwal_ujian.pretest.participants", $session->id) }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateParticipantsList(data.participants);
            }
        })
        .catch(error => {
            console.error('Error updating participants:', error);
        });
    }
    
    function updateParticipantsList(participants) {
        const participantsList = document.getElementById('participantsList');
        
        if (participants && participants.length > 0) {
            participantsList.innerHTML = participants.map(participant => `
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                    <img src="https://api.dicebear.com/7.x/big-ears/svg?seed=${participant.nama_siswa}"
                         alt="${participant.nama_siswa}"
                         class="w-10 h-10 rounded-full border-2 border-white shadow-md animate-bounce"
                         onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(participant.nama_siswa)}&background=6366f1&color=ffffff&size=48'">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 dark:text-white">${participant.nama_siswa}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${participant.status}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            ${participant.skor_total} poin
                        </p>
                    </div>
                </div>
            `).join('');
        }
    }
    
    // Clean up interval when page is unloaded
    window.addEventListener('beforeunload', function() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
    });
</script>
@endpush
@endsection