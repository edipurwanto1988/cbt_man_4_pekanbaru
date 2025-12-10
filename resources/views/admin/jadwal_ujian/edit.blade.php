@extends('layouts.admin')

@section('title', 'Edit Sesi Pretest - ' . $session->bankSoal->nama_bank)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Sesi Pretest</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $session->bankSoal->nama_bank }}</p>
        </div>
        <a href="{{ route('admin.jadwal_ujian.show', $session->id) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <i class="ri-arrow-left-line mr-2"></i>Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-line"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center gap-2">
                <i class="ri-alert-line"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <form action="{{ route('admin.jadwal_ujian.update', $session->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Pilih Status --</option>
                    <option value="waiting" {{ $session->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                    <option value="running" {{ $session->status == 'running' ? 'selected' : '' }}>Running</option>
                    <option value="finished" {{ $session->status == 'finished' ? 'selected' : '' }}>Finished</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Informasi Sesi
                </label>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kode Sesi</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $session->kode_sesi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Bank Soal</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $session->bankSoal->nama_bank }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $session->bankSoal->mataPelajaran->nama_mapel ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Peserta</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $session->pesertas->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.jadwal_ujian.show', $session->id) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition">
                    <i class="ri-save-line mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection