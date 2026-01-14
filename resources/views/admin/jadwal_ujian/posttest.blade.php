@extends('layouts.admin')

@section('title', 'Posttest')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Posttest</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola sesi posttest</p>
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

    <!-- Bank Soal List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bankSoals as $bankSoal)
            @if($bankSoal->type_test === 'posttest')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4 text-white">
                    <h3 class="text-lg font-bold">{{ $bankSoal->nama_bank }}</h3>
                    <p class="text-sm text-green-100">{{ $bankSoal->mataPelajaran->nama_mapel ?? '-' }} - {{ $bankSoal->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                </div>
                
                <div class="p-4">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Jumlah Soal: {{ $bankSoal->pertanyaanSoals->count() }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Tipe: {{ ucfirst($bankSoal->type_test) }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('guru.pertanyaan_soal.index', $bankSoal->id) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="ri-question-line mr-1"></i> Lihat Soal
                        </a>
                        
                        <div class="relative">
                            <button id="dropdown-button-{{ $bankSoal->id }}" onclick="toggleDropdown({{ $bankSoal->id }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                                <i class="ri-more-2-fill text-xl"></i>
                            </button>
                            
                            <div id="dropdown-menu-{{ $bankSoal->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10">
                                <div class="py-1">
                                    @php
                                        $hasActiveParticipants = \App\Models\PosttestPeserta::where('bank_soal_id', $bankSoal->id)
                                            ->whereIn('status', ['active', 'ongoing'])
                                            ->exists();
                                        
                                        $hasResults = \App\Models\PosttestHasil::where('bank_soal_id', $bankSoal->id)
                                            ->exists();
                                    @endphp
                                    
                                    @if(!$hasActiveParticipants)
                                        <button onclick="startPosttest({{ $bankSoal->id }})" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <i class="ri-play-circle-line mr-2"></i> Start Posttest
                                        </button>
                                    @else
                                        <a href="{{ route('admin.jadwal_ujian.posttest.live', $bankSoal->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <i class="ri-play-circle-line mr-2"></i> Monitoring
                                        </a>
                                    @endif
                                    
                                    @if($hasResults)
                                        <a href="{{ route('admin.jadwal_ujian.posttest.hasil', $bankSoal->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <i class="ri-file-list-3-line mr-2"></i> Lihat Hasil
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 dark:text-gray-500 mb-4">
                    <i class="ri-inbox-line text-5xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada bank soal</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Buat bank soal terlebih dahulu untuk memulai posttest</p>
                <a href="{{ route('guru.bank_soal.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="ri-add-line mr-2"></i> Buat Bank Soal
                </a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    // Function to toggle dropdown menu
    function toggleDropdown(bankSoalId) {
        const dropdown = document.getElementById(`dropdown-menu-${bankSoalId}`);
        
        // Hide all other dropdowns
        document.querySelectorAll('[id^="dropdown-menu-"]').forEach(menu => {
            if (menu.id !== `dropdown-menu-${bankSoalId}`) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="dropdown-button-"]')) {
            document.querySelectorAll('[id^="dropdown-menu-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
    
    function startPosttest(bankSoalId) {
        console.log('Starting posttest for bank soal ID:', bankSoalId);
        
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Memproses...';
        button.disabled = true;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Initialize posttest
        fetch('{{ route("admin.jadwal_ujian.storePosttest") }}', {
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
                // Posttest initialized successfully, now start posttest
                return fetch('{{ route("admin.jadwal_ujian.startPosttest") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        bank_soal_id: bankSoalId
                    })
                });
            } else {
                throw new Error(data.message || 'Failed to initialize posttest');
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
                        <span>Posttest berhasil dimulai!</span>
                    </div>
                `;
                document.body.appendChild(successDiv);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    document.body.removeChild(successDiv);
                }, 3000);
                
                // Redirect to live page
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            } else {
                throw new Error(data.message || 'Failed to start posttest');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memulai posttest: ' + error.message);
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
</script>
@endpush
@endsection