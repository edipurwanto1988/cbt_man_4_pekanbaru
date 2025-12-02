@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.mata_pelajaran.index') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" title="Kembali">
            <i class="ri-arrow-left-line text-sm"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Tambah Mata Pelajaran</h1>
    </div>
</header>

<!-- Rombel Info -->
@if($rombel)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                <i class="ri-group-line text-blue-600 dark:text-blue-300"></i>
            </div>
            <div>
                <p class="text-sm text-blue-600 dark:text-blue-400">Menambah untuk Rombel</p>
                <p class="font-medium text-blue-800 dark:text-blue-200">{{ $rombel->nama_rombel }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Form -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <form action="{{ route('admin.mata_pelajaran.store') }}" method="POST" class="p-6">
        @csrf
        @if($rombel)
            <input type="hidden" name="rombel_id" value="{{ $rombel->id }}">
        @endif
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-6">
            <!-- Nama Mata Pelajaran -->
            <div>
                <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <select id="mata_pelajaran_id" name="mata_pelajaran_id"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white"
                    required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach(\App\Models\MataPelajaran::all() as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.mata_pelajaran.index') }}" 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Batal
            </a>
            <button type="submit" 
                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                @if($rombel) 'Tambah ke Rombel' @else 'Simpan' @endif
            </button>
        </div>
    </form>
</div>
@endsection
