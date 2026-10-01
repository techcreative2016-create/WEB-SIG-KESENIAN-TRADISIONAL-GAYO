@extends('layouts.master')

@section('title', 'Dashboard Master Admin')

@section('content')
{{-- Stat Cards + Quick Actions --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 rounded-2xl flex items-center gap-6">
        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-[32px] text-primary">admin_panel_settings</span>
        </div>
        <div>
            <h3 class="text-label-md text-on-surface-variant">Total Admin Seni</h3>
            <p class="text-headline-xl font-bold text-on-surface">{{ $totalAdmins }}</p>
        </div>
    </div>

    {{-- Quick Action: Tambah Admin --}}
    <a href="{{ route('master.admin.create') }}"
       class="glass-panel p-6 rounded-2xl flex items-center gap-5 hover:shadow-lg transition-all hover:scale-[1.02] group border-2 border-primary/20 hover:border-primary/50">
        <div class="w-14 h-14 rounded-full bg-primary flex items-center justify-center shadow-md">
            <span class="material-symbols-outlined text-[28px] text-white">person_add</span>
        </div>
        <div>
            <p class="font-bold text-primary text-sm">Tambah Admin Baru</p>
            <p class="text-on-surface-variant text-xs mt-0.5">Buat akun Admin Seni</p>
        </div>
        <span class="material-symbols-outlined text-primary/40 group-hover:text-primary ml-auto transition-colors">arrow_forward</span>
    </a>

    {{-- Quick Action: Statistik Pengunjung --}}
    <a href="{{ route('master.visitor.stats') }}"
       class="glass-panel p-6 rounded-2xl flex items-center gap-5 hover:shadow-lg transition-all hover:scale-[1.02] group border-2 border-secondary/20 hover:border-secondary/50">
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-md" style="background: #E8B931;">
            <span class="material-symbols-outlined text-[28px] text-white">bar_chart</span>
        </div>
        <div>
            <p class="font-bold text-sm" style="color: #B8940C;">Statistik Pengunjung</p>
            <p class="text-on-surface-variant text-xs mt-0.5">Lihat grafik kunjungan</p>
        </div>
        <span class="material-symbols-outlined text-on-surface-variant/40 group-hover:text-on-surface ml-auto transition-colors">arrow_forward</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Tabel Admin -->
    <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
        <h2 class="font-headline-md text-[20px] font-bold text-on-surface mb-6 border-b border-outline-variant pb-4">Daftar Admin Biasa</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant text-label-sm text-on-surface-variant">
                        <th class="py-3 px-4 font-semibold">Nama</th>
                        <th class="py-3 px-4 font-semibold">Username/Email</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr class="border-b border-outline-variant/50 hover:bg-surface-variant/30 transition-colors">
                        <td class="py-4 px-4 font-medium text-on-surface">{{ $admin->name }}</td>
                        <td class="py-4 px-4 text-on-surface-variant text-sm">{{ $admin->username ?? $admin->email }}</td>
                        <td class="py-4 px-4">
                            @if($admin->status === 'active')
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Suspended</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('master.admin.toggle', $admin->id) }}" method="POST">
                                @csrf
                                @if($admin->status === 'active')
                                    <button type="submit" class="px-3 py-1.5 bg-error text-white rounded-lg text-xs font-bold hover:bg-error/90 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menangguhkan admin ini?');">Suspend</button>
                                @else
                                    <button type="submit" class="px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-bold hover:bg-primary/90 transition-colors" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan admin ini?');">Activate</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-on-surface-variant">Belum ada admin biasa terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aktivitas Terkini -->
    <div class="glass-panel p-6 rounded-2xl flex flex-col">
        <div class="flex justify-between items-center mb-6 border-b border-outline-variant pb-4">
            <h2 class="font-headline-md text-[20px] font-bold text-on-surface">Aktivitas Terkini</h2>
            <a href="{{ route('master.logs') }}" class="text-primary text-sm font-semibold hover:underline">Lihat Semua</a>
        </div>
        
        <div class="flex-1 overflow-y-auto pr-2 space-y-4">
            @forelse($recentLogs as $log)
                @php $isCritical = str_contains($log->activity, '[CRITICAL]'); @endphp
                <div class="flex items-start gap-4 p-3 rounded-xl {{ $isCritical ? 'bg-error-container/50 border-error/50' : 'bg-surface-container-low border-outline-variant/30' }} border hover:shadow-sm transition-shadow">
                    <div class="w-10 h-10 rounded-full {{ $isCritical ? 'bg-error animate-pulse' : 'bg-primary/10' }} flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] {{ $isCritical ? 'text-white' : 'text-primary' }}">{{ $isCritical ? 'warning' : 'history' }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium {{ $isCritical ? 'text-error font-bold' : 'text-on-surface' }}">{{ $log->activity }}</p>
                        <p class="text-xs {{ $isCritical ? 'text-error/80' : 'text-on-surface-variant' }} mt-1">Oleh <span class="font-semibold">{{ $log->user->name }}</span> • {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-on-surface-variant/50">
                    <span class="material-symbols-outlined text-[48px] mb-2">history_toggle_off</span>
                    <p class="text-sm">Belum ada aktivitas terekam.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
