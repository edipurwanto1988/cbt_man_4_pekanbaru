@extends('layouts.guru')

@section('title', 'Detail Pertanyaan Soal - ' . $bankSoal->nama_bank)

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Pertanyaan Soal</h1>
            <p class="text-gray-600 dark:text-gray-400">Bank Soal: {{ $bankSoal->nama_bank }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guru.pertanyaan_soal.edit', [$bankSoal->id, $pertanyaanSoal->id]) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-blue-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-blue-700 transition-colors">
                <i class="ri-edit-line text-xl"></i>
                <span class="truncate">Edit</span>
            </a>
            <a href="{{ route('guru.pertanyaan_soal.index', $bankSoal->id) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-200 text-gray-700 text-sm font-bold leading-normal tracking-wide hover:bg-gray-300 transition-colors">
                <i class="ri-arrow-left-line text-xl"></i>
                <span class="truncate">Kembali</span>
            </a>
        </div>
    </header>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Jenis Soal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Jenis Soal
                </label>
                <p>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                        @if($pertanyaanSoal->jenis_soal == 'pilihan_ganda') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @elseif($pertanyaanSoal->jenis_soal == 'esai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                        @else bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 @endif">
                        {{ ucwords(str_replace('_', ' ', $pertanyaanSoal->jenis_soal)) }}
                    </span>
                </p>
            </div>

            <!-- Bobot Benar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Bobot Benar
                </label>
                <p class="text-green-600 font-medium">{{ $pertanyaanSoal->bobot_benar ?? '0.00' }}</p>
            </div>

            <!-- Bobot Salah -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Bobot Salah
                </label>
                <p class="text-red-600 font-medium">{{ $pertanyaanSoal->bobot_salah ?? '0.00' }}</p>
            </div>
        </div>

        <!-- Pertanyaan -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Pertanyaan
            </label>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{!! $pertanyaanSoal->pertanyaan !!}</p>
            </div>
        </div>

        <!-- Gambar Soal -->
        @if($pertanyaanSoal->gambar_soal)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Gambar Soal
                </label>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <img src="{{ asset('uploads/pertanyaan_soals/' . $pertanyaanSoal->gambar_soal) }}" alt="Gambar Soal" class="max-w-full h-auto rounded">
                </div>
            </div>
        @endif

        <!-- Jawaban Soals -->
        @if($pertanyaanSoal->jawabanSoals->count() > 0)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Pilihan Jawaban
                </label>
                <div class="space-y-2">
                    @foreach($pertanyaanSoal->jawabanSoals->sortBy('urutan') as $jawaban)
                        <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium">
                                {{ $jawaban->urutan }}
                            </span>
                            <p class="flex-1 text-gray-900 dark:text-white">{{ $jawaban->isi_jawaban }}</p>
                            @if($jawaban->is_benar)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <i class="ri-check-line mr-1"></i>
                                    Benar
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Timestamps -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                <div>
                    <span class="font-medium">Dibuat:</span> {{ $pertanyaanSoal->created_at->format('d M Y, H:i') }}
                </div>
                <div>
                    <span class="font-medium">Diperbarui:</span> {{ $pertanyaanSoal->updated_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>
@endsection