@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran - ' . $rombel->nama_rombel)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rombel.mapel', $rombel->id) }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <i class="ri-arrow-left-line"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Mata Pelajaran</h1>
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

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.rombel.mapel.update', [$rombel->id, $rombelMapel->id]) }}" method="POST" class="p-6">
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
                <!-- Mata Pelajaran -->
                <div>
                    <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-gray-800 dark:text-white"
                        required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mataPelajaran as $mapel)
                            <option value="{{ $mapel->id }}" {{ $mapel->id == $rombelMapel->mata_pelajaran_id ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.rombel.mapel', $rombel->id) }}" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection