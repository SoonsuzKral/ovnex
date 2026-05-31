{{-- OVNEX 503 Bakım Modu --}}
@extends('layouts.app')

@section('title', '503 — Bakım')

@section('content')
<div class="absolute inset-0 flex items-center justify-center z-50">
    <div class="text-center">
        <div class="text-8xl font-bold ovnex-orange mb-4">503</div>
        <div class="text-xl text-gray-400 mb-6">SİSTEM GÜNCELLEMEDE</div>
        <p class="text-gray-500 mb-8">Yeni sensörler kalibre ediliyor. Kısa süre içinde yayındayız.</p>
        <div class="flex items-center justify-center gap-2 text-gray-600">
            <span class="w-2 h-2 bg-[#00d4ff] rounded-full animate-pulse"></span>
            <span>Tahmini süre: 15 dakika</span>
        </div>
    </div>
</div>
@endsection
