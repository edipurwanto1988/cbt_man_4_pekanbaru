@extends('layouts.admin')

@section('title', 'Edit Rombel')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rombel.index') }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Rombel</h1>
            <p class="text-gray-600 dark:text-gray-400">Perbarui data rombongan belajar</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <form action="{{ route('admin.rombel.update', $rombel->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ri-alert-line"></i>
                        <strong>Terjadi kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tahun Ajaran -->
            <div>
                <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <select id="tahun_ajaran_id" 
                        name="tahun_ajaran_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $rombel->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tingkat Kelas -->
            <div>
                <label for="tingkat_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tingkat Kelas <span class="text-red-500">*</span>
                </label>
                <select id="tingkat_id" 
                        name="tingkat_id" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Tingkat Kelas</option>
                    @foreach($tingkatKelas as $tk)
                        <option value="{{ $tk->id }}" {{ old('tingkat_id', $rombel->tingkat_id) == $tk->id ? 'selected' : '' }}>
                            {{ $tk->nama }}
                        </option>
                    @endforeach
                </select>
                @error('tingkat_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kode Kelas -->
            <div>
                <label for="kode_kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kode Kelas <span class="text-red-500">*</span>
                </label>
                <select id="kode_kelas" 
                        name="kode_kelas" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Kode Kelas</option>
                    <option value="A" {{ old('kode_kelas', $rombel->kode_kelas) == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('kode_kelas', $rombel->kode_kelas) == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('kode_kelas', $rombel->kode_kelas) == 'C' ? 'selected' : '' }}>C</option>
                    <option value="D" {{ old('kode_kelas', $rombel->kode_kelas) == 'D' ? 'selected' : '' }}>D</option>
                </select>
                @error('kode_kelas')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Rombel -->
            <div>
                <label for="nama_rombel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Rombel <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nama_rombel" 
                       name="nama_rombel" 
                       value="{{ old('nama_rombel', $rombel->nama_rombel) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white"
                       placeholder="Contoh: X IPA 1, XI IPS 2, XII MIPA 3">
                @error('nama_rombel')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Wali Kelas -->
            <div>
                <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Wali Kelas
                </label>
                <select id="wali_kelas_id" 
                        name="wali_kelas_id" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary dark:bg-gray-700 dark:text-white">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($guru as $g)
                        <option value="{{ $g->id_guru }}" {{ old('wali_kelas_id', $rombel->wali_kelas_id) == $g->id_guru ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
                @error('wali_kelas_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika belum ada wali kelas</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.rombel.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <i class="ri-save-line"></i>
                    Perbarui Rombel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection