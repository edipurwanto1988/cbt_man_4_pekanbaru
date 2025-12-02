@extends('layouts.admin')

@section('title', 'Detail Guru')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.guru.index') }}" 
           class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Guru</h1>
            <p class="text-gray-600 dark:text-gray-400">Informasi lengkap data guru</p>
        </div>
    </div>

    <!-- Guru Details Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="p-6">
            <!-- Profile Section -->
            <div class="flex items-start gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tie text-4xl text-primary"></i>
                    </div>
                </div>
                
                <!-- Info -->
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $guru->nama_guru }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-id-badge text-gray-400 w-4"></i>
                            <span class="text-gray-600 dark:text-gray-400">ID Guru:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $guru->id_guru }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-id-card text-gray-400 w-4"></i>
                            <span class="text-gray-600 dark:text-gray-400">NIK:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $guru->nik ?? '-' }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-gray-400 w-4"></i>
                            <span class="text-gray-600 dark:text-gray-400">Email:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $guru->email ?? '-' }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar text-gray-400 w-4"></i>
                            <span class="text-gray-600 dark:text-gray-400">Dibuat:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $guru->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-gray-400 w-4"></i>
                            <span class="text-gray-600 dark:text-gray-400">Diperbarui:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $guru->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.guru.edit', $guru->id_guru) }}" 
                   class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit Guru
                </a>
                
                <form action="{{ route('admin.guru.destroy', $guru->id_guru) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-trash"></i>
                        Hapus Guru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Tambahan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Kelas</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-book text-green-600 dark:text-green-300"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 dark:text-purple-300"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Siswa</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-orange-600 dark:text-orange-300"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jadwal Aktif</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection