@extends('layouts.participant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ujian: {{ $bankSoal->nama_bank }}</h3>
            </div>

            @if(session('error'))
                <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <div class="text-sm text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Question List (Left Sidebar) -->
                <div class="lg:col-span-1 overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative max-w-xs">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Daftar Soal</h4>
                    </div>
                    <div class="p-4 max-h-[calc(100vh-250px)] overflow-y-auto">
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($questions as $index => $question)
                                <div class="question-item p-3 rounded-lg border cursor-pointer transition-colors duration-200 {{ $loop->first ? 'bg-primary/10 border-primary' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                                     data-question-id="{{ $question->id }}"
                                     data-index="{{ $index + 1 }}">
                                    <div class="flex items-center justify-center">
                                        <span class="question-number w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium {{ $loop->first ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Question Area (Main Content) -->
                <div class="lg:col-span-3 overflow-visible rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 relative">
                    <div class="p-6">
                        <!-- Timer -->
                        <div class="mb-6 flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                Sisa Waktu
                            </div>
                            <div class="timer text-lg font-semibold text-red-600 dark:text-red-400">
                                <span id="minutes">00</span>:<span id="seconds">00</span>
                            </div>
                        </div>
                        
                        <!-- Elapsed Time -->
                        <div class="mb-6 flex justify-between items-center">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                Waktu Terpakai
                            </div>
                            <div class="elapsed-time text-lg font-semibold text-blue-600 dark:text-blue-400">
                                <span id="elapsed-minutes">00</span>:<span id="elapsed-seconds">00</span>
                            </div>
                        </div>

                        <!-- Question Content -->
                        <div id="question-container" class="mb-8">
                            @if($questions->count() > 0)
                                @php
                                    $currentQuestion = $questions->first();
                                    $answers = $currentQuestion->jawabanSoals->pluck('isi_jawaban', 'opsi');
                                @endphp
                                
                                <div class="question-content" data-question-id="{{ $currentQuestion->id }}">
                                    <div class="mb-6">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                            Soal 1
                                        </h4>
                                        <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                                            {!! $currentQuestion->pertanyaan !!}
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach($answers as $option => $text)
                                            <label class="answer-option flex items-start p-4 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                                <input type="radio" name="answer" value="{{ $option }}" class="mt-1 mr-3">
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $option }}.</span>
                                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $text }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        Tidak ada soal yang tersedia
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between">
                            <button id="prev-btn" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200" disabled>
                                <i class="ri-arrow-left-line mr-2"></i>
                                Sebelumnya
                            </button>
                            
                            <button id="next-btn" class="px-4 py-2 border border-transparent text-white bg-primary hover:bg-primary/90 rounded-md disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                Selanjutnya
                                <i class="ri-arrow-right-line ml-2"></i>
                            </button>
                            
                            <button id="finish-btn" class="hidden px-4 py-2 border border-transparent text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-200">
                                Selesai
                                <i class="ri-check-line ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="exam-form" method="POST" action="{{ route('participant.exams.submitPosttest', $bankSoal->id) }}">
    @csrf
    <input type="hidden" id="current-question-id" name="current_question_id" value="{{ $questions->first()->id ?? '' }}">
    <input type="hidden" id="answers-data" name="answers" value="">
    <input type="hidden" id="durations-data" name="durations" value="">
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = @json($questions);
    let currentQuestionIndex = 0;
    let answers = {};
    let durations = {};
    let timerInterval;
    let saveInterval;
    let startTime = Date.now();
    const examDuration = {{ $bankSoal->durasi_menit ?? 60 }}; // Default 60 minutes if not set
    const bankSoalId = {{ $bankSoal->id }};
    
    // Initialize timer
    startTimer();
    
    // Initialize elapsed time
    updateElapsedTime();
    
    // Start saving duration every 5 seconds
    startSaveDuration();
    
    // Question item click handler
    document.querySelectorAll('.question-item').forEach(item => {
        item.addEventListener('click', function() {
            const questionId = parseInt(this.dataset.questionId);
            const index = questions.findIndex(q => q.id === questionId);
            if (index !== -1) {
                switchToQuestion(index);
            }
        });
    });
    
    // Answer option change handler
    document.querySelectorAll('input[name="answer"]').forEach(input => {
        input.addEventListener('change', function() {
            const questionId = document.getElementById('current-question-id').value;
            answers[questionId] = this.value;
            
            // Record when this question was first answered
            if (!durations[questionId]) {
                durations[questionId] = Math.floor((Date.now() - startTime) / 1000);
            }
            
            // Auto-save answer to server
            autoSaveAnswer(questionId, this.value, durations[questionId]);
            
            // Update question item status
            updateQuestionItemStatus(questionId, true);
            
            // Enable next button
            document.getElementById('next-btn').disabled = false;
        });
    });
    
    // Previous button click handler
    document.getElementById('prev-btn').addEventListener('click', function() {
        if (currentQuestionIndex > 0) {
            switchToQuestion(currentQuestionIndex - 1);
        }
    });
    
    // Next button click handler
    document.getElementById('next-btn').addEventListener('click', function() {
        if (currentQuestionIndex < questions.length - 1) {
            switchToQuestion(currentQuestionIndex + 1);
        }
    });
    
    // Finish button click handler
    document.getElementById('finish-btn').addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menyelesaikan ujian?')) {
            submitExam();
        }
    });
    
    function switchToQuestion(index) {
        currentQuestionIndex = index;
        const question = questions[index];
        
        // Update current question ID
        document.getElementById('current-question-id').value = question.id;
        
        // Update question content
        const questionContainer = document.getElementById('question-container');
        questionContainer.innerHTML = `
            <div class="question-content" data-question-id="${question.id}">
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Soal ${index + 1}
                    </h4>
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                        ${question.pertanyaan}
                    </div>
                </div>

                <div class="space-y-3">
                    ${question.jawaban_soals.map(answer => `
                        <label class="answer-option flex items-start p-4 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                            <input type="radio" name="answer" value="${answer.opsi}" class="mt-1 mr-3" ${answers[question.id] === answer.opsi ? 'checked' : ''}>
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 dark:text-white">${answer.opsi}.</span>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">${answer.isi_jawaban}</span>
                            </div>
                        </label>
                    `).join('')}
                </div>
            </div>
        `;
        
        // Re-attach answer change handler
        questionContainer.querySelectorAll('input[name="answer"]').forEach(input => {
            input.addEventListener('change', function() {
                const questionId = document.getElementById('current-question-id').value;
                answers[questionId] = this.value;
                
                // Record when this question was first answered
                if (!durations[questionId]) {
                    durations[questionId] = Math.floor((Date.now() - startTime) / 1000);
                }
                
                // Auto-save answer to server
                autoSaveAnswer(questionId, this.value, durations[questionId]);
                
                // Update question item status
                updateQuestionItemStatus(questionId, true);
                
                // Enable next button
                document.getElementById('next-btn').disabled = false;
            });
        });
        
        // Update question items
        document.querySelectorAll('.question-item').forEach((item, i) => {
            const questionNumber = item.querySelector('.question-number');
            const questionText = item.querySelector('span:last-child');
            
            if (i === index) {
                item.className = 'question-item p-3 rounded-lg border cursor-pointer transition-colors duration-200 bg-primary/10 border-primary';
                questionNumber.className = 'question-number w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium bg-primary text-white';
            } else {
                item.className = 'question-item p-3 rounded-lg border cursor-pointer transition-colors duration-200 bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700';
                questionNumber.className = 'question-number w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
            }
        });
        
        // Update navigation buttons
        document.getElementById('prev-btn').disabled = index === 0;
        
        if (index === questions.length - 1) {
            document.getElementById('next-btn').classList.add('hidden');
            document.getElementById('finish-btn').classList.remove('hidden');
        } else {
            document.getElementById('next-btn').classList.remove('hidden');
            document.getElementById('finish-btn').classList.add('hidden');
            
            // Disable next button if current question hasn't been answered
            document.getElementById('next-btn').disabled = !answers[question.id];
        }
    }
    
    function updateQuestionItemStatus(questionId, answered) {
        const questionItem = document.querySelector(`.question-item[data-question-id="${questionId}"]`);
        if (questionItem && answered) {
            const questionNumber = questionItem.querySelector('.question-number');
            questionNumber.classList.add('bg-green-500', 'text-white');
            questionNumber.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'bg-primary', 'text-white');
        }
    }
    
    function startTimer() {
        let timeLeft = examDuration * 60; // Convert minutes to seconds
        
        timerInterval = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitExam();
            }
        }, 1000);
    }
    
    function updateElapsedTime() {
        // Calculate elapsed time as: (total duration in seconds) - (remaining time)
        const totalDurationSeconds = {{ $bankSoal->durasi_menit ?? 60 }} * 60; // Convert minutes to seconds
        const elapsedSeconds = Math.max(0, totalDurationSeconds - remainingTime);
        
        const hours = Math.floor(elapsedSeconds / 3600);
        const minutes = Math.floor((elapsedSeconds % 3600) / 60);
        const seconds = elapsedSeconds % 60;
        
        // Update elapsed time display with hours, minutes, and seconds
        const elapsedHoursElement = document.getElementById('elapsed-hours');
        const elapsedMinutesElement = document.getElementById('elapsed-minutes');
        const elapsedSecondsElement = document.getElementById('elapsed-seconds');
        
        // Create hours element if it doesn't exist
        if (!elapsedHoursElement) {
            const elapsedTimeElement = document.querySelector('.elapsed-time');
            elapsedTimeElement.innerHTML = '<span id="elapsed-hours">00</span>:<span id="elapsed-minutes">00</span>:<span id="elapsed-seconds">00</span>';
        }
        
        // Update the elements
        document.getElementById('elapsed-hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('elapsed-minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('elapsed-seconds').textContent = seconds.toString().padStart(2, '0');
    }
    
    function startSaveDuration() {
        // Update elapsed time every second
        setInterval(() => {
            updateElapsedTime();
        }, 1000);
        
        // Save duration data every 5 seconds
        saveInterval = setInterval(() => {
            saveDurationData();
        }, 5000);
    }
    
    // Also update elapsed time when remaining time is updated
    const originalUpdateTimerDisplay = updateTimerDisplay;
    updateTimerDisplay = function() {
        originalUpdateTimerDisplay();
        updateElapsedTime();
    };
    
    function autoSaveAnswer(questionId, answer, duration) {
        // Save answer to server via AJAX
        fetch(`/participant/exams/auto-save-answer/${bankSoalId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                question_id: questionId,
                answer: answer,
                duration: duration
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Answer saved successfully');
                // Show a small notification
                showAutoSaveNotification();
            } else {
                console.error('Failed to save answer:', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving answer:', error);
        });
    }

    function showAutoSaveNotification() {
        // Remove any existing notification
        const existingNotification = document.getElementById('auto-save-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'auto-save-notification';
        notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg transition-opacity duration-300 z-50';
        notification.textContent = 'Jawaban otomatis disimpan';
        
        // Add to page
        document.body.appendChild(notification);
        
        // Fade out after 2 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 2000);
    }

    function saveDurationData() {
        // Update durations for all answered questions
        const currentTime = Math.floor((Date.now() - startTime) / 1000);
        
        // Update duration for current question if it has been answered
        const currentQuestionId = document.getElementById('current-question-id').value;
        if (answers[currentQuestionId]) {
            // If question has been answered but duration not recorded, record it now
            if (!durations[currentQuestionId]) {
                durations[currentQuestionId] = currentTime;
            }
        }
        
        // Save durations to server via AJAX
        fetch(`/participant/exams/save-duration/${bankSoalId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                durations: durations
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Duration data saved successfully');
            } else {
                console.error('Failed to save duration data:', data.message);
            }
        })
        .catch(error => {
            console.error('Error saving duration data:', error);
        });
    }
    
    function submitExam() {
        // Stop timer and save interval
        clearInterval(timerInterval);
        clearInterval(saveInterval);
        
        // Update durations for all answered questions one last time
        const currentTime = Math.floor((Date.now() - startTime) / 1000);
        
        // Make sure all answered questions have duration recorded
        Object.keys(answers).forEach(questionId => {
            if (!durations[questionId]) {
                durations[questionId] = currentTime;
            }
        });
        
        // Store answers and durations data
        document.getElementById('answers-data').value = JSON.stringify(answers);
        document.getElementById('durations-data').value = JSON.stringify(durations);
        
        // Submit form
        document.getElementById('exam-form').submit();
    }
});

// Timer functionality for updating remaining time in database
let remainingTime = {{ $remainingTime }};
let updateTimerInterval;

// Validate remainingTime is a number and not negative
if (isNaN(remainingTime) || remainingTime === null || remainingTime === undefined || remainingTime < 0) {
    // Fallback to exam duration if remainingTime is invalid or negative
    remainingTime = {{ $bankSoal->durasi_menit ?? 60 }} * 60;
}

// Update remaining time in database
function updateRemainingTimeInDb() {
    if (remainingTime <= 0) {
        clearInterval(updateTimerInterval);
        showTimeUpMessage();
        return;
    }
    
    fetch(`/participant/exams/update-remaining-time/{{ $bankSoal->id }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            remaining_time: remainingTime
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            remainingTime = data.remaining_time;
            
            // Check if time is up
            if (remainingTime <= 0) {
                clearInterval(updateTimerInterval);
                showTimeUpMessage();
                return;
            }
            
            updateTimerDisplay();
        } else {
            console.error('Failed to update remaining time:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating remaining time:', error);
    });
}

// Update the timer display
function updateTimerDisplay() {
    // Validate remainingTime before calculating
    if (isNaN(remainingTime) || remainingTime === null || remainingTime === undefined || remainingTime < 0) {
        remainingTime = 0;
        clearInterval(updateTimerInterval);
        showTimeUpMessage();
        return;
    }
    
    // Use the actual remaining time from database (already in seconds)
    const totalSeconds = Math.max(0, remainingTime);
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    
    const formattedTime =
        String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0');
    
    // Update the timer display if it exists
    const timerElement = document.querySelector('.timer');
    if (timerElement) {
        timerElement.innerHTML = `<span id="hours">${String(hours).padStart(2, '0')}</span>:<span id="minutes">${String(minutes).padStart(2, '0')}</span>:<span id="seconds">${String(seconds).padStart(2, '0')}</span>`;
    }
}

// Show message when time is up
function showTimeUpMessage() {
    const container = document.querySelector('.container-fluid');
    container.innerHTML = `
        <div class="container">
            <div class="alert alert-danger text-center">
                <h3>Waktu Ujian Habis</h3>
                <p>Waktu ujian Anda telah habis. Silakan kembali ke dashboard.</p>
                <a href="{{ route('participant.exams.index') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            </div>
        </div>
    `;
}

// Initialize timer update functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if time is already up
    if (remainingTime <= 0) {
        showTimeUpMessage();
        return;
    }
    
    // Initialize timer display
    updateTimerDisplay();
    
    // Update remaining time in database every 3 seconds
    updateTimerInterval = setInterval(updateRemainingTimeInDb, 3000);
});
</script>
@endsection