@extends('layouts.admin')

@section('title', 'Import Guru (Fixed)')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.guru.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="ri-arrow-left-line text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Import Data Guru (Fixed)</h1>
    </div>
</header>

<!-- Import Form -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <div class="mb-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-information-line text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Petunjuk Import</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Download template Excel terlebih dahulu untuk format yang benar</li>
                            <li>Pastikan file Excel memiliki kolom: nik, nama_guru, email, password</li>
                            <li>Email bersifat opsional, namun password wajib diisi</li>
                            <li>NIK harus unik dan tidak boleh duplikat dengan data yang sudah ada</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('admin.guru.template') }}" method="GET" target="_blank" onsubmit="console.log('Download template form submitted'); return true;">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="ri-download-line mr-2"></i>
                                Download Template
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Area -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Pilih File Excel <span class="text-red-500">*</span>
        </label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors" id="dropZone">
            <div class="space-y-1 text-center">
                <i class="ri-upload-cloud-line text-4xl text-gray-400"></i>
                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                    <label for="file-upload" class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-md font-medium text-primary hover:text-primary/90 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                        <span>Upload file</span>
                        <input id="file-upload" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv">
                    </label>
                    <p class="pl-1">atau drag and drop</p>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">XLSX, XLS, CSV hingga 2MB</p>
            </div>
        </div>
        <div id="file-info" class="mt-2 hidden">
            <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-800 rounded">
                <i class="ri-file-excel-line text-green-500 mr-2"></i>
                <span id="file-name" class="text-sm text-gray-700 dark:text-gray-300"></span>
                <button type="button" id="remove-file" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Preview Area -->
    <div id="preview-area" class="hidden mb-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Preview Data</h3>
        
        <!-- Progress Bar -->
        <div id="progress-container" class="hidden mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Proses Import</span>
                <span id="progress-text" class="text-sm text-gray-500 dark:text-gray-400">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div id="progress-bar" class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                <span id="success-count">0</span> berhasil, <span id="fail-count">0</span> gagal
            </div>
        </div>

        <!-- Preview Table -->
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="preview-tbody" class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Preview data will be inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Error Messages -->
        <div id="error-messages" class="mt-4 hidden">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Error Validasi</h4>
                <ul id="error-list" class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                    <!-- Error messages will be inserted here -->
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.guru.index') }}" 
            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            Batal
        </a>
        <button type="button" id="preview-btn" 
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            <i class="ri-eye-line mr-2"></i>
            Preview
        </button>
        <button type="button" id="import-btn" 
            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors hidden">
            <i class="ri-upload-line mr-2"></i>
            Import Data
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Test if JavaScript is loading
console.log('Guru importfix page loaded, script executing');

// DOM loaded event listener
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and ready');
});

let selectedFile = null;
let previewData = [];

// File upload handling
const fileInput = document.getElementById('file-upload');
const dropZone = document.getElementById('dropZone');
const fileInfo = document.getElementById('file-info');
const fileName = document.getElementById('file-name');
const removeFileBtn = document.getElementById('remove-file');
const previewBtn = document.getElementById('preview-btn');
const importBtn = document.getElementById('import-btn');

// Test if elements are found
console.log('Elements found:', {
    fileInput: !!fileInput,
    dropZone: !!dropZone,
    fileInfo: !!fileInfo,
    fileName: !!fileName,
    removeFileBtn: !!removeFileBtn,
    previewBtn: !!previewBtn,
    importBtn: !!importBtn
});

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-primary/5');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-primary/5');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-primary/5');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFileSelect(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFileSelect(e.target.files[0]);
    }
});

removeFileBtn.addEventListener('click', () => {
    clearFileSelection();
});

function handleFileSelect(file) {
    console.log('File selected:', file);
    console.log('File type:', file.type);
    console.log('File name:', file.name);
    console.log('File size:', file.size);
    
    // Validate file type
    const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
    console.log('Valid types:', validTypes);
    console.log('Type validation:', validTypes.includes(file.type));
    console.log('Extension validation:', file.name.match(/\.(xlsx|xls|csv)$/i));
    
    if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
        console.error('Invalid file type');
        alert('File harus berformat Excel (XLSX, XLS) atau CSV');
        return;
    }

    // Validate file size (2MB)
    const maxSize = 2 * 1024 * 1024;
    console.log('Max size:', maxSize);
    console.log('Size validation:', file.size <= maxSize);
    
    if (file.size > maxSize) {
        console.error('File too large');
        alert('Ukuran file maksimal 2MB');
        return;
    }

    console.log('File validation passed');
    selectedFile = file;
    fileName.textContent = file.name;
    fileInfo.classList.remove('hidden');
    previewBtn.disabled = false;
    
    console.log('File selection completed, preview button enabled');
    console.log('Selected file variable:', selectedFile);
}

function clearFileSelection() {
    selectedFile = null;
    fileInput.value = '';
    fileInfo.classList.add('hidden');
    previewBtn.disabled = true;
    importBtn.classList.add('hidden');
    document.getElementById('preview-area').classList.add('hidden');
    previewData = [];
}

// Preview functionality
previewBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    console.log('Preview button clicked, selectedFile:', selectedFile);
    console.log('Preview route:', "{{ route('admin.guru.preview') }}");
    
    if (!selectedFile) {
        console.log('No file selected');
        alert('Silakan pilih file terlebih dahulu');
        return;
    }

    console.log('Creating FormData...');
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('_token', "{{ csrf_token() }}");
    
    console.log('FormData created, file size:', selectedFile.size);
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0]+ ': ' + pair[1]);
    }

    try {
        previewBtn.disabled = true;
        previewBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Loading...';

        console.log('Sending preview request to:', "{{ route('admin.guru.preview') }}");

        const response = await fetch("{{ route('admin.guru.preview') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        });

        console.log('Preview response received');
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response not ok:', errorText);
            throw new Error('Network response was not ok: ' + response.status);
        }

        const result = await response.json();
        console.log('Preview result:', result);

        if (result.success) {
            previewData = result.data;
            displayPreview(result.data, result.errors);
            importBtn.classList.remove('hidden');
        } else {
            console.error('Preview failed:', result);
            alert('Gagal mempreview file: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Preview error:', error);
        console.error('Error stack:', error.stack);
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        previewBtn.disabled = false;
        previewBtn.innerHTML = '<i class="ri-eye-line mr-2"></i>Preview';
    }
});

function displayPreview(data, errors) {
    const previewArea = document.getElementById('preview-area');
    const tbody = document.getElementById('preview-tbody');
    const errorMessages = document.getElementById('error-messages');
    const errorList = document.getElementById('error-list');

    previewArea.classList.remove('hidden');
    tbody.innerHTML = '';

    data.forEach((row, index) => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-800';
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${index + 1}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.nik || ''}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.nama_guru || ''}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${row.email || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Pending
                </span>
            </td>
        `;
        tbody.appendChild(tr);
    });

    // Display errors if any
    if (errors && errors.length > 0) {
        errorMessages.classList.remove('hidden');
        errorList.innerHTML = '';
        errors.forEach(error => {
            const li = document.createElement('li');
            const rowNum = error.row ? (Array.isArray(error.row) ? error.row.join(', ') : error.row) : 'Unknown';
            const errorMsg = error.error || (error.errors ? error.errors.join(', ') : 'Unknown error');
            li.textContent = `Baris ${rowNum}: ${errorMsg}`;
            errorList.appendChild(li);
        });
    } else {
        errorMessages.classList.add('hidden');
    }
}

// Import functionality
importBtn.addEventListener('click', async () => {
    console.log('Import button clicked, selectedFile:', selectedFile);
    
    if (!selectedFile) {
        alert('Silakan pilih file terlebih dahulu');
        return;
    }
    
    if (!confirm('Apakah Anda yakin ingin mengimport data ini?')) return;

    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('_token', "{{ csrf_token() }}");

    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const successCount = document.getElementById('success-count');
    const failCount = document.getElementById('fail-count');

    progressContainer.classList.remove('hidden');
    importBtn.disabled = true;
    importBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Importing...';

    try {
        console.log('Sending import request...');
        
        const response = await fetch("{{ route('admin.guru.process') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        });

        console.log('Import response status:', response.status);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json();
        console.log('Import result:', result);

        if (result.success) {
            // Animate progress
            let currentProgress = 0;
            const totalSteps = 100;
            const increment = 100 / totalSteps;
            
            const progressInterval = setInterval(() => {
                currentProgress += increment;
                if (currentProgress >= 100) {
                    currentProgress = 100;
                    clearInterval(progressInterval);
                    
                    // Update final counts
                    successCount.textContent = result.success_count;
                    failCount.textContent = result.fail_count;
                    
                    // Show completion message
                    setTimeout(() => {
                        alert(`Import selesai!\n${result.success_count} data berhasil diimport\n${result.fail_count} data gagal diimport`);
                        window.location.href = "{{ route('admin.guru.index') }}";
                    }, 500);
                }
                
                progressBar.style.width = currentProgress + '%';
                progressText.textContent = Math.round(currentProgress) + '%';
                
                // Update counts during progress
                if (currentProgress < 100) {
                    const estimatedSuccess = Math.round((currentProgress / 100) * result.success_count);
                    const estimatedFail = Math.round((currentProgress / 100) * result.fail_count);
                    successCount.textContent = estimatedSuccess;
                    failCount.textContent = estimatedFail;
                }
            }, 20);
        } else {
            alert('Gagal mengimport: ' + result.message);
        }
    } catch (error) {
        console.error('Import error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        if (progressContainer.classList.contains('hidden')) {
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="ri-upload-line mr-2"></i>Import Data';
        }
    }
});
</script>
@endpush
@endsection