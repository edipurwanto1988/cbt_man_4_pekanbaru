@extends('layouts.admin')

@section('title', 'Detail Sesi Pretest - ' . $session->bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Sesi Pretest</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $session->bankSoal->nama_bank }}</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.pretest') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="ri-arrow-left-line mr-2"></i>Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-alert-line"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Session Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kode Sesi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $session->kode_sesi ?? '-' }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                    <i class="ri-key-line text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($session->status == 'waiting') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($session->status == 'running') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endif">
                            {{ ucfirst($session->status) }}
                        </span>
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                    <i class="ri-time-line text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $session->pesertas->count() }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                    <i class="ri-group-line text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Soal</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $session->soalTimers->count() }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full">
                    <i class="ri-question-line text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
            <h2 class="text-xl font-bold">Daftar Peserta</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($session->pesertas as $index => $participant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $participant->siswa->foto ? asset('storage/uploads/siswa/' . $participant->siswa->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->siswa->nama_siswa ?? 'Unknown') . '&background=6366f1&color=ffffff&size=32' }}" 
                                         alt="{{ $participant->siswa->nama_siswa }}"
                                         class="w-8 h-8 rounded-full mr-3"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($participant->siswa->nama_siswa ?? 'Unknown') }}&background=6366f1&color=ffffff&size=32'">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $participant->siswa->nama_siswa }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $participant->siswa->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($participant->status == 'waiting') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($participant->status == 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($participant->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($participant->pretestHasil)
                                    <a href="{{ route('admin.jadwal_ujian.pretest.results', $session->id) }}#participant-{{ $participant->nisn }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Lihat Hasil
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3">
        @if($session->status == 'waiting')
            <button onclick="startPretest({{ $session->id }})" class="bg-green-500 hover:bg-green-600 text-white py-2 px-6 rounded-lg font-medium transition">
                <i class="ri-play-line mr-2"></i> Mulai Pretest
            </button>
        @endif
        
        @if($session->status == 'running')
            <a href="{{ route('admin.jadwal_ujian.pretest.live', $session->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg font-medium transition">
                <i class="ri-eye-line mr-2"></i> Monitor Pretest
            </a>
        @endif
        
        @if($session->status == 'finished')
            <a href="{{ route('admin.jadwal_ujian.pretest.results', $session->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-6 rounded-lg font-medium transition">
                <i class="ri-bar-chart-line mr-2"></i> Lihat Hasil
            </a>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function startPretest(sessionId) {
        if (confirm('Apakah Anda yakin ingin memulai pretest?')) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("admin.jadwal_ujian.startPretestSession") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: sessionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.href = '{{ route("admin.jadwal_ujian.pretest.live", ":sessionId") }}'.replace(':sessionId', sessionId);
                    }
                } else {
                    alert('Gagal memulai pretest: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memulai pretest: ' + error.message);
            });
        }
    }
</script>
@endpush
@endsection