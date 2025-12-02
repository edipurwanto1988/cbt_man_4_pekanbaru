@extends('layouts.admin')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.tahun_ajaran.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="ri-arrow-left-line text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Tambah Tahun Ajaran Baru</h1>
    </div>
</header>

<!-- Form -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <form action="{{ route('admin.tahun_ajaran.store') }}" method="POST">
        @csrf
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            <!-- Tahun Ajaran Field -->
            <div>
                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <input type="text" id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Contoh: 2024/2025" maxlength="9">
            </div>

            <!-- Semester Field -->
            <div>
                <label for="semester" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Semester <span class="text-red-500">*</span>
                </label>
                <select id="semester" name="semester" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">
                    <option value="">Pilih Semester</option>
                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ganjil</option>
                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <!-- Status Field -->
            <div>
                <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary focus:ring-2 focus:ring-primary">
                    <span>Aktif</span>
                </label>
            </div>

            <!-- Keterangan Field -->
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Keterangan
                </label>
                <textarea id="keterangan" name="keterangan" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Opsional: masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.tahun_ajaran.index') }}"
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                Simpan Tahun Ajaran
            </button>
        </div>
    </form>
</div>
@endsection