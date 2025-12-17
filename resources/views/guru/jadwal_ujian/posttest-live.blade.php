@extends('layouts.guru')

@section('title', 'Monitoring Posttest')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Posttest</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $bankSoal->nama_bank }} -
                    {{ $bankSoal->mataPelajaran->nama_mapel ?? '-' }}
                </p>
            </div>
            <a href="{{ route('guru.jadwal_ujian.index') }}"
                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="ri-arrow-left-line mr-2"></i>Kembali
            </a>
        </div>

        <!-- Bank Soal Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Status Posttest</p>
                    <p class="text-lg font-semibold">
                        @if($bankSoal->tanggal_mulai && $bankSoal->tanggal_mulai > now())
                            <span class="text-yellow-600"><i class="ri-time-line mr-1"></i> Menunggu</span>
                        @elseif($bankSoal->tanggal_selesai && $bankSoal->tanggal_selesai < now())
                            <span class="text-blue-600"><i class="ri-checkbox-circle-line mr-1"></i> Selesai</span>
                        @else
                            <span class="text-green-600"><i class="ri-play-circle-line mr-1"></i> Berjalan</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Waktu Mulai</p>
                    <p class="text-lg font-semibold">
                        {{ $bankSoal->tanggal_mulai ? $bankSoal->tanggal_mulai->format('d/m/Y H:i:s') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Waktu Selesai</p>
                    <p class="text-lg font-semibold">
                        {{ $bankSoal->tanggal_selesai ? $bankSoal->tanggal_selesai->format('d/m/Y H:i:s') : '-' }}
                    </p>
                </div>
            </div>

            <!-- Timer Section -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    @if($bankSoal->tanggal_mulai && $bankSoal->tanggal_mulai > now())
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Waktu Tersisa Hingga Mulai</p>
                    @elseif($bankSoal->tanggal_selesai && $bankSoal->tanggal_selesai > now())
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Waktu Tersisa Hingga Selesai</p>
                    @endif

                    <div id="countdown-timer"
                        data-start="{{ $bankSoal->tanggal_mulai ? $bankSoal->tanggal_mulai->timestamp : '' }}"
                        data-end="{{ $bankSoal->tanggal_selesai ? $bankSoal->tanggal_selesai->timestamp : '' }}"
                        class="flex justify-center gap-4 text-2xl font-bold">
                        <div class="flex flex-col items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-4 min-w-[80px]">
                            <span id="days" class="text-3xl">00</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hari</span>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-4 min-w-[80px]">
                            <span id="hours" class="text-3xl">00</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Jam</span>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-4 min-w-[80px]">
                            <span id="minutes" class="text-3xl">00</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Menit</span>
                        </div>
                        <div class="flex flex-col items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-4 min-w-[80px]">
                            <span id="seconds" class="text-3xl">00</span>
                            <span class="text-xs text-gray-600 dark:text-gray-400 mt-1">Detik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Peserta</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Siswa
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sisa Waktu / Hasil
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cheat Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nilai
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($participants as $index => $participant)
                                            <tr
                                                class="@if($participant->cheat_status === 'blocked') bg-red-50 dark:bg-red-900/10 @elseif($participant->hasil) bg-green-50 dark:bg-green-900/10 @endif">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $participant->nama_siswa }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($participant->hasil)
                                                        {{-- Sudah selesai dan punya hasil --}}
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            <i class="ri-checkbox-circle-fill mr-1"></i> Selesai
                                                        </span>
                                                    @elseif($participant->cheat_status === 'blocked')
                                                        {{-- Diblokir --}}
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            <i class="ri-error-warning-fill mr-1"></i> Diblokir
                                                        </span>
                                                    @elseif($participant->status == 'waiting')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            Menunggu
                                                        </span>
                                                    @elseif($participant->status == 'active')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                            {{ $participant->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    @if($participant->hasil)
                                                        {{-- Tampilkan hasil --}}
                                                        <div class="font-semibold text-green-600 dark:text-green-400">
                                                            {{ $participant->hasil->total_poin ?? 0 }} poin
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            Benar: {{ $participant->hasil->total_benar }} |
                                                            Salah: {{ $participant->hasil->total_salah }} |
                                                            Kosong: {{ $participant->hasil->total_kosong }}
                                                        </div>
                                                    @elseif($participant->sisa_detik)
                                                        {{-- Tampilkan sisa waktu --}}
                                                        {{ gmdate('H:i:s', $participant->sisa_detik) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($participant->cheat_status)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            {{ $participant->cheat_status }}
                                                        </span>
                                                        @if($participant->cheat_reason)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $participant->cheat_reason }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            Clean
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ $participant->hasil->nilai_akhir }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    <a href="{{ route('guru.jadwal_ujian.detail', [
                                'banksoalId' => $bankSoal->id,
                                'nisn' => $participant->nisn
                            ]) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                                             Detail
                                                    </a>
                                                    @if($participant->cheat_status === 'blocked')
                                                        <button onclick="unblockParticipant('{{ $participant->nisn }}', '{{ $bankSoal->id }}')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-lg transition">
                                                            <i class="ri-lock-unlock-line mr-1"></i> Unblock
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada peserta
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function unblockParticipant(nisn, bankSoalId) {
                if (!confirm('Apakah Anda yakin ingin unblock peserta ini?')) {
                    return;
                }

                fetch(`/guru/posttest/unblock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nisn: nisn,
                        bank_soal_id: bankSoalId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal unblock peserta: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat unblock peserta');
                    });
            }
        </script>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            @if($bankSoal->posttest_status == 'waiting')
                <button onclick="startPosttest({{ $bankSoal->id }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="ri-play-line mr-2"></i> Mulai Posttest
                </button>
            @elseif($bankSoal->posttest_status == 'running')
                <button onclick="finishPosttest({{ $bankSoal->id }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="ri-stop-line mr-2"></i> Selesaikan Posttest
                </button>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function startCountdown() {
                const timerElement = document.getElementById('countdown-timer');
                const startTime = parseInt(timerElement.dataset.start) * 1000;
                const endTime = parseInt(timerElement.dataset.end) * 1000;

                const daysElement = document.getElementById('days');
                const hoursElement = document.getElementById('hours');
                const minutesElement = document.getElementById('minutes');
                const secondsElement = document.getElementById('seconds');

                function updateTimer() {
                    // Get current time directly in Jakarta timezone and convert to timestamp
                    const now = new Date(new Date()).getTime();

                    let targetTime;

                    if (startTime && now < startTime) {
                        targetTime = startTime;
                    } else if (endTime && now < endTime) {
                        targetTime = endTime;
                    } else {
                        daysElement.textContent = '00';
                        hoursElement.textContent = '00';
                        minutesElement.textContent = '00';
                        secondsElement.textContent = '00';
                        return;
                    }

                    const distance = targetTime - now;

                    if (distance < 0) {
                        location.reload();
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    daysElement.textContent = String(days).padStart(2, '0');
                    hoursElement.textContent = String(hours).padStart(2, '0');
                    minutesElement.textContent = String(minutes).padStart(2, '0');
                    secondsElement.textContent = String(seconds).padStart(2, '0');
                }

                updateTimer();
                setInterval(updateTimer, 1000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startCountdown);
            } else {
                startCountdown();
            }

            function startPosttest(bankSoalId) {
                if (!confirm('Apakah Anda yakin ingin memulai posttest?')) {
                    return;
                }

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("guru.jadwal_ujian.startPosttest") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        bank_soal_id: bankSoalId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const successDiv = document.createElement('div');
                            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-all duration-300';
                            successDiv.innerHTML = `
                                                                            <div class="flex items-center gap-2">
                                                                                <i class="ri-checkbox-circle-line"></i>
                                                                                <span>Posttest berhasil dimulai!</span>
                                                                            </div>
                                                                        `;
                            document.body.appendChild(successDiv);

                            // Remove notification after 3 seconds
                            setTimeout(() => {
                                document.body.removeChild(successDiv);
                            }, 3000);

                            // Reload page
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            alert('Gagal memulai posttest: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memulai posttest');
                    });
            }

            function finishPosttest(bankSoalId) {
                if (!confirm('Apakah Anda yakin ingin menyelesaikan posttest?')) {
                    return;
                }

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/guru/jadwal_ujian/posttest/${bankSoalId}/finish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            const successDiv = document.createElement('div');
                            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-all duration-300';
                            successDiv.innerHTML = `
                                                                            <div class="flex items-center gap-2">
                                                                                <i class="ri-checkbox-circle-line"></i>
                                                                                <span>Posttest berhasil diselesaikan!</span>
                                                                            </div>
                                                                        `;
                            document.body.appendChild(successDiv);

                            // Remove notification after 3 seconds
                            setTimeout(() => {
                                document.body.removeChild(successDiv);
                            }, 3000);

                            // Redirect to posttest page
                            setTimeout(() => {
                                window.location.href = '{{ route("guru.jadwal_ujian.posttest") }}';
                            }, 1500);
                        } else {
                            alert('Gagal menyelesaikan posttest: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyelesaikan posttest');
                    });
            }
        </script>
    @endpush
@endsection