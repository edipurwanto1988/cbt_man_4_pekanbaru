@extends('layouts.guru')

@section('title', 'Tambah Pertanyaan Soal - ' . $bankSoal->nama_bank)

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Tambah Pertanyaan Soal</h1>
            <p class="text-gray-600 dark:text-gray-400">Bank Soal: {{ $bankSoal->nama_bank }}</p>
        </div>
        <a href="{{ route('guru.pertanyaan_soal.index', $bankSoal->id) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-700 text-sm font-bold leading-normal tracking-wide hover:bg-gray-300 transition-colors">
            <i class="ri-arrow-left-line text-xl"></i>
            <span class="truncate">Kembali</span>
        </a>
    </header>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('guru.pertanyaan_soal.store', $bankSoal->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Jenis Soal -->
                <div>
                    <label for="jenis_soal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jenis Soal <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_soal"
                            id="jenis_soal"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white"
                            required>
                        <option value="">Pilih Jenis Soal</option>
                        <option value="pilihan_ganda" {{ old('jenis_soal') == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                    </select>
                    @error('jenis_soal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pertanyaan -->
                <div>
                    <label for="pertanyaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pertanyaan <span class="text-red-500">*</span>
                    </label>
                    <div id="pertanyaan-editor">
                        <textarea name="pertanyaan"
                                  id="pertanyaan"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white"
                                  required>{{ old('pertanyaan') }}</textarea>
                    </div>
                    @error('pertanyaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Soal -->
                <div>
                    <label for="gambar_soal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Gambar Soal (Opsional)
                    </label>
                    <input type="file" 
                           name="gambar_soal" 
                           id="gambar_soal" 
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maks: 2MB</p>
                    @error('gambar_soal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jawaban Pilihan Ganda -->
                <div id="jawaban-pilihan-ganda" class="hidden">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Opsi Jawaban <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="tambah-jawaban" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                            <i class="ri-add-line mr-1"></i> Tambah Opsi
                        </button>
                    </div>
                    
                    <div id="container-jawaban" class="space-y-3">
                        <!-- Opsi A -->
                        <div class="jawaban-item flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-full text-sm font-medium">A</span>
                            </div>
                            <div class="flex-grow">
                                <input type="text"
                                       name="jawaban[A]"
                                       placeholder="Isi jawaban A"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex-shrink-0">
                                <input type="file"
                                       name="gambar_jawaban[A]"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                            </div>
                            <div class="flex-shrink-0">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_benar" value="A" class="form-radio text-primary">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Benar</span>
                                </label>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="hapus-jawaban text-red-600 hover:text-red-800">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Opsi B -->
                        <div class="jawaban-item flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-full text-sm font-medium">B</span>
                            </div>
                            <div class="flex-grow">
                                <input type="text"
                                       name="jawaban[B]"
                                       placeholder="Isi jawaban B"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex-shrink-0">
                                <input type="file"
                                       name="gambar_jawaban[B]"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                            </div>
                            <div class="flex-shrink-0">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_benar" value="B" class="form-radio text-primary">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Benar</span>
                                </label>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="hapus-jawaban text-red-600 hover:text-red-800">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Opsi C -->
                        <div class="jawaban-item flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <span class="w-8 h-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-full text-sm font-medium">C</span>
                            </div>
                            <div class="flex-grow">
                                <input type="text"
                                       name="jawaban[C]"
                                       placeholder="Isi jawaban C"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex-shrink-0">
                                <input type="file"
                                       name="gambar_jawaban[C]"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                            </div>
                            <div class="flex-shrink-0">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_benar" value="C" class="form-radio text-primary">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Benar</span>
                                </label>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="hapus-jawaban text-red-600 hover:text-red-800">
                                    <i class="ri-delete-bin-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jawaban Benar/Salah -->
                <div id="jawaban-benar-salah" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Jawaban Benar <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="jawaban_benar_salah" value="T" class="form-radio text-primary">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Benar</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="jawaban_benar_salah" value="F" class="form-radio text-primary">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Salah</span>
                        </label>
                    </div>
                </div>

                <!-- Bobot Benar & Salah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bobot_benar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bobot Benar
                        </label>
                        <input type="number"
                               name="bobot_benar"
                               id="bobot_benar"
                               value="{{ old('bobot_benar', $bankSoal->bobot_benar_default ?? '1.00') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                        @error('bobot_benar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bobot_salah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bobot Salah
                        </label>
                        <input type="number"
                               name="bobot_salah"
                               id="bobot_salah"
                               value="{{ old('bobot_salah', $bankSoal->bobot_salah_default ?? '0.00') }}"
                               step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                        @error('bobot_salah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('guru.pertanyaan_soal.index', $bankSoal->id) }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        CKEDITOR.replace('pertanyaan', {
            height: 200,
            toolbar: [
                { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },
                { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
                { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
            ],
            versionCheck: false
        });
        
        // Fungsi untuk menampilkan/menyembunyikan bagian jawaban berdasarkan jenis soal
        function toggleJawabanSection() {
            const jenisSoal = document.getElementById('jenis_soal').value;
            const jawabanPilihanGanda = document.getElementById('jawaban-pilihan-ganda');
            const jawabanBenarSalah = document.getElementById('jawaban-benar-salah');
            
            // Sembunyikan semua bagian jawaban
            jawabanPilihanGanda.classList.add('hidden');
            jawabanBenarSalah.classList.add('hidden');
            
            // Tampilkan bagian jawaban yang sesuai
            if (jenisSoal === 'pilihan_ganda') {
                jawabanPilihanGanda.classList.remove('hidden');
            } else if (jenisSoal === 'benar_salah') {
                jawabanBenarSalah.classList.remove('hidden');
            }
        }
        
        // Event listener untuk perubahan jenis soal
        document.getElementById('jenis_soal').addEventListener('change', toggleJawabanSection);
        
        // Inisialisasi tampilan awal
        toggleJawabanSection();
        
        // Variabel untuk melacak opsi jawaban yang ada
        let opsiJawaban = ['A', 'B', 'C'];
        
        // Fungsi untuk menambah opsi jawaban
        document.getElementById('tambah-jawaban').addEventListener('click', function() {
            const container = document.getElementById('container-jawaban');
            const currentCount = container.children.length;
            
            // Maksimal 5 opsi (A-E)
            if (currentCount >= 5) {
                alert('Maksimal 5 opsi jawaban (A-E)');
                return;
            }
            
            // Tentukan huruf opsi berikutnya
            const nextOpsi = String.fromCharCode(65 + currentCount); // 65 adalah kode ASCII untuk 'A'
            opsiJawaban.push(nextOpsi);
            
            // Buat elemen opsi jawaban baru
            const newJawaban = document.createElement('div');
            newJawaban.className = 'jawaban-item flex items-center gap-3';
            newJawaban.innerHTML = `
                <div class="flex-shrink-0">
                    <span class="w-8 h-8 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-full text-sm font-medium">${nextOpsi}</span>
                </div>
                <div class="flex-grow">
                    <input type="text"
                           name="jawaban[${nextOpsi}]"
                           placeholder="Isi jawaban ${nextOpsi}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex-shrink-0">
                    <input type="file"
                           name="gambar_jawaban[${nextOpsi}]"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                </div>
                <div class="flex-shrink-0">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_benar" value="${nextOpsi}" class="form-radio text-primary">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Benar</span>
                    </label>
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="hapus-jawaban text-red-600 hover:text-red-800">
                        <i class="ri-delete-bin-line text-xl"></i>
                    </button>
                </div>
            `;
            
            // Tambahkan ke container
            container.appendChild(newJawaban);
            
            // Tambahkan event listener untuk tombol hapus
            newJawaban.querySelector('.hapus-jawaban').addEventListener('click', function() {
                hapusJawaban(this, nextOpsi);
            });
            
            // Perbarui tampilan tombol hapus pada opsi yang ada
            updateHapusButtons();
        });
        
        // Fungsi untuk menghapus opsi jawaban
        function hapusJawaban(button, opsi) {
            const jawabanItem = button.closest('.jawaban-item');
            jawabanItem.remove();
            
            // Hapus dari array opsiJawaban
            const index = opsiJawaban.indexOf(opsi);
            if (index > -1) {
                opsiJawaban.splice(index, 1);
            }
            
            // Perbarui tampilan tombol hapus
            updateHapusButtons();
        }
        
        // Fungsi untuk memperbarui tampilan tombol hapus
        function updateHapusButtons() {
            const jawabanItems = document.querySelectorAll('.jawaban-item');
            const hapusButtons = document.querySelectorAll('.hapus-jawaban');
            
            // Minimal 2 opsi, jadi sembunyikan tombol hapus jika hanya ada 2 opsi
            if (jawabanItems.length <= 2) {
                hapusButtons.forEach(button => button.classList.add('hidden'));
            } else {
                hapusButtons.forEach(button => button.classList.remove('hidden'));
            }
        }
        
        // Tambahkan event listener untuk tombol hapus yang sudah ada
        document.querySelectorAll('.hapus-jawaban').forEach(button => {
            button.addEventListener('click', function() {
                const jawabanItem = this.closest('.jawaban-item');
                const opsiSpan = jawabanItem.querySelector('.bg-gray-200, .dark\\:bg-gray-700');
                const opsi = opsiSpan.textContent.trim();
                hapusJawaban(this, opsi);
            });
        });
    });
</script>
@endpush