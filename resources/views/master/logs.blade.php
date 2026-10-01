@extends('layouts.master')

@section('title', 'Activity Logs')

@section('content')
<div class="glass-panel p-6 rounded-2xl">
    <h2 class="font-headline-md text-[24px] font-bold text-on-surface mb-6 border-b border-outline-variant pb-4">Riwayat Aktivitas Admin</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant text-label-sm text-on-surface-variant">
                    <th class="py-4 px-4 font-semibold">Waktu</th>
                    <th class="py-4 px-4 font-semibold">Admin</th>
                    <th class="py-4 px-4 font-semibold">Aktivitas</th>
                    <th class="py-4 px-4 font-semibold">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                @php
                    $isCritical = str_contains($log->activity, '[CRITICAL]');
                @endphp
                <tr class="border-b border-outline-variant/50 {{ $isCritical ? 'bg-error-container/40' : 'hover:bg-surface-variant/30' }} transition-colors">
                    <td class="py-4 px-4 text-sm {{ $isCritical ? 'text-error font-bold' : 'text-on-surface-variant' }}">{{ $log->created_at->format('d M Y, H:i') }}</td>
                    <td class="py-4 px-4 font-medium {{ $isCritical ? 'text-error font-bold' : 'text-on-surface' }}">
                        {{ $log->user->name ?? 'Unknown' }}
                        @if($isCritical)
                            <span class="ml-2 inline-flex items-center gap-1 bg-error text-on-error px-2 py-0.5 rounded text-[10px] uppercase font-bold animate-pulse">
                                <span class="material-symbols-outlined text-[12px]">warning</span> Banned
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-4 {{ $isCritical ? 'text-error font-semibold' : 'text-on-surface' }}">{{ $log->activity }}</td>
                    <td class="py-4 px-4 text-sm {{ $isCritical ? 'text-error font-bold' : 'text-on-surface-variant' }}">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada riwayat aktivitas yang terekam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
