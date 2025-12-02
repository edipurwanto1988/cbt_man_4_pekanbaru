@extends('layouts.admin')

@section('title', 'Edit Siswa')

@section('content')
<!-- PageHeading -->
<header class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.siswa.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="ri-arrow-left-line text-xl"></i>
        </a>
        <h1 class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Edit Siswa</h1>
    </div>
</header>

<!-- Form -->
<div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
    <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            <!-- NISN Field -->
            <div>
                <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    NISN <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan NISN" maxlength="20">
                @error('nisn')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Siswa Field -->
            <div>
                <label for="nama_siswa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Siswa <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan nama lengkap siswa">
                @error('nama_siswa')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin Field -->
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select id="jenis_kelamin" name="jenis_kelamin" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $siswa->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan email (opsional)">
                @error('email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Password <span class="text-gray-500 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Masukkan password minimal 6 karakter" minlength="6">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Siswa Info -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Siswa</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">NISN:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->nisn }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Dibuat:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->created_at->format('d M Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Diperbarui:</span>
                    <span class="ml-2 text-gray-900 dark:text-white">{{ $siswa->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('admin.siswa.index') }}" 
                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Batal
            </a>
            <button type="submit" 
                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                Update Siswa
            </button>
        </div>
    </form>
</div>
@endsection