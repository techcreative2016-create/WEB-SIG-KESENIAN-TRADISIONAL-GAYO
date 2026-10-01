@extends('layouts.master')

@section('title', 'Statistik Pengunjung')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Statistik Pengunjung</h1>
            <p class="text-on-surface-variant text-body-md mt-1">Pantau aktivitas pengunjung pada landing page web Anda.</p>
        </div>
    </div>

    <!-- Statistik Kotak (Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Pengunjung Hari Ini -->
        <div class="glass-panel p-6 rounded-2xl flex items-center gap-6 border-l-4 border-primary">
            <div class="w-14 h-14 bg-primary-container text-on-primary-container rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">today</span>
            </div>
            <div>
                <p class="text-label-sm uppercase tracking-widest text-on-surface-variant mb-1">Hari Ini</p>
                <h3 class="text-headline-xl font-headline-xl text-primary">{{ number_format($stats['today']) }}</h3>
            </div>
        </div>

        <!-- Pengunjung Bulan Ini -->
        <div class="glass-panel p-6 rounded-2xl flex items-center gap-6 border-l-4 border-[#0D9373]">
            <div class="w-14 h-14 bg-[#0D9373]/20 text-[#705e00] rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">calendar_month</span>
            </div>
            <div>
                <p class="text-label-sm uppercase tracking-widest text-on-surface-variant mb-1">Bulan Ini</p>
                <h3 class="text-headline-xl font-headline-xl text-primary">{{ number_format($stats['this_month']) }}</h3>
            </div>
        </div>

        <!-- Total Pengunjung -->
        <div class="glass-panel p-6 rounded-2xl flex items-center gap-6 border-l-4 border-[#0D9373]">
            <div class="w-14 h-14 bg-[#0D9373]/10 text-[#0D9373] rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">public</span>
            </div>
            <div>
                <p class="text-label-sm uppercase tracking-widest text-on-surface-variant mb-1">Total Kunjungan</p>
                <h3 class="text-headline-xl font-headline-xl text-primary">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Area Grafik / Log Harian -->
    <div class="glass-panel p-8 rounded-2xl mt-8">
        <h3 class="font-headline-md text-headline-md font-bold text-primary mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">monitoring</span>
            Tren 7 Hari Terakhir
        </h3>
        
        @if($chartData->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#0D9373]/10 text-label-sm text-on-surface-variant uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4 text-right">Jumlah Pengunjung Unik</th>
                    </tr>
                </thead>
                <tbody class="text-body-md">
                    @foreach($chartData as $data)
                    <tr class="border-b border-[#0D9373]/8 hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4 text-primary font-bold">
                            {{ \Carbon\Carbon::parse($data->visited_date)->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="py-4 px-4 text-right">
                            <span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full font-bold text-sm">
                                {{ number_format($data->count) }} Orang
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-10">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/30 mb-4 block">query_stats</span>
            <p class="text-on-surface-variant">Belum ada data kunjungan dalam 7 hari terakhir.</p>
        </div>
        @endif
    </div>
</div>
@endsection
