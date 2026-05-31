{{-- OVNEX 404 Sayfa Bulunamadı --}}
@extends('layouts.app')

@section('title', '404 — Sayfa Bulunamadı')

@section('content')
<div class="absolute inset-0 flex items-center justify-center z-50">
    <div class="text-center">
        <div class="text-8xl font-bold ovnex-cyan mb-4">404</div>
        <div class="text-xl text-gray-400 mb-6">TAKİP KESİNTİYE UĞRADI</div>
        <p class="text-gray-500 mb-8">Aradığın sinyal menzil dışında. Belki de hiç var olmadı.</p>
        <a href="/" class="px-6 py-3 btn-cyan rounded text-sm transition">ANASAYFAYA DÖN</a>
    </div>
</div>
@endsection
