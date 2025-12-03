@extends('layouts.participant')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-8">
                    <!-- Success Icon -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                            <i class="ri-check-line text-4xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Ujian Selesai!
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Terima kasih telah menyelesaikan ujian: <strong>{{ $bankSoal->nama_bank }}</strong>
                        </p>
                    </div>

                    <!-- Exam Summary -->
                    <div class="border-t border-b border-gray-200 dark:border-gray-700 py-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Total Questions -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary mb-2">
                                    {{ $totalQuestions }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Total Soal
                                </div>
                            </div>

                            <!-- Answered Questions -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                                    {{ $answeredQuestions }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Soal Dijawab
                                </div>
                            </div>

                            <!-- Time Spent -->
                            <div class="text-center">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                    {{ $timeSpent }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Waktu Terpakai
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Information Message -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="ri-information-line text-blue-600 dark:text-blue-400 text-xl mr-3 mt-0.5"></i>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">
                                    Informasi Penting
                                </h4>
                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                    Jawaban Anda telah tersimpan dengan aman. Hasil ujian akan diumumkan setelah proses penilaian selesai.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Details -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Detail Pengumpulan
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tanggal & Waktu:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ now()->format('d M Y, H:i') }} WIB
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Nama Ujian:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $bankSoal->nama_bank }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <i class="ri-check-line mr-1"></i>
                                    Sudah Dikumpulkan
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('participant.exams.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 transition-colors duration-200">
                            <i class="ri-dashboard-line mr-2"></i>
                            Kembali ke Dashboard
                        </a>
                        
                        @if(isset($resultUrl))
                        <a href="{{ $resultUrl }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <i class="ri-file-list-3-line mr-2"></i>
                            Lihat Hasil
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Optional: Feedback Section -->
                <div class="mt-6 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Berikan Feedback (Opsional)
                    </h4>
                    <form action="{{ route('participant.exams.feedback', $bankSoal->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bagaimana pengalaman Anda dengan ujian ini?
                            </label>
                            <textarea id="feedback" 
                                      name="feedback" 
                                      rows="4" 
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Tulis feedback Anda di sini..."></textarea>
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90 transition-colors duration-200">
                            <i class="ri-send-plane-line mr-2"></i>
                            Kirim Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection