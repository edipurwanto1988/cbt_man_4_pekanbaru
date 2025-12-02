@extends('layouts.participant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mulai Ujian: {{ $bankSoal->nama_bank }}</h3>
            </div>

            @if(session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <div class="text-sm text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Exam Information -->
                <div class="overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 mr-3">
                                <i class="ri-file-list-3-line text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Ujian</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Kode Ujian</span>
                                <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $bankSoal->kode_bank }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</span>
                                <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $bankSoal->mataPelajaran->nama_mapel ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tipe</span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $bankSoal->type_test == 'Pretest' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                                    {{ $bankSoal->type_test }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Soal</span>
                                <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $bankSoal->pertanyaanSoals->count() }} soal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Instructions -->
                <div class="overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 mr-3">
                                <i class="ri-alert-line text-amber-600 dark:text-amber-400 text-xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">Instruksi Penting</h4>
                        </div>
                        
                        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                            @if($instruction)
                                <div class="prose prose-sm max-w-none text-amber-800 dark:text-amber-200">
                                    {!! $instruction !!}
                                </div>
                            @else
                                <ul class="space-y-2 text-sm text-amber-800 dark:text-amber-200">
                                    <li class="flex items-start">
                                        <i class="ri-checkbox-circle-line mr-2 mt-0.5 flex-shrink-0"></i>
                                        <span>Pastikan Anda memiliki koneksi internet yang stabil</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="ri-checkbox-circle-line mr-2 mt-0.5 flex-shrink-0"></i>
                                        <span>Sistem menjawab soal per soal secara serentak</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="ri-checkbox-circle-line mr-2 mt-0.5 flex-shrink-0"></i>
                                        <span>Setiap soal memiliki waktu tersendiri</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="ri-checkbox-circle-line mr-2 mt-0.5 flex-shrink-0"></i>
                                        <span>Hubungi support jika mengalami masalah teknis</span>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('participant.exams.take', $bankSoal->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200" onclick="return confirm('Apakah Anda yakin ingin memulai ujian ini?')">
                    <i class="ri-play-line mr-2"></i>
                    Mulai Ujian Sekarang
                </a>
                
                <a href="{{ route('participant.exams.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Kembali ke Daftar Ujian
                </a>
            </div>
        </div>
    </div>
</div>
@endsection