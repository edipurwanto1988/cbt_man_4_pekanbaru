@extends('layouts.guru')

@section('title', 'Pertanyaan Soal - ' . $bankSoal->nama_bank)

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Pertanyaan Soal</h1>
            <p class="text-gray-600 dark:text-gray-400">Bank Soal: {{ $bankSoal->nama_bank }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guru.bank_soal.index') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-700 text-sm font-bold leading-normal tracking-wide hover:bg-gray-300 transition-colors">
                <i class="ri-arrow-left-line text-xl"></i>
                <span class="truncate">Kembali</span>
            </a>
            <button onclick="openImportModal()" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-green-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-green-700 transition-colors">
                <i class="ri-upload-cloud-line text-xl"></i>
                <span class="truncate">Impor</span>
            </button>
            <button id="delete-selected" onclick="deleteSelected()" class="hidden flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-red-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-red-700 transition-colors">
                <i class="ri-delete-bin-line text-xl"></i>
                <span class="truncate">Hapus Terpilih</span>
            </button>
            <a href="{{ route('guru.pertanyaan_soal.create', $bankSoal->id) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-wide hover:bg-primary/90 transition-colors">
                <i class="ri-add-circle-line text-xl"></i>
                <span class="truncate">Tambah Soal</span>
            </a>
        </div>
    </header>

    <!-- Alert Message -->
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-800/30 dark:text-green-400">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative">
        <div>
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left">
                            <input type="checkbox" id="select-all" onchange="toggleSelectAll()" class="rounded border-gray-300 text-primary focus:ring-primary">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Jenis Soal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Pertanyaan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Gambar
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Bobot
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900/50">
                    @forelse ($pertanyaanSoals as $pertanyaanSoal)
                        <tr>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" name="selected_items[]" value="{{ $pertanyaanSoal->id }}" class="item-checkbox rounded border-gray-300 text-primary focus:ring-primary" onchange="updateDeleteButton()">
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($pertanyaanSoal->jenis_soal == 'pilihan_ganda') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                    @elseif($pertanyaanSoal->jenis_soal == 'esai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 @endif">
                                    {{ ucwords(str_replace('_', ' ', $pertanyaanSoal->jenis_soal)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                {{ strip_tags($pertanyaanSoal->pertanyaan) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                @if($pertanyaanSoal->gambar_soal)
                                    <img src="{{ asset('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal) }}" alt="Gambar Soal" class="h-10 w-10 rounded object-cover">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <div class="text-xs">
                                    <div class="text-green-600">Benar: {{ $pertanyaanSoal->bobot_benar ?? '0.00' }}</div>
                                    <div class="text-red-600">Salah: {{ $pertanyaanSoal->bobot_salah ?? '0.00' }}</div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Dropdown Menu -->
                                    <div class="relative inline-block text-left">
                                        <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                                onclick="toggleDropdown('dropdown-{{ $pertanyaanSoal->id }}')">
                                            <i class="ri-arrow-down-s-line"></i>
                                        </button>
                                        
                                        <div id="dropdown-{{ $pertanyaanSoal->id }}"
                                             class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-[9999]">
                                            <div class="py-1">
                                                <a href="{{ route('guru.pertanyaan_soal.show', [$bankSoal->id, $pertanyaanSoal->id]) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-eye-line mr-2"></i>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('guru.pertanyaan_soal.edit', [$bankSoal->id, $pertanyaanSoal->id]) }}"
                                                   class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <i class="ri-edit-line mr-2"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data pertanyaan soal
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Impor Pertanyaan Soal</h3>
                    <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-4">
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
                                        <li>Pastikan file Excel memiliki kolom: jenis_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban_benar</li>
                                        <li>Jenis soal: pilihan_ganda, esai, benar_salah</li>
                                        <li>Untuk benar_salah, isi jawaban_benar dengan T (Benar) atau F (Salah)</li>
                                    </ul>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('guru.pertanyaan_soal.template', $bankSoal->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="ri-download-line mr-2"></i>
                                        Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div class="mb-4">
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

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3">
                    <button onclick="closeImportModal()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Batal
                    </button>
                    <button type="button" id="import-btn" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="ri-upload-line mr-2"></i>
                        Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== dropdownId) {
                    el.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
        
        // Checkbox functions
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            
            updateDeleteButton();
        }
        
        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const deleteButton = document.getElementById('delete-selected');
            
            if (checkedBoxes.length > 0) {
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
            }
        }
        
        function deleteSelected() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Silakan pilih minimal satu item yang akan dihapus');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin menghapus ${checkedBoxes.length} pertanyaan soal yang dipilih?`)) {
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('guru.pertanyaan_soal.destroy_multiple', $bankSoal->id) }}";
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
        
        // Import Modal Functions
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            clearFileSelection();
        }
        
        // File upload handling
        let selectedFile = null;
        
        const fileInput = document.getElementById('file-upload');
        const dropZone = document.getElementById('dropZone');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file');
        const importBtn = document.getElementById('import-btn');
        
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
            // Validate file type
            const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];
            
            if (!validTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                alert('File harus berformat Excel (XLSX, XLS) atau CSV');
                return;
            }
            
            // Validate file size (2MB)
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('Ukuran file maksimal 2MB');
                return;
            }
            
            selectedFile = file;
            fileName.textContent = file.name;
            fileInfo.classList.remove('hidden');
            importBtn.disabled = false;
        }
        
        function clearFileSelection() {
            selectedFile = null;
            fileInput.value = '';
            fileInfo.classList.add('hidden');
            importBtn.disabled = true;
        }
        
        // Import functionality
        importBtn.addEventListener('click', async () => {
            if (!selectedFile) {
                alert('Silakan pilih file terlebih dahulu');
                return;
            }
            
            if (!confirm('Apakah Anda yakin ingin mengimport data ini?')) return;
            
            const formData = new FormData();
            formData.append('file', selectedFile);
            formData.append('_token', "{{ csrf_token() }}");
            
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Importing...';
            
            try {
                const response = await fetch("{{ route('guru.pertanyaan_soal.import', $bankSoal->id) }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(`Import selesai!\n${result.success_count} data berhasil diimport\n${result.fail_count} data gagal diimport`);
                    location.reload();
                } else {
                    alert('Gagal mengimport: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                importBtn.disabled = false;
                importBtn.innerHTML = '<i class="ri-upload-line mr-2"></i>Import';
            }
        });
    </script>
    @endpush
@endsection