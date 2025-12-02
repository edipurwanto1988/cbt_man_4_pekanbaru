@extends('layouts.admin')

@section('title', 'Import Siswa - ' . $rombel->nama_rombel)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rombel.siswa', $rombel->id) }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Siswa</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Import data siswa untuk {{ $rombel->nama_rombel }}
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

    <!-- Import Instructions -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="ri-information-line text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
            <div>
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Petunjuk Import:</h3>
                <ol class="text-sm text-blue-700 dark:text-blue-300 space-y-1 list-decimal list-inside">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Isi data siswa sesuai format yang tersedia</li>
                    <li>Pastikan NISN siswa sudah ada di database master siswa</li>
                    <li>Upload file Excel yang telah diisi</li>
                    <li>Periksa preview data sebelum memproses import</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Import Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="p-6">
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih File Excel <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-primary/50 transition-colors">
                            <input type="file" 
                                   id="file" 
                                   name="file" 
                                   accept=".xlsx,.xls,.csv"
                                   class="hidden"
                                   onchange="handleFileSelect(this)">
                            <label for="file" class="cursor-pointer">
                                <i class="ri-upload-cloud-line text-4xl text-gray-400 dark:text-gray-500"></i>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Klik untuk memilih file atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    Format: .xlsx, .xls, .csv (Maks. 2MB)
                                </p>
                            </label>
                        </div>
                        <div id="fileInfo" class="hidden mt-2">
                            <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                <i class="ri-file-excel-line"></i>
                                <span id="fileName"></span>
                                <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.rombel.siswa', $rombel->id) }}" 
                           class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </a>
                        <a href="{{ route('admin.rombel.siswa.template', $rombel->id) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <i class="ri-download-line"></i>
                            Download Template
                        </a>
                        <button type="button" 
                                id="previewBtn"
                                onclick="previewData()"
                                disabled
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="ri-eye-line"></i>
                            Preview Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Preview Data Siswa</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <div id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closePreview()" 
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </button>
                <button onclick="processImport()" 
                        id="processBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <i class="ri-check-line"></i>
                    Proses Import
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedFile = null;

function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        if (file.size > 2097152) { // 2MB
            alert('File terlalu besar. Maksimal ukuran file adalah 2MB.');
            input.value = '';
            return;
        }
        
        selectedFile = file;
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileInfo').classList.remove('hidden');
        document.getElementById('previewBtn').disabled = false;
    }
}

function clearFile() {
    document.getElementById('file').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('previewBtn').disabled = true;
    selectedFile = null;
}

function previewData() {
    if (!selectedFile) {
        alert('Pilih file terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile);

    document.getElementById('previewBtn').disabled = true;
    document.getElementById('previewBtn').innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Loading...';

    fetch(`{{ route('admin.rombel.siswa.preview', $rombel->id) }}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('previewBtn').disabled = false;
        document.getElementById('previewBtn').innerHTML = '<i class="ri-eye-line"></i> Preview Data';
        
        if (data.success) {
            showPreview(data);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        document.getElementById('previewBtn').disabled = false;
        document.getElementById('previewBtn').innerHTML = '<i class="ri-eye-line"></i> Preview Data';
        alert('Terjadi kesalahan: ' + error.message);
    });
}

function showPreview(data) {
    const content = `
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Total data: <span class="font-medium">${data.total || 0}</span>
                </span>
                <span class="text-sm text-green-600 dark:text-green-400">
                    Valid: <span class="font-medium">${data.valid_count || 0}</span>
                </span>
            </div>
            ${data.errors && data.errors.length > 0 ? `
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded p-3 mb-4">
                    <p class="text-sm text-red-800 dark:text-red-200 font-medium mb-2">Error:</p>
                    <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                        ${data.errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            ` : ''}
            ${data.valid_count > 0 ? `
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded p-3">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        <i class="ri-check-line mr-1"></i>
                        ${data.valid_count} data siswa valid dan siap diimport
                    </p>
                </div>
            ` : ''}
            ${data.valid_count === 0 && data.errors.length === 0 ? `
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded p-3">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <i class="ri-information-line mr-1"></i>
                        Tidak ada data yang ditemukan dalam file
                    </p>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = content;
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

function processImport() {
    if (!selectedFile) {
        alert('Pilih file terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile);

    document.getElementById('processBtn').disabled = true;
    document.getElementById('processBtn').innerHTML = '<i class="ri-loader-4-line animate-spin"></i> Processing...';

    fetch(`{{ route('admin.rombel.siswa.process', $rombel->id) }}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Import berhasil diproses!');
            window.location.href = '{{ route('admin.rombel.siswa', $rombel->id) }}';
        } else {
            alert('Error: ' + data.message);
            document.getElementById('processBtn').disabled = false;
            document.getElementById('processBtn').innerHTML = '<i class="ri-check-line"></i> Proses Import';
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
        document.getElementById('processBtn').disabled = false;
        document.getElementById('processBtn').innerHTML = '<i class="ri-check-line"></i> Proses Import';
    });
}
</script>
@endpush
@endsection