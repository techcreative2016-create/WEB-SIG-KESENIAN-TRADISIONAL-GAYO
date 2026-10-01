@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Pengaturan Akun')

@section('content')
    <div class="max-w-4xl">
        <h1 class="font-headline-lg text-headline-lg font-bold text-on-surface mb-2">Pengaturan Akun</h1>
        <p class="text-body-md text-on-surface-variant mb-8">Kelola informasi profil dan keamanan akun Anda di platform Admin Seni dan Budaya Gayo.</p>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
            <!-- Tabs Header -->
            <div class="flex border-b border-outline-variant">
                <button onclick="switchTab('profil')" id="tab-profil" class="flex-1 py-4 font-label-md text-label-md text-primary border-b-2 border-primary bg-primary/5 transition-all">Ubah Profil</button>
                <button onclick="switchTab('password')" id="tab-password" class="flex-1 py-4 font-label-md text-label-md text-on-surface-variant border-b-2 border-transparent hover:bg-surface-container-low transition-all">Ubah Kata Sandi</button>
            </div>

            <!-- Tab Content: Profil -->
            <div id="content-profil" class="p-8">
                <form method="post" action="{{ route(auth()->user()->role === 'master' ? 'master.profile.update' : 'admin.profile.update') }}" class="flex flex-col md:flex-row gap-8">
                    @csrf
                    @method('patch')

                    <!-- Photo Section -->
                    <div class="flex flex-col items-center gap-4 w-full md:w-1/4">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-surface-container shadow-sm relative group cursor-pointer">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHA0OejV3FVaJo7xg0c_WGy2dG3OGrslWOESSFo3Jk9gQdgLYER-3bo92v68XTNf_v9n_htN-6Wis1--bE3x6g4i0hMHr0iHoEHZf5lvM72iNfPA1YoiDwW53X1nBLzlcCZapxwvKuU6h61pjuHTv9tuHtKpG5pYGouJZIbus-mqX9mHroaMBnReeSH_s7_aPrCvTzlCB3uWNuVU02lWjaj2BtM9rAprHQmj3wIeaHzvvnICUHTTSqLuByyenEtA-blc4CHqOW3XUU" alt="Profile" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-white">photo_camera</span>
                            </div>
                        </div>
                        <button type="button" class="text-primary font-label-sm text-label-sm hover:underline">Ganti Foto</button>
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nama Lengkap</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" required autofocus autocomplete="name" />
                                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="username" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nama Pengguna</label>
                                <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="Min. 8 huruf/angka" autocomplete="username" />
                                @error('username')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all opacity-70 cursor-not-allowed" readonly autocomplete="username" />
                                <p class="text-[10px] text-on-surface-variant/70 mt-1 italic">Email tidak dapat diubah secara mandiri.</p>
                                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Nomor Telepon (WhatsApp)</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="+62..." />
                                @error('phone')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="region" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Wilayah / Kota</label>
                            <select id="region" name="region" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all appearance-none">
                                <option value="Bener Meriah" selected>Kabupaten Bener Meriah</option>
                                <option value="Takengon">Takengon</option>
                                <option value="Gayo Lues">Gayo Lues</option>
                            </select>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-outline-variant">
                            <button type="submit" class="px-6 py-2 bg-primary text-on-primary font-label-md text-label-md rounded-lg shadow-sm hover:bg-primary-container active:scale-95 transition-all">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab Content: Password -->
            <div id="content-password" class="p-8 hidden">
                <form method="post" action="{{ route('password.update') }}" class="max-w-xl mx-auto space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Kata Sandi Saat Ini</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" autocomplete="current-password" required />
                        @error('current_password', 'updatePassword')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Kata Sandi Baru</label>
                        <input id="update_password_password" name="password" type="password" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" autocomplete="new-password" required />
                        @error('password', 'updatePassword')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" autocomplete="new-password" required />
                        @error('password_confirmation', 'updatePassword')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end pt-4 border-t border-outline-variant">
                        <button type="submit" class="px-6 py-2 bg-primary text-on-primary font-label-md text-label-md rounded-lg shadow-sm hover:bg-primary-container active:scale-95 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function switchTab(tab) {
            const tabProfil = document.getElementById('tab-profil');
            const tabPassword = document.getElementById('tab-password');
            const contentProfil = document.getElementById('content-profil');
            const contentPassword = document.getElementById('content-password');

            if (tab === 'profil') {
                tabProfil.className = 'flex-1 py-4 font-label-md text-label-md text-primary border-b-2 border-primary bg-primary/5 transition-all';
                tabPassword.className = 'flex-1 py-4 font-label-md text-label-md text-on-surface-variant border-b-2 border-transparent hover:bg-surface-container-low transition-all';
                contentProfil.classList.remove('hidden');
                contentPassword.classList.add('hidden');
            } else {
                tabPassword.className = 'flex-1 py-4 font-label-md text-label-md text-primary border-b-2 border-primary bg-primary/5 transition-all';
                tabProfil.className = 'flex-1 py-4 font-label-md text-label-md text-on-surface-variant border-b-2 border-transparent hover:bg-surface-container-low transition-all';
                contentPassword.classList.remove('hidden');
                contentProfil.classList.add('hidden');
            }
        }

        // Show specific tab if there are errors from updating password
        @if($errors->updatePassword->isNotEmpty())
            switchTab('password');
        @endif
    </script>
    @endpush
@endsection
