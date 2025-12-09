<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Dashboard - CBT Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Remix Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- Navigation -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <img src="{{ asset('images/logo_clean-min.png') }}" alt="MAN 4 KOTA PEKANBARU" class="h-8 w-8 mr-3">
                <span class="text-xl font-semibold text-gray-900">MAN 4 KOTA PEKANBARU</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, {{ Auth::guard('siswa')->user()->nama_siswa }}</span>
                <form action="{{ route('participant.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Student Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <i class="ri-user-line text-blue-600 mr-2"></i>
                Student Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Student ID (NISN)</p>
                    <p class="text-lg font-medium text-gray-900">{{ Auth::guard('siswa')->user()->nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="text-lg font-medium text-gray-900">{{ Auth::guard('siswa')->user()->nama_siswa }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Gender</p>
                    <p class="text-lg font-medium text-gray-900">
                        {{ Auth::guard('siswa')->user()->jenis_kelamin == 'L' ? 'Male' : 'Female' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <i class="ri-play-circle-line text-green-600 text-3xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Start Exam</h3>
                </div>
                <p class="text-gray-600 mb-4">Begin your computer-based test examination.</p>
                <a href="{{ route('participant.exams.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium transition-colors w-full inline-block text-center">
                    Start Now
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <i class="ri-history-line text-blue-600 text-3xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Exam History</h3>
                </div>
                <p class="text-gray-600 mb-4">View your previous examination results.</p>
                <a href="{{ route('participant.history.index') }}"  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition-colors w-full inline-block text-center">
                    View History
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <i class="ri-settings-3-line text-purple-600 text-3xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">System Check</h3>
                </div>
                <p class="text-gray-600 mb-4">Run system diagnostics before exam.</p>
                <button class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md font-medium transition-colors w-full">
                    Run Check
                </button>
            </div>
        </div>

        <!-- Important Information -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-alert-line text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Make sure you have a stable internet connection</li>
                            <li>Close all unnecessary applications before starting</li>
                            <li>Ensure you have sufficient time to complete the exam</li>
                            <li>Contact support if you encounter any technical issues</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

    
    <!-- Footer with dynamic content from settings -->
    <footer class="w-full max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 text-sm mt-6 text-center text-gray-600">
         Man 4 Pekanbaru©2025 - 2026
    </footer>
</body>
</html>