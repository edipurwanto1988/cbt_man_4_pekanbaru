@extends('layouts.admin')

@section('title', 'Siswa Rombel - ' . $rombel->nama_rombel)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rombel.index') }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Siswa Rombel</h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ $rombel->nama_rombel }} - {{ $rombel->tingkatKelas->nama ?? '' }} {{ $rombel->kode_kelas }}
            </p>
        </div>
    </div>

    <!-- Rombel Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="ri-group-line text-blue-600 dark:text-blue-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Rombel</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $rombel->nama_rombel }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="ri-calendar-line text-green-600 dark:text-green-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tahun Ajaran</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $rombel->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <i class="ri-user-tie text-purple-600 dark:text-purple-300"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Wali Kelas</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $rombel->waliKelas->nama_guru ?? 'Belum ada' }}</p>
                </div>
            </div>
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

    <!-- Actions and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            <div class="flex-1 w-full md:w-auto">
                <form method="GET" action="{{ route('admin.rombel.siswa', $rombel->id) }}" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama siswa..." 
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <button type="submit" 
                            class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="ri-search-line"></i>
                        Cari
                    </button>
                </form>
            </div>
            <div class="flex gap-2">
                <button onclick="openAddStudentModal()"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <i class="ri-user-add-line"></i>
                    Tambah Siswa
                </button>
                <a href="{{ route('admin.rombel.siswa.import', $rombel->id) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <i class="ri-upload-line"></i>
                    Import Siswa
                </a>
                <a href="{{ route('admin.rombel.siswa.template', $rombel->id) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="ri-download-line"></i>
                    Template
                </a>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            NISN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Jenis Kelamin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($siswa as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $siswa->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->nisn }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="ri-user-3-line text-primary"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->nama_siswa }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2 py-1 text-xs font-medium 
                                    {{ $item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }} rounded-full">
                                    {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $item->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.siswa.show', $item->nisn) }}"
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                       title="Lihat Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <form action="{{ route('admin.rombel.siswa.remove', [$rombel->id, $item->nisn]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini dari rombel?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Hapus dari Rombel">
                                            <i class="ri-user-unfollow-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="ri-graduation-cap-line text-4xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-gray-500 dark:text-gray-400">Belum ada siswa di rombel ini</p>
                                    <a href="{{ route('admin.rombel.siswa.import', $rombel->id) }}"
                                       class="text-primary hover:text-primary/80 font-medium">
                                        Import Siswa Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($siswa->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                Menampilkan {{ $siswa->firstItem() }} hingga {{ $siswa->lastItem() }} dari {{ $siswa->total() }} data
            </div>
            {{ $siswa->links() }}
        </div>
    @endif
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Siswa ke Rombel</h3>
                <button onclick="closeAddStudentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.rombel.siswa.store', $rombel->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="nisn"
                                   name="nisn"
                                   required
                                   maxlength="20"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan NISN">
                            <div id="nisn-loading" class="absolute right-3 top-2.5 hidden">
                                <i class="ri-loader-4-line animate-spin text-gray-400"></i>
                            </div>
                        </div>
                        <div id="nisn-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        <div id="nisn-success" class="text-green-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="nama_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Siswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nama_siswa"
                               name="nama_siswa"
                               required
                               readonly
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600"
                               placeholder="Nama akan muncul otomatis">
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin"
                                name="jenis_kelamin"
                                required
                                disabled
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               readonly
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600"
                               placeholder="Email akan muncul otomatis (opsional)">
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            id="submit-btn"
                            disabled
                            class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="ri-save-line mr-2"></i>
                        Simpan
                    </button>
                    <button type="button"
                            onclick="closeAddStudentModal()"
                            class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let currentStudent = null;

function openAddStudentModal() {
    document.getElementById('addStudentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Focus on NISN input
    document.getElementById('nisn').focus();
}

function closeAddStudentModal() {
    document.getElementById('addStudentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form and state
    resetForm();
}

function resetForm() {
    const form = document.querySelector('#addStudentModal form');
    form.reset();
    
    // Reset field states
    document.getElementById('nama_siswa').readOnly = true;
    document.getElementById('jenis_kelamin').disabled = true;
    document.getElementById('email').readOnly = true;
    document.getElementById('submit-btn').disabled = true;
    
    // Reset visual states
    document.getElementById('nama_siswa').classList.add('bg-gray-50', 'dark:bg-gray-600');
    document.getElementById('jenis_kelamin').classList.add('bg-gray-50', 'dark:bg-gray-600');
    document.getElementById('email').classList.add('bg-gray-50', 'dark:bg-gray-600');
    
    // Hide messages
    document.getElementById('nisn-error').classList.add('hidden');
    document.getElementById('nisn-success').classList.add('hidden');
    document.getElementById('nisn-loading').classList.add('hidden');
    
    currentStudent = null;
}

function enableStudentFields() {
    document.getElementById('nama_siswa').readOnly = false;
    document.getElementById('jenis_kelamin').disabled = false;
    document.getElementById('email').readOnly = false;
    document.getElementById('submit-btn').disabled = false;
    
    // Remove disabled styling
    document.getElementById('nama_siswa').classList.remove('bg-gray-50', 'dark:bg-gray-600');
    document.getElementById('jenis_kelamin').classList.remove('bg-gray-50', 'dark:bg-gray-600');
    document.getElementById('email').classList.remove('bg-gray-50', 'dark:bg-gray-600');
}

function searchStudentByNISN(nisn) {
    if (nisn.length < 3) {
        resetForm();
        return;
    }
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Show loading
    document.getElementById('nisn-loading').classList.remove('hidden');
    document.getElementById('nisn-error').classList.add('hidden');
    document.getElementById('nisn-success').classList.add('hidden');
    
    // Set timeout for AJAX call
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.rombel.siswa.search', $rombel->id) }}?nisn=${encodeURIComponent(nisn)}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('nisn-loading').classList.add('hidden');
            
            if (data.success) {
                if (data.already_in_rombel) {
                    document.getElementById('nisn-error').textContent = 'Siswa ini sudah ada di rombel ini.';
                    document.getElementById('nisn-error').classList.remove('hidden');
                    resetForm();
                } else {
                    // Populate form fields
                    document.getElementById('nama_siswa').value = data.student.nama_siswa;
                    document.getElementById('jenis_kelamin').value = data.student.jenis_kelamin;
                    document.getElementById('email').value = data.student.email || '';
                    
                    // Enable fields
                    enableStudentFields();
                    
                    // Show success message
                    document.getElementById('nisn-success').textContent = 'Data siswa ditemukan!';
                    document.getElementById('nisn-success').classList.remove('hidden');
                    
                    currentStudent = data.student;
                }
            } else {
                document.getElementById('nisn-error').textContent = data.message;
                document.getElementById('nisn-error').classList.remove('hidden');
                resetForm();
            }
        })
        .catch(error => {
            document.getElementById('nisn-loading').classList.add('hidden');
            document.getElementById('nisn-error').textContent = 'Terjadi kesalahan saat mencari data siswa.';
            document.getElementById('nisn-error').classList.remove('hidden');
            console.error('AJAX error:', error);
            resetForm();
        });
    }, 500); // 500ms delay
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const nisnInput = document.getElementById('nisn');
    
    nisnInput.addEventListener('input', function() {
        const nisn = this.value.trim();
        if (nisn) {
            searchStudentByNISN(nisn);
        } else {
            resetForm();
        }
    });
    
    nisnInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && currentStudent) {
            e.preventDefault();
            document.querySelector('#addStudentModal form').submit();
        }
    });
});

// Close modal when clicking outside
document.getElementById('addStudentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddStudentModal();
    }
});
</script>
@endsection