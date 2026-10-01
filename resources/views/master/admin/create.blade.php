@extends('layouts.master')

@section('title', 'Pembuatan Akun Admin Seni')

@section('content')
<div class="glass-panel p-6 rounded-2xl mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-headline-md font-headline-md text-primary font-bold">Buat Akun Admin Seni</h1>
            <p class="text-body-md text-on-surface-variant mt-1">Isi formulir di bawah ini untuk membuat akun admin baru.</p>
        </div>
    </div>

    <form action="{{ route('master.admin.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-label-md font-semibold text-on-surface mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Masukkan nama lengkap" required>
                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="email" class="block text-label-md font-semibold text-on-surface mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Masukkan email aktif" required>
                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="password" class="block text-label-md font-semibold text-on-surface mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Masukkan password" required>
                @error('password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-label-md font-semibold text-on-surface mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 rounded-lg bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" placeholder="Ketik ulang password" required>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-outline-variant">
            <a href="{{ route('master.dashboard') }}" class="px-6 py-2.5 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container-high transition-colors font-semibold">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary/90 transition-colors shadow-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Simpan Akun
            </button>
        </div>
    </form>
</div>
@endsection
