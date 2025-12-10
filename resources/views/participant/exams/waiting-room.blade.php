@extends('layouts.participant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Kahoot-style Header -->
            <div class="mb-8 text-center">
                <div class="inline-block mb-4">
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white font-bold text-3xl md:text-4xl px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105">
                        {{ $bankSoal->nama_bank }}
                    </div>
                </div>
                <p class="text-lg text-gray-700 dark:text-gray-300 font-medium">Menunggu peserta lain bergabung...</p>
            </div>

            <!-- Game PIN Display -->
            <div class="flex justify-center mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-6 shadow-xl transform transition-all duration-300 hover:scale-105">
                    <div class="text-center">
                        <p class="text-white text-sm font-medium mb-2">KODE UJIAN</p>
                        <div class="flex justify-center space-x-2">
                            @for($i = 0; $i < strlen($bankSoal->kode_bank); $i++)
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-white text-purple-700 font-bold text-xl md:text-2xl flex items-center justify-center rounded-lg shadow-inner">
                                    {{ substr($bankSoal->kode_bank, $i, 1) }}
                                </div>
                            @endfor
                        </div>
                        <p class="text-white text-xs mt-3 opacity-80">Bagikan kode ini kepada peserta lain</p>
                    </div>
                </div>
            </div>

            <!-- Nickname Entry Area -->
            <div class="flex justify-center mb-8">
                <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 border-2 border-purple-200">
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Masukkan Nama Anda</h3>
                        <p class="text-sm text-gray-600">Nama ini akan ditampilkan selama ujian</p>
                    </div>
                    <form id="nickname-form" class="space-y-4">
                        <div>
                            <input type="text" id="nickname-input"
                                   class="w-full px-4 py-3 rounded-lg border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none transition-colors text-center text-lg font-medium"
                                   placeholder="Ketik nama Anda"
                                   value="{{ Auth::guard('siswa')->user()->nama_siswa ?? '' }}"
                                   maxlength="15">
                        </div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-opacity-75">
                            <i class="ri-user-add-line mr-2"></i> Bergabung ke Ujian
                        </button>
                    </form>
                    <div id="nickname-feedback" class="mt-3 text-center text-sm hidden"></div>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800 animate-pulse">
                    <div class="text-sm text-red-800 dark:text-red-200 flex items-center">
                        <i class="ri-error-warning-line mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Exam Info -->
                <div class="lg:col-span-1">
                    <div class="overflow-visible rounded-xl border border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-900/50 relative shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-t-xl"></div>
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 mr-3 shadow-md">
                                    <i class="ri-file-list-3-line text-purple-600 dark:text-purple-400 text-xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Ujian</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $bankSoal->mataPelajaran->nama_mapel ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-3 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tipe</span>
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 shadow-sm">
                                        {{ $bankSoal->type_test }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Soal</span>
                                    <span class="text-sm text-gray-900 dark:text-white font-semibold">{{ $bankSoal->pertanyaanSoals->count() }} soal</span>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border border-purple-200 dark:border-purple-800 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 mr-3 shadow-md">
                                        <i class="ri-user-group-line text-purple-600 dark:text-purple-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-purple-900 dark:text-purple-100">Peserta Bergabung</p>
                                        <p class="text-xl font-bold text-purple-900 dark:text-purple-100" id="participant-count">{{ $participants->count() }} orang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants List -->
                <div class="lg:col-span-2">
                    <div class="overflow-visible rounded-xl border border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-900/50 relative shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-t-xl"></div>
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 mr-3 shadow-md">
                                    <i class="ri-team-line text-purple-600 dark:text-purple-400 text-xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">Peserta yang Bergabung</h4>
                            </div>

                            <div id="participants-container">
                                @if($participants->count() > 0)
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="participants-grid">
                                        @foreach($participants as $participant)
                                            <div class="text-center participant-item transform transition-all duration-300 hover:scale-105" data-nisn="{{ $participant->nisn }}">
                                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-purple-900 dark:to-indigo-900 mb-2 overflow-hidden shadow-md border-2 border-white dark:border-gray-700 animate-bounce">
                                                    <img src="{{ 'https://api.dicebear.com/7.x/big-ears/svg?seed=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown')  }}"
                                                         alt="{{ $participant->siswa->nama_siswa ?? 'Avatar' }}"
                                                         class="w-full h-full object-cover participant-avatar "
                                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($participant->siswa->nama_siswa ?? 'User') }}&background=8B5CF6&color=ffffff&size=64'">
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate participant-name">
                                                    {{ $participant->siswa->nama_siswa ?? 'Peserta' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 participant-score">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                        {{ $participant->skor_total }} poin
                                                    </span>
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12" id="no-participants">
                                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-purple-900 dark:to-indigo-900 mb-4 shadow-md animate-pulse">
                                            <i class="ri-user-add-line text-purple-400 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Peserta</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Anda adalah peserta pertama yang bergabung. Tunggu peserta lain...
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Message -->
            <div class="mt-6">
                <div class="overflow-visible rounded-xl border border-purple-200 dark:border-purple-800 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 relative shadow-md transform transition-all duration-300 hover:scale-[1.02]">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-t-xl"></div>
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 mr-3 shadow-md">
                                <i class="ri-time-line text-purple-600 dark:text-purple-400 text-xl animate-pulse"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-base font-medium text-purple-800 dark:text-purple-200">
                                    Menunggu guru memulai sesi ujian
                                </p>
                                <p class="text-sm text-purple-700 dark:text-purple-300 mt-1">
                                    Sesi akan otomatis dimulai ketika guru menekan tombol mulai. Halaman ini akan diperbarui secara otomatis.
                                </p>
                            </div>
                            <div class="ml-3">
                                <!-- Kahoot-style loading animation -->
                                <div class="flex space-x-1">
                                    <div class="w-3 h-3 rounded-full bg-purple-500 animate-bounce"></div>
                                    <div class="w-3 h-3 rounded-full bg-purple-500 animate-bounce delay-75"></div>
                                    <div class="w-3 h-3 rounded-full bg-purple-500 animate-bounce delay-150"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teacher Controls (visible only to teachers) -->
            @if(Auth::guard('guru')->check())
            <div class="mt-8">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-white mb-4">Kontrol Guru</h3>
                        <p class="text-green-100 mb-6">Tekan tombol di bawah untuk memulai ujian</p>
                        
                        <div class="flex justify-center space-x-4">
                            <button id="start-exam-btn" class="bg-white text-green-700 font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                                <i class="ri-play-line mr-2"></i> Mulai Ujian
                            </button>
                            
                            <button id="start-countdown-btn" class="bg-yellow-400 text-yellow-900 font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-75">
                                <i class="ri-timer-line mr-2"></i> Mulai Hitung Mundur (10 detik)
                            </button>
                        </div>
                        
                        <div id="teacher-feedback" class="mt-4 text-white text-sm hidden"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Kahoot-style waiting animation -->
            <div class="mt-8 flex justify-center">
                <div class="relative w-64 h-64">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-48 h-48 rounded-full border-8 border-purple-200 animate-ping opacity-20"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-40 h-40 rounded-full border-8 border-purple-300 animate-ping opacity-30" style="animation-delay: 0.2s"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 rounded-full border-8 border-purple-400 animate-ping opacity-40" style="animation-delay: 0.4s"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <i class="ri-time-line text-white text-3xl animate-pulse"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// AJAX update every 3 seconds
let updateInterval;

function updateParticipants() {
    fetch(`{{ route('participant.exams.waiting-room.participants', $bankSoal->id) }}`, {
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
            updateParticipantCount(data.total_participants);
        } else {
            console.error('Error updating participants:', data.error);
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
    });
}

function checkSessionStatus() {
    fetch(`{{ route('participant.exams.waiting-room.status', $bankSoal->id) }}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.status === 'running') {
                // Session has started, redirect to live exam
                window.location.href = data.redirect_url;
            } else if (data.status === 'countdown') {
                // Show countdown timer
                showCountdown(data.countdown_seconds);
            }
        } else {
            console.error('Error checking session status:', data.error);
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
    });
}

function updateParticipantsList(participants) {
    const container = document.getElementById('participants-container');
    const grid = document.getElementById('participants-grid');
    const noParticipants = document.getElementById('no-participants');
    
    if (participants.length === 0) {
        if (grid) grid.style.display = 'none';
        if (!noParticipants) {
            container.innerHTML = `
                <div class="text-center py-12" id="no-participants">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-purple-900 dark:to-indigo-900 mb-4 shadow-md animate-pulse">
                        <i class="ri-user-add-line text-purple-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Peserta</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Anda adalah peserta pertama yang bergabung. Tunggu peserta lain...
                    </p>
                </div>
            `;
        } else {
            noParticipants.style.display = 'block';
        }
    } else {
        if (noParticipants) noParticipants.style.display = 'none';
        
        if (!grid) {
            container.innerHTML = '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="participants-grid"></div>';
        }
        
        const gridElement = document.getElementById('participants-grid');
        const existingParticipants = new Set();
        
        // Collect existing participants
        gridElement.querySelectorAll('.participant-item').forEach(item => {
            existingParticipants.add(item.dataset.nisn);
        });
        
        // Add new participants
        participants.forEach(participant => {
            if (!existingParticipants.has(participant.nisn)) {
                const participantDiv = document.createElement('div');
                participantDiv.className = 'text-center participant-item';
                participantDiv.dataset.nisn = participant.nisn;
                participantDiv.innerHTML = `
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-indigo-200 dark:from-purple-900 dark:to-indigo-900 mb-2 overflow-hidden shadow-md border-2 border-white dark:border-gray-700">
                        <img src="{{ 'https://api.dicebear.com/7.x/big-ears/svg?seed=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown')  }}"
                             alt="${participant.nama_siswa}"
                             class="w-full h-full object-cover participant-avatar"
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(participant.nama_siswa)}&background=8B5CF6&color=ffffff&size=64'">
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate participant-name">
                        ${participant.nama_siswa}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 participant-score">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                            ${participant.skor_total} poin
                        </span>
                    </p>
                `;
                gridElement.appendChild(participantDiv);
                
                // Kahoot-style animation for new participant
                participantDiv.style.opacity = '0';
                participantDiv.style.transform = 'scale(0.8) rotate(10deg)';
                setTimeout(() => {
                    participantDiv.style.transition = 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    participantDiv.style.opacity = '1';
                    participantDiv.style.transform = 'scale(1) rotate(0deg)';
                    
                    // Play a sound effect if available
                    playJoinSound();
                }, 100);
                
                // Show a notification for new participant
                showJoinNotification(participant.nama_siswa);
            }
        });
        
        gridElement.style.display = 'grid';
    }
}

// Function to play join sound
function playJoinSound() {
    try {
        // Create a simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.type = 'sine';
        oscillator.frequency.value = 523.25; // C5 note
        gainNode.gain.value = 0.1;
        
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {
        console.log('Audio not supported');
    }
}

// Function to show join notification
function showJoinNotification(participantName) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-all duration-500 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="ri-user-add-line mr-2"></i>
            <span>${participantName} bergabung!</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(120%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

function updateParticipantCount(count) {
    const countElement = document.getElementById('participant-count');
    if (countElement) {
        const oldCount = parseInt(countElement.textContent);
        if (oldCount !== count) {
            // Kahoot-style animation for count update
            countElement.style.transition = 'all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
            countElement.style.transform = 'scale(1.5) rotate(5deg)';
            countElement.style.color = '#8B5CF6'; // Purple color for highlight
            
            // Animate the number change
            animateValue(countElement, oldCount, count, 500);
            
            setTimeout(() => {
                countElement.style.transform = 'scale(1) rotate(0deg)';
                setTimeout(() => {
                    countElement.style.color = ''; // Reset to original color
                }, 500);
            }, 300);
        }
    }
}

// Function to animate number counting
function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range > 0 ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        element.textContent = current + ' orang';
        if (current === end) {
            clearInterval(timer);
        }
    }, stepTime);
}

// Function to show countdown timer
function showCountdown(seconds) {
    // Clear existing interval to prevent multiple updates
    if (updateInterval) {
        clearInterval(updateInterval);
    }
    
    // Create countdown overlay
    const countdownOverlay = document.createElement('div');
    countdownOverlay.className = 'fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50';
    countdownOverlay.innerHTML = `
        <div class="text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 mb-4 shadow-2xl">
                    <span class="text-white text-6xl font-bold" id="countdown-number">${seconds}</span>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Ujian akan dimulai dalam</h2>
            <p class="text-xl text-purple-200">Bersiaplah!</p>
        </div>
    `;
    
    document.body.appendChild(countdownOverlay);
    
    // Animate countdown
    let remainingSeconds = seconds;
    const countdownNumber = document.getElementById('countdown-number');
    
    const countdownInterval = setInterval(() => {
        remainingSeconds--;
        
        if (remainingSeconds <= 0) {
            clearInterval(countdownInterval);
            // Redirect to exam
            window.location.href = countdownOverlay.getAttribute('data-redirect-url');
        } else {
            // Animate number change
            countdownNumber.style.transform = 'scale(1.5)';
            countdownNumber.style.opacity = '0.5';
            
            setTimeout(() => {
                countdownNumber.textContent = remainingSeconds;
                countdownNumber.style.transform = 'scale(1)';
                countdownNumber.style.opacity = '1';
                
                // Add pulse animation for last 3 seconds
                if (remainingSeconds <= 3) {
                    countdownNumber.parentElement.classList.add('animate-ping');
                    setTimeout(() => {
                        countdownNumber.parentElement.classList.remove('animate-ping');
                    }, 1000);
                }
            }, 200);
        }
    }, 1000);
    
    // Check session status more frequently during countdown
    updateInterval = setInterval(() => {
        checkSessionStatus().then(data => {
            if (data.success && data.status === 'running') {
                // Update redirect URL if available
                if (data.redirect_url) {
                    countdownOverlay.setAttribute('data-redirect-url', data.redirect_url);
                }
            }
        });
    }, 500);
}

// Handle teacher controls
document.addEventListener('DOMContentLoaded', function() {
    const startExamBtn = document.getElementById('start-exam-btn');
    const startCountdownBtn = document.getElementById('start-countdown-btn');
    const teacherFeedback = document.getElementById('teacher-feedback');
    
    // Start exam immediately
    if (startExamBtn) {
    startExamBtn.addEventListener('click', function() {
        fetch(`participant/exams/start/{{ $bankSoal->id }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showTeacherFeedback('Ujian telah dimulai!', 'success');
                startExamBtn.disabled = true;
                startCountdownBtn.disabled = true;
                startExamBtn.innerHTML = '<i class="ri-check-line mr-2"></i> Ujian Dimulai';
            } else {
                showTeacherFeedback(data.error || 'Gagal memulai ujian', 'error');
            }
        })
        .catch(error => {
            console.error('Error starting exam:', error);
            showTeacherFeedback('Terjadi kesalahan, coba lagi', 'error');
        });
    });
}

    // Start countdown
    if (startCountdownBtn) {
        startCountdownBtn.addEventListener('click', function() {
            fetch(`{{ route('participant.exams.start-countdown', $bankSoal->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    countdown_seconds: 10
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showTeacherFeedback('Hitung mundur telah dimulai!', 'success');
                    startCountdownBtn.disabled = true;
                    startExamBtn.disabled = true;
                    startCountdownBtn.innerHTML = '<i class="ri-timer-line mr-2"></i> Hitung Mundur Aktif';
                } else {
                    showTeacherFeedback(data.error || 'Gagal memulai hitung mundur', 'error');
                }
            })
            .catch(error => {
                console.error('Error starting countdown:', error);
                showTeacherFeedback('Terjadi kesalahan, coba lagi', 'error');
            });
        });
    }
    
    function showTeacherFeedback(message, type) {
        if (teacherFeedback) {
            teacherFeedback.textContent = message;
            teacherFeedback.classList.remove('hidden');
            
            // Hide feedback after 3 seconds
            setTimeout(() => {
                teacherFeedback.classList.add('hidden');
            }, 3000);
        }
    }
    
    // Handle nickname form submission
    const nicknameForm = document.getElementById('nickname-form');
    const nicknameInput = document.getElementById('nickname-input');
    const nicknameFeedback = document.getElementById('nickname-feedback');
    console.log("{{ $participant->status }}");
    if("{{ $participant->status }}" == "waiting"){
        if (nicknameForm) {
            nicknameForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const nickname = nicknameInput.value.trim();
                
                if (nickname.length < 3) {
                    showFeedback('Nama minimal 3 karakter', 'error');
                    return;
                }
                
                if (nickname.length > 15) {
                    showFeedback('Nama maksimal 15 karakter', 'error');
                    return;
                }
                
                // Submit nickname to server
                fetch(`{{ route('participant.exams.waiting-room.nickname', $bankSoal->id) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nickname: nickname
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showFeedback('Berhasil bergabung dengan nama: ' + nickname, 'success');
                        
                        // Update the current participant's display name
                        const currentParticipantName = document.querySelector('.current-participant-name');
                        if (currentParticipantName) {
                            currentParticipantName.textContent = nickname;
                        }
                        
                        // Disable the form after successful submission
                        nicknameInput.disabled = true;
                        nicknameForm.querySelector('button[type="submit"]').disabled = true;
                        nicknameForm.querySelector('button[type="submit"]').innerHTML = '<i class="ri-check-line mr-2"></i> Telah Bergabung';
                    } else {
                        showFeedback(data.error || 'Gagal menyimpan nama', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error submitting nickname:', error);
                    showFeedback('Terjadi kesalahan, coba lagi', 'error');
                });
            });
        }
    
    } else {
         // Disable the form after successful submission
                        nicknameInput.disabled = true;
                        nicknameForm.querySelector('button[type="submit"]').disabled = true;
                        nicknameForm.querySelector('button[type="submit"]').innerHTML = '<i class="ri-check-line mr-2"></i> Telah Bergabung';
    }
  
    function showFeedback(message, type) {
        if (nicknameFeedback) {
            nicknameFeedback.textContent = message;
            nicknameFeedback.classList.remove('hidden', 'text-green-600', 'text-red-600');
            
            if (type === 'success') {
                nicknameFeedback.classList.add('text-green-600');
            } else {
                nicknameFeedback.classList.add('text-red-600');
            }
            
            // Hide feedback after 3 seconds
            setTimeout(() => {
                nicknameFeedback.classList.add('hidden');
            }, 3000);
        }
    }
    
    // Start auto-update
    updateInterval = setInterval(() => {
        updateParticipants();
        checkSessionStatus();
    }, 3000);
});

// Clean up interval when page is unloaded
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});
</script>
@endsection