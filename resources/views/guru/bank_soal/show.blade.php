@extends('layouts.guru')

@section('title', 'Detail Bank Soal')

@section('content')
    <!-- PageHeading -->
    <header class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Bank Soal</h1>
        <div class="flex gap-3">
            <a href="{{ route('guru.bank_soal.edit', $bankSoal->id) }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-blue-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-blue-700 transition-colors">
                <i class="ri-edit-line text-xl"></i>
                <span class="truncate">Edit</span>
            </a>
            <a href="{{ route('guru.bank_soal.index') }}" class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-600 text-white text-sm font-bold leading-normal tracking-wide hover:bg-gray-700 transition-colors">
                <i class="ri-arrow-left-line text-xl"></i>
                <span class="truncate">Kembali</span>
            </a>
        </div>
    </header>

    <!-- Detail Card -->
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Kode Bank -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kode Bank</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->kode_bank }}</p>
                </div>

                <!-- Type Test -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Type Test</h3>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bankSoal->type_test == 'pretest' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                        {{ ucfirst($bankSoal->type_test) }}
                    </span>
                </div>

                <!-- Tahun Ajaran -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tahun Ajaran</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->tahunAjaran->tahun_ajaran }}</p>
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Mata Pelajaran</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->mataPelajaran->nama_mapel }}</p>
                </div>

                <!-- Nama Bank -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Bank</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->nama_bank }}</p>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</h3>
                    <p class="text-base text-gray-900 dark:text-white">
                        {{ $bankSoal->tanggal_mulai ? $bankSoal->tanggal_mulai->format('d M Y H:i') : '-' }}
                    </p>
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Selesai</h3>
                    <p class="text-base text-gray-900 dark:text-white">
                        {{ $bankSoal->tanggal_selesai ? $bankSoal->tanggal_selesai->format('d M Y H:i') : '-' }}
                    </p>
                </div>

                <!-- Durasi -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Durasi</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->durasi_menit }} menit</p>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</h3>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bankSoal->status == 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' : ($bankSoal->status == 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300') }}">
                        {{ ucfirst($bankSoal->status) }}
                    </span>
                </div>

                <!-- Bobot Benar Default -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bobot Benar Default</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->bobot_benar_default }}</p>
                </div>

                <!-- Bobot Salah Default -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bobot Salah Default</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->bobot_salah_default }}</p>
                </div>

                <!-- Dibuat Oleh -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Oleh</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->creator->nama_guru }}</p>
                </div>

                <!-- Pengawas -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pengawas</h3>
                    <p class="text-base text-gray-900 dark:text-white">
                        {{ $bankSoal->pengawas ? $bankSoal->pengawas->nama_guru : '-' }}
                    </p>
                </div>

                <!-- Dibuat Pada -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Pada</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->created_at->format('d M Y H:i') }}</p>
                </div>

                <!-- Diperbarui Pada -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Diperbarui Pada</h3>
                    <p class="text-base text-gray-900 dark:text-white">{{ $bankSoal->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection