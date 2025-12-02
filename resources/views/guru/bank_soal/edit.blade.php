@extends('layouts.guru')

@section('title', 'Edit Bank Soal')

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Edit Bank Soal</h1>
        <a href="{{ route('guru.bank_soal.index') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-gray-700 transition-colors">
            <i class="ri-arrow-left-line text-xl"></i>
            <span class="truncate">Kembali</span>
        </a>
    </header>

    <!-- Form -->
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
        <form action="{{ route('guru.bank_soal.update', $bankSoal->id) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Kode Bank -->
                <div>
                    <label for="kode_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode Bank <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_bank" id="kode_bank" value="{{ old('kode_bank', $bankSoal->kode_bank) }}" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                        placeholder="Contoh: BK001">
                    @error('kode_bank')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type Test -->
                <div>
                    <label for="type_test" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Type Test <span class="text-red-500">*</span>
                    </label>
                    <select name="type_test" id="type_test" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                        <option value="">Pilih Type Test</option>
                        <option value="pretest" {{ old('type_test', $bankSoal->type_test) == 'pretest' ? 'selected' : '' }}>Pretest</option>
                        <option value="posttest" {{ old('type_test', $bankSoal->type_test) == 'posttest' ? 'selected' : '' }}>Posttest</option>
                    </select>
                    @error('type_test')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $tahunAjaran)
                            <option value="{{ $tahunAjaran->id }}" {{ old('tahun_ajaran_id', $bankSoal->tahun_ajaran_id) == $tahunAjaran->id ? 'selected' : '' }}>
                                {{ $tahunAjaran->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="mapel_id" id="mapel_id" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajarans as $mataPelajaran)
                            <option value="{{ $mataPelajaran->id }}" {{ old('mapel_id', $bankSoal->mapel_id) == $mataPelajaran->id ? 'selected' : '' }}>
                                {{ $mataPelajaran->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                    @error('mapel_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengawas -->
                <div>
                    <label for="pengawas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Pengawas
                    </label>
                    <select name="pengawas_id" id="pengawas_id"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                        <option value="">Pilih Pengawas (Opsional)</option>
                        @foreach ($gurus as $guru)
                            <option value="{{ $guru->id_guru }}" {{ old('pengawas_id', $bankSoal->pengawas_id) == $guru->id_guru ? 'selected' : '' }}>
                                {{ $guru->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                    @error('pengawas_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rombel -->
                <div>
                    <label for="rombel_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Rombel
                    </label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto dark:border-gray-700 dark:bg-gray-900">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Pilih Rombel</p>
                        <div class="space-y-2">
                            @foreach ($rombels as $rombel)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" name="rombel_ids[]" value="{{ $rombel->id }}"
                                        {{ in_array($rombel->id, $selectedRombels ?? []) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800 dark:focus:ring-primary">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $rombel->nama_rombel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @error('rombel_ids')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Nama Bank -->
            <div>
                <label for="nama_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Bank <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $bankSoal->nama_bank) }}" required
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                    placeholder="Contoh: Ujian Matematika Semester 1">
                @error('nama_bank')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $bankSoal->tanggal_mulai ? $bankSoal->tanggal_mulai->format('Y-m-d\TH:i') : '') }}"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tanggal Selesai
                    </label>
                    <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $bankSoal->tanggal_selesai ? $bankSoal->tanggal_selesai->format('Y-m-d\TH:i') : '') }}"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi Menit -->
                <div>
                    <label for="durasi_menit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Durasi (menit)
                    </label>
                    <input type="number" name="durasi_menit" id="durasi_menit" value="{{ old('durasi_menit', $bankSoal->durasi_menit) }}" min="1"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                    @error('durasi_menit')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Time -->
                <div>
                    <label for="max_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Max Time (detik per soal)
                    </label>
                    <input type="number" name="max_time" id="max_time" value="{{ old('max_time', $bankSoal->max_time) }}" min="1"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary"
                        placeholder="Contoh: 30">
                    @error('max_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Waktu maksimal per soal dalam detik</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Bobot Benar Default -->
                <div>
                    <label for="bobot_benar_default" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Bobot Benar Default
                    </label>
                    <input type="number" name="bobot_benar_default" id="bobot_benar_default" value="{{ old('bobot_benar_default', $bankSoal->bobot_benar_default) }}" step="0.01" min="0"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                    @error('bobot_benar_default')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bobot Salah Default -->
                <div>
                    <label for="bobot_salah_default" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Bobot Salah Default
                    </label>
                    <input type="number" name="bobot_salah_default" id="bobot_salah_default" value="{{ old('bobot_salah_default', $bankSoal->bobot_salah_default) }}" step="0.01"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                    @error('bobot_salah_default')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-primary dark:focus:ring-primary">
                        <option value="">Pilih Status</option>
                        <option value="draft" {{ old('status', $bankSoal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="aktif" {{ old('status', $bankSoal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ old('status', $bankSoal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('guru.bank_soal.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    <i class="ri-save-line"></i>
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const typeTestSelect = document.getElementById('type_test');
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
        const durasiMenitInput = document.getElementById('durasi_menit');
        const maxTimeInput = document.getElementById('max_time');
        const bobotBenarInput = document.getElementById('bobot_benar_default');
        const bobotSalahInput = document.getElementById('bobot_salah_default');
        
        // Function to toggle field states based on type test
        function toggleFieldsBasedOnTypeTest() {
            const typeTestValue = typeTestSelect.value;
            
            if (typeTestValue === 'pretest') {
                // Disable fields for pretest
                tanggalMulaiInput.disabled = true;
                tanggalSelesaiInput.disabled = true;
                durasiMenitInput.disabled = true;
                // Keep max_time enabled for pretest
                bobotBenarInput.disabled = true;
                bobotSalahInput.disabled = true;
                
                // Add visual indication that fields are disabled
                tanggalMulaiInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                tanggalSelesaiInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                durasiMenitInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                // Don't add disabled styling to max_time
                bobotBenarInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                bobotSalahInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                // Enable fields for other types
                tanggalMulaiInput.disabled = false;
                tanggalSelesaiInput.disabled = false;
                durasiMenitInput.disabled = false;
                maxTimeInput.disabled = false;
                bobotBenarInput.disabled = false;
                bobotSalahInput.disabled = false;
                
                // Remove visual indication
                tanggalMulaiInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                tanggalSelesaiInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                durasiMenitInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                maxTimeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                bobotBenarInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                bobotSalahInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }
        
        // Initial call to set correct state on page load
        toggleFieldsBasedOnTypeTest();
        
        // Add event listener for type test changes
        typeTestSelect.addEventListener('change', toggleFieldsBasedOnTypeTest);
    });
</script>
@endpush