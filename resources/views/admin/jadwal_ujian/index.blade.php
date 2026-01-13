@extends('layouts.admin')

@section('title', 'Jadwal Ujian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Ujian</h1>
            <p class="text-gray-600 dark:text-gray-400">Daftar jadwal ujian yang Anda buat</p>
        </div>
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

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('admin.jadwal_ujian.index') }}" class="flex gap-4 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama bank soal..." 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
            </div>
            <div class="w-48">
                <select name="tahun_ajaran_id" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tahun Ajaran</option>
                    @foreach($tahunAjarans as $tahunAjaran)
                        <option value="{{ $tahunAjaran->id }}" 
                                {{ request('tahun_ajaran_id') == $tahunAjaran->id ? 'selected' : '' }}>
                            {{ $tahunAjaran->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="type_test" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="pretest" {{ request('type_test') == 'pretest' ? 'selected' : '' }}>Pretest</option>
                    <option value="posttest" {{ request('type_test') == 'posttest' ? 'selected' : '' }}>Posttest</option>
                </select>
            </div>
            <div class="w-40">
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" 
                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                <i class="ri-search-line"></i>
                Cari
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-visible relative">
        <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Bank Soal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tahun Ajaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tipe Test
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pengawas
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bankSoals as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors align-top">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $loop->index + 1 }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="ri-file-list-3-line text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->nama_bank }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->kode_bank }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->tahunAjaran->tahun_ajaran }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->mataPelajaran->nama_mapel ?? '-'}}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs font-medium 
                                    {{ $item->type_test == 'pretest' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' }} 
                                    rounded-full">
                                    {{ ucwords($item->type_test) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($item->tanggal_mulai && $item->tanggal_selesai)
                                    <div class="text-sm">{{ $item->tanggal_mulai ? date('d M Y, H:i', strtotime($item->tanggal_mulai)) : '-' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">s/d {{ $item->tanggal_selesai ? date('d M Y, H:i', strtotime($item->tanggal_selesai)) : '-' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->durasi_menit }} menit</div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs font-medium 
                                    {{ $item->status == 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' : '' }}
                                    {{ $item->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $item->status == 'selesai' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                    rounded-full">
                                    {{ ucwords($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->pengawas->nama_guru ?? '-' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="menu-button-{{ $item->id }}" aria-expanded="false" aria-haspopup="true" onclick="toggleDropdown('dropdown-{{ $item->id }}')">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    
                                    <div id="dropdown-{{ $item->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-[9999]" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $item->id }}" tabindex="-1">
                                        <div class="py-1" role="none">
                                            @if($item->type_test == 'pretest')
                                                @if($item->status == 'aktif')
                                                    <button onclick="startPretest({{ $item->id }})" class="flex items-center w-full px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                        <i class="ri-play-circle-line mr-2"></i>
                                                        Start Pretest
                                                    </button>
                                                @elseif($item->status == 'selesai' && $item->pretestSession)
                                                    <a href="/admin/jadwal_ujian/pretest-results/{{ $item->pretestSession->id }}" class="flex items-center w-full px-4 py-2 text-sm inline-block text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                        <i class="ri-eye-line mr-2"></i>
                                                        Lihat Hasil
                                                    </a>
                                                @endif
                                            @elseif($item->type_test == 'posttest')
                                                @if($item->status == 'aktif')
                                                    <button onclick="startPosttest({{ $item->id }})" class="flex items-center w-full px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                        <i class="ri-play-circle-line mr-2"></i>
                                                        Lihat Posttest
                                                    </button>
                                                @elseif($item->status == 'selesai')
                                                    <a href="/admin/jadwal_ujian/posttest-hasil/{{ $item->id }}" class="flex items-center w-full px-4 py-2 text-sm inline-block text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                        <i class="ri-eye-line mr-2"></i>
                                                        Lihat Hasil
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="py-1" role="none">
                                            @if($item->status == 'aktif')
                                                <button onclick="markAsSelesai({{ $item->id }})" class="flex items-center w-full px-4 py-2 text-sm text-yellow-600 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left" role="menuitem">
                                                    <i class="ri-checkbox-circle-line mr-2"></i>
                                                    Tandai Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="ri-calendar-schedule-line text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada jadwal ujian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Button -->
    <div class="mt-6 flex justify-end">
        <!-- Tombol Kelola Pretest telah dihapus -->
    </div>
</div>
@push('scripts')
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        
        // Close all dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== id) {
                d.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (!d.contains(event.target) && !event.target.closest('[id^="menu-button-"]')) {
                d.classList.add('hidden');
            }
        });
    });
    
    function startPretest(bankSoalId) {
        console.log('Starting pretest for bank soal ID:', bankSoalId);
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        // Send AJAX request to create and start pretest session
        const url = `/admin/jadwal_ujian/start/${bankSoalId}`;
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
                // Redirect to waiting room (live pretest page)
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    // Fallback redirect if redirect_url is not provided
                    window.location.href = '{{ route("admin.jadwal_ujian.pretest") }}';
                }
            } else {
                alert('Gagal membuat sesi pretest: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat sesi pretest: ' + error.message);
        });
    }
    
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
<script>
function markAsSelesai(bankSoalId) {
    if (!confirm('Apakah Anda yakin ingin menandai bank soal ini sebagai selesai?')) {
        return;
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send AJAX request
    fetch(`/admin/jadwal_ujian/${bankSoalId}/mark-selesai`, {
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
            // Show success notification
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-all duration-300';
            successDiv.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="ri-checkbox-circle-line"></i>
                    <span>${data.message}</span>
                </div>
            `;
            document.body.appendChild(successDiv);
            
            // Remove notification after 2 seconds and reload page
            setTimeout(() => {
                document.body.removeChild(successDiv);
                window.location.reload();
            }, 2000);
        } else {
            alert('Gagal menandai bank soal sebagai selesai: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menandai bank soal sebagai selesai');
    });
}
</script>
