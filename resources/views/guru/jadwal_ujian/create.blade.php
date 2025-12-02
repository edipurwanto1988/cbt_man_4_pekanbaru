@extends('layouts.guru')

@section('title', 'Buat Sesi Pretest')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Sesi Pretest</h1>
            <p class="text-gray-600 dark:text-gray-400">Pilih bank soal untuk membuat sesi pretest baru</p>
        </div>
        <a href="{{ route('guru.jadwal_ujian.pretest') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
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
        <form action="{{ route('guru.jadwal_ujian.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="bank_soal_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Bank Soal <span class="text-red-500">*</span>
                </label>
                <select id="bank_soal_id" name="bank_soal_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Pilih Bank Soal --</option>
                    @foreach($bankSoals as $bankSoal)
                        <option value="{{ $bankSoal->id }}">{{ $bankSoal->nama_bank }} - {{ $bankSoal->mataPelajaran->nama_mapel ?? '-' }}</option>
                    @endforeach
                </select>
                @error('bank_soal_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <i class="ri-information-line"></i> Sesi pretest akan dibuat untuk semua siswa yang terdaftar pada bank soal yang dipilih.
                </p>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('guru.jadwal_ujian.pretest') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition">
                    <i class="ri-add-line mr-2"></i> Buat Sesi Pretest
                </button>
            </div>
        </form>
    </div>
</div>
@endsection