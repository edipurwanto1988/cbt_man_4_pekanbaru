@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.admins.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Detail Pengguna</h1>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.admins.edit', $admin) }}" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793A2 2 0 0116 4.414V11a2 2 0 01-2 2H6a2 2 0 01-2-2V4.414a2 2 0 01.586-1.414l3.171-3.171a1 1 0 011.414 0l3.415 3.415z"/>
            </svg>
            Edit
        </a>
        @if(Auth::guard('admin')->id() !== $admin->id)
            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center" onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $admin->name }}?')">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Hapus
                </button>
            </form>
        @endif
    </div>
</header>

<!-- User Details -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <div class="flex items-center gap-6 mb-6">
        <div class="h-24 w-24 rounded-full bg-primary/10 flex items-center justify-center">
            <span class="text-primary text-3xl font-bold">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ $admin->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded-full 
                @if($admin->role === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                @elseif($admin->role === 'guru') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @endif">
                {{ ucfirst($admin->role) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Akun</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">ID</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $admin->id }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Email</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $admin->email }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Role</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ ucfirst($admin->role) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Email Verified</span>
                    <span class="text-gray-900 dark:text-white font-medium">
                        {{ $admin->email_verified_at ? $admin->email_verified_at->format('d M Y H:i') : 'Belum' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Waktu</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Dibuat</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $admin->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Diperbarui</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $admin->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection