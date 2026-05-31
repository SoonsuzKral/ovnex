{{-- OVNEX 500 Sunucu Hatası --}}
@extends('layouts.app')

@section('title', '500 — Sunucu Hatası')

@section('content')
<div class="absolute inset-0 flex items-center justify-center z-50">
    <div class="text-center">
        <div class="text-8xl font-bold ovnex-red mb-4">500</div>
        <div class="text-xl text-gray-400 mb-6">SİSTEM OVERLOAD</div>
        <p class="text-gray-500 mb-8">İstihbarat ağında kritik bir arıza meydana geldi. Ekibimiz olay yerinde.</p>
        <a href="/" class="px-6 py-3 btn-cyan rounded text-sm transition">YENİDEN BAĞLAN</a>
    </div>
</div>
@endsection
