@extends('layouts.admin')

@section('title', 'Pretest')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pretest</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola sesi pretest</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
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

    <!-- Create Button -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.jadwal_ujian.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg font-medium transition">
            <i class="ri-add-line mr-2"></i> Buat Sesi Pretest Baru
        </a>
    </div>
    
    <!-- Bank Soals List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($bankSoals as $bankSoal)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                    <h3 class="text-xl font-bold">{{ $bankSoal->nama_bank }}</h3>
                    <p class="text-blue-100 text-sm">{{ $bankSoal->mataPelajaran->nama_mapel ?? '-' }}</p>
                </div>
                
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full">
                            @if($bankSoal->pretestSession && $bankSoal->pretestSession->status === 'finished')
                                <span class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Selesai</span>
                            @elseif($bankSoal->pretestSession && in_array($bankSoal->pretestSession->status, ['waiting', 'running']))
                                <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">{{ ucfirst($bankSoal->pretestSession->status) }}</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum Dimulai</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Soal</span>
                        <span class="text-sm font-medium">{{ $bankSoal->pertanyaanSoals->count() }} soal</span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</span>
                        <span class="text-sm">{{ $bankSoal->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex gap-2">
                        @if($bankSoal->pretestSession && $bankSoal->pretestSession->status === 'finished')
                            <a href="{{ route('admin.jadwal_ujian.pretest.results', $bankSoal->pretestSession->id) }}" class="flex-1 bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition text-center">
                                <i class="ri-bar-chart-line mr-1"></i> Finish
                            </a>
                        @elseif($bankSoal->pretestSession && $bankSoal->pretestSession->status === 'running')
                            <a href="{{ route('admin.jadwal_ujian.pretest.live', $bankSoal->pretestSession->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition text-center">
                                <i class="ri-play-circle-line mr-1"></i> Running
                            </a>
                        @elseif($bankSoal->pretestSession && $bankSoal->pretestSession->status === 'waiting')
                            <a href="{{ route('admin.jadwal_ujian.pretest.live', $bankSoal->pretestSession->id) }}" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition text-center">
                                <i class="ri-play-circle-line mr-1"></i> Start Pretest
                            </a>
                        @else
                            <button id="startPretestBtn-{{ $bankSoal->id }}" onclick="startPretest({{ $bankSoal->id }})" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition">
                                <i class="ri-play-circle-line mr-1"></i> Start Pretest
                            </button>
                        @endif
                        
                        <a href="{{ route('guru.bank_soal.show', $bankSoal->id) }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-2 px-4 rounded-lg text-sm font-medium transition">
                            <i class="ri-settings-3-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="ri-file-list-3-line text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400 mb-2">Belum ada bank soal</h3>
                <p class="text-gray-400 dark:text-gray-500 mb-6">Buat bank soal baru untuk memulai</p>
                <a href="{{ route('guru.bank_soal.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg font-medium transition">
                    <i class="ri-add-line mr-2"></i> Buat Bank Soal
                </a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    function startPretest(bankSoalId) {
        console.log('Starting pretest for bank soal ID:', bankSoalId);
        
        // Show loading state
        const startBtn = document.getElementById('startPretestBtn-' + bankSoalId);
        const originalText = startBtn.innerHTML;
        startBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-1"></i> Memproses...';
        startBtn.disabled = true;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        // First check if there's already a pretest session
        const checkUrl = '{{ route("admin.jadwal_ujian.checkPretestStatus", ":bankSoalId") }}'.replace(':bankSoalId', bankSoalId);
        
        fetch(checkUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.has_session) {
                // If session exists, redirect to appropriate page
                if (data.status === 'finished') {
                    window.location.href = '{{ route("admin.jadwal_ujian.pretest.results", ":sessionId") }}'.replace(':sessionId', data.session_id);
                } else if (data.status === 'waiting' || data.status === 'running') {
                    window.location.href = '{{ route("admin.jadwal_ujian.pretest.live", ":sessionId") }}'.replace(':sessionId', data.session_id);
                }
            } else {
                // If no session exists, create one
                const url = '{{ route("admin.jadwal_ujian.start", ":bankSoalId") }}'.replace(':bankSoalId', bankSoalId);
                console.log('Request URL:', url);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Redirect to live pretest session
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.href = '{{ route("admin.jadwal_ujian.pretest.live", ":sessionId") }}'.replace(':sessionId', bankSoalId);
                        }
                    } else {
                        alert('Gagal memulai pretest: ' + data.message);
                        startBtn.innerHTML = originalText;
                        startBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memulai pretest: ' + error.message);
                    startBtn.innerHTML = originalText;
                    startBtn.disabled = false;
                });
            }
        })
        .catch(error => {
            console.error('Error checking pretest status:', error);
            alert('Terjadi kesalahan: ' + error.message);
            startBtn.innerHTML = originalText;
            startBtn.disabled = false;
        });
    }
    
    function checkPretestStatus(bankSoalId) {
        // Check if there's already a pretest session for this bank soal
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('{{ route("admin.jadwal_ujian.checkPretestStatus", ":bankSoalId") }}'.replace(':bankSoalId', bankSoalId), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.has_session) {
                if (data.status === 'finished') {
                    window.location.href = '{{ route("admin.jadwal_ujian.pretest.results", ":sessionId") }}'.replace(':sessionId', data.session_id);
                } else if (data.status === 'waiting' || data.status === 'running') {
                    window.location.href = '{{ route("admin.jadwal_ujian.pretest.live", ":sessionId") }}'.replace(':sessionId', data.session_id);
                }
            }
        })
        .catch(error => {
            console.error('Error checking pretest status:', error);
        });
    }
</script>
@endpush
@endsection