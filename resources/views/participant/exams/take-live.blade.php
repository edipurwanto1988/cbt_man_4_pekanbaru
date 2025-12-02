@extends('layouts.participant')

@section('title', 'Live Pretest - ' . $pretestSession->bankSoal->nama_bank)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pretestSession->bankSoal->nama_bank }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Soal <span id="currentStep">1</span>/<span id="totalQuestions">{{ $pretestSession->bankSoal->pertanyaanSoals->count() }}</span></p>
            </div>

            <!-- Question Display -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-8 mb-6">
                <div id="questionContainer">
                    @if($currentQuestion)
                        @php
                            $answers = [];
                            foreach($currentQuestion->jawabanSoals as $jawaban) {
                                $answers[$jawaban->opsi] = $jawaban->isi_jawaban;
                            }
                        @endphp
                        <div class="question-text text-lg font-medium text-gray-900 dark:text-white mb-6">
                            {!! $currentQuestion->pertanyaan !!}
                        </div>
                        
                        <div class="space-y-3" id="answersContainer">
                            @if(isset($answers['A']) && $answers['A'])
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="A">
                                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">A</span>
                                <span class="text-gray-900 dark:text-white">{!! $answers['A'] !!}</span>
                            </div>
                            @endif
                            
                            @if(isset($answers['B']) && $answers['B'])
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="B">
                                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">B</span>
                                <span class="text-gray-900 dark:text-white">{!! $answers['B'] !!}</span>
                            </div>
                            @endif
                            
                            @if(isset($answers['C']) && $answers['C'])
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="C">
                                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">C</span>
                                <span class="text-gray-900 dark:text-white">{!! $answers['C'] !!}</span>
                            </div>
                            @endif
                            
                            @if(isset($answers['D']) && $answers['D'])
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="D">
                                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">D</span>
                                <span class="text-gray-900 dark:text-white">{!! $answers['D'] !!}</span>
                            </div>
                            @endif
                            
                            @if(isset($answers['E']) && $answers['E'])
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="E">
                                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">E</span>
                                <span class="text-gray-900 dark:text-white">{!! $answers['E'] !!}</span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="ri-loader-4-line animate-spin text-4xl text-blue-600 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">Menunggu soal dari guru...</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Message -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 mr-3">
                        <i class="ri-information-line text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Pretest Sedang Berlangsung
                        </p>
                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                            Soal akan muncul secara otomatis ketika guru menampilkan soal berikutnya.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
    line-height: 1.5;
}

.alert-success {
    background-color: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
}

.alert-danger {
    background-color: #fee2e2;
    border: 1px solid #ef4444;
    color: #991b1b;
}

.alert strong {
    display: block;
    margin-bottom: 4px;
    font-size: 16px;
}

.alert-warning {
    background-color: #fef3c7;
    border: 1px solid #fbbf24;
    color: #92400e;
}

</style>

<script>
let updateInterval;
let sessionId = {{ $pretestSession->id }};
let selectedAnswer = null;
let hasAnsweredCurrentQuestion = false;
let currentQuestionId = null;
let questionStartTime = null;
let questionTimer = null;
let questionMaxTime = 30; // Default, will be updated from server

// Load current question
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to answer options
    document.querySelectorAll('.answer-option').forEach(option => {
        option.addEventListener('click', function() {
            if (!hasAnsweredCurrentQuestion) {
                selectAnswer(this.dataset.option);
            }
        });
    });
    
    // Start auto-update
    updateInterval = setInterval(loadCurrentQuestion, 2000);
});

function selectAnswer(option) {
    selectedAnswer = option;
    hasAnsweredCurrentQuestion = true;
    
    // Calculate time taken for this question
    const currentTime = new Date().getTime();
    const timeTaken = questionStartTime ? (currentTime - questionStartTime) / 1000 : 0; // in seconds
    
    // Update UI to show selected answer
    document.querySelectorAll('.answer-option').forEach(el => {
        el.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
        el.classList.add('bg-gray-50', 'dark:bg-gray-700');
        // Remove hover effect and make non-clickable after selection
        el.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-600', 'cursor-pointer');
        el.classList.add('cursor-not-allowed', 'opacity-75');
        el.style.pointerEvents = 'none';
    });
    
    const selectedElement = document.querySelector(`[data-option="${option}"]`);
    selectedElement.classList.remove('bg-gray-50', 'dark:bg-gray-700', 'cursor-not-allowed', 'opacity-75');
    selectedElement.classList.add('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
    selectedElement.style.pointerEvents = 'auto';
    
    // Submit answer with time data
    submitAnswer(option, timeTaken);
}

function submitAnswer(option, timeTaken) {
    fetch(`/participant/exams/submit-answer/${sessionId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            answer: option,
            time_taken: timeTaken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Answer submitted successfully');
            console.log('Bonus points earned:', data.bonus_points);
            
            // Display bonus information to student
            const container = document.getElementById('questionContainer');
            const existingAlert = container.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alertDiv = document.createElement('div');
            if (data.is_timeout) {
                alertDiv.className = 'alert alert-warning mt-4';
                alertDiv.innerHTML = `
                    <strong>Waktu Habis!</strong><br>
                    Tidak ada jawaban yang dipilih.<br>
                    Poin: 0 (Tidak menjawab)
                `;
            } else if (data.is_correct) {
                alertDiv.className = 'alert alert-success mt-4';
                alertDiv.innerHTML = `
                    <strong>Benar!</strong><br>
                    Poin Dasar: ${data.base_score}<br>
                    Bonus Kecepatan: ${data.bonus_points} poin<br>
                    Waktu: ${data.time_taken} detik / ${data.max_time} detik<br>
                    <strong>Total Poin: ${data.total_score}</strong>
                `;
            } else {
                alertDiv.className = 'alert alert-danger mt-4';
                alertDiv.innerHTML = `
                    <strong>Salah!</strong><br>
                    Poin Dasar: ${data.base_score}<br>
                    Bonus Kecepatan: 0 poin<br>
                    <strong>Total Poin: ${data.total_score}</strong>
                `;
            }
            container.appendChild(alertDiv);
            
            // Disable all answer options after submission
            document.querySelectorAll('.answer-option').forEach(option => {
                option.style.pointerEvents = 'none';
                option.style.opacity = '0.6';
            });
            
        } else {
            console.error('Error submitting answer:', data.message);
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
    });
}

function loadCurrentQuestion() {
    fetch(`{{ route('participant.exams.take-live.question', $pretestSession->id) }}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.question) {
                // Only update if it's a new question
                if (data.question.id !== currentQuestionId) {
                    currentQuestionId = data.question.id;
                    displayQuestion(data.question);
                    updateStepInfo(data.step, data.total_questions);
                }
            }
            
            // Check if session is finished
            if (data.session_status === 'finished') {
                window.location.href = `/participant/exams/results/${sessionId}`;
            }
        } else {
            console.error('Error loading question:', data.error);
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
    });
}

function displayQuestion(question) {
    const container = document.getElementById('questionContainer');
    container.innerHTML = `
        <div class="question-text text-lg font-medium text-gray-900 dark:text-white mb-6">
            ${question.pertanyaan}
        </div>
        
        <div class="space-y-3" id="answersContainer">
            ${question.opsi_a ? `<div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="A">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">A</span>
                <span class="text-gray-900 dark:text-white">${question.opsi_a}</span>
            </div>` : ''}
            
            ${question.opsi_b ? `<div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="B">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">B</span>
                <span class="text-gray-900 dark:text-white">${question.opsi_b}</span>
            </div>` : ''}
            
            ${question.opsi_c ? `<div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="C">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">C</span>
                <span class="text-gray-900 dark:text-white">${question.opsi_c}</span>
            </div>` : ''}
            
            ${question.opsi_d ? `<div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="D">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">D</span>
                <span class="text-gray-900 dark:text-white">${question.opsi_d}</span>
            </div>` : ''}
            
            ${question.opsi_e ? `<div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition-colors answer-option" data-option="E">
                <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold mr-3">E</span>
                <span class="text-gray-900 dark:text-white">${question.opsi_e}</span>
            </div>` : ''}
        </div>
    `;
    
    // Re-add click handlers
    document.querySelectorAll('.answer-option').forEach(option => {
        option.addEventListener('click', function() {
            if (!hasAnsweredCurrentQuestion) {
                selectAnswer(this.dataset.option);
            }
        });
    });
    
    // Reset selected answer and answered state for new question
    selectedAnswer = null;
    hasAnsweredCurrentQuestion = false;
    
    // Clear any existing timer
    if (questionTimer) {
        clearInterval(questionTimer);
        questionTimer = null;
    }
    
    // Record the start time for this new question
    questionStartTime = new Date().getTime();
    
    // Set max time from question data or use default
    questionMaxTime = question.max_time || 30;
    
    // Start timer for tracking (timer is controlled by teacher, not visible to students)
    startQuestionTimer();
}

function updateStepInfo(step, total) {
    document.getElementById('currentStep').textContent = step;
    document.getElementById('totalQuestions').textContent = total;
}

function startQuestionTimer() {
    // Timer is controlled by teacher, not visible to students
    // Only track time for bonus calculation
    questionStartTime = new Date().getTime();
}

function submitTimeoutAnswer() {
    hasAnsweredCurrentQuestion = true;
    
    // Calculate time taken (should be equal to max_time)
    const currentTime = new Date().getTime();
    const timeTaken = questionStartTime ? (currentTime - questionStartTime) / 1000 : questionMaxTime;
    
    // Submit timeout answer with empty answer
    fetch(`/participant/exams/submit-answer/${sessionId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            answer: '',
            time_taken: timeTaken,
            is_timeout: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Timeout answer submitted successfully');
            
            // Display timeout message
            const container = document.getElementById('questionContainer');
            const existingAlert = container.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning mt-4';
            alertDiv.innerHTML = `
                <strong>Waktu Habis!</strong><br>
                Tidak ada jawaban yang dipilih.<br>
                Poin: 0 (Tidak menjawab)
            `;
            container.appendChild(alertDiv);
            
            // Disable all answer options
            document.querySelectorAll('.answer-option').forEach(option => {
                option.style.pointerEvents = 'none';
                option.style.opacity = '0.6';
            });
        } else {
            console.error('Error submitting timeout answer:', data.message);
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
    });
}

// Clean up intervals when page is unloaded
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
    if (questionTimer) {
        clearInterval(questionTimer);
    }
});
</script>
@endsection