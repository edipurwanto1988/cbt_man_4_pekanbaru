@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.mata_pelajaran.index') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors" title="Kembali">
            <i class="ri-arrow-left-line text-sm"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Edit Mata Pelajaran</h1>
    </div>
</header>

<!-- Form -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <form action="{{ route('admin.mata_pelajaran.update', $mataPelajaran) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
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
                <label for="nama_mapel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel', $mataPelajaran->nama_mapel) }}" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan nama mata pelajaran" required>
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
                Perbarui
            </button>
        </div>
    </form>
</div>
@endsection