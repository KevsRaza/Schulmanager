@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4">
    <div class="card">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Détails du dossier #{{ $id }}</h1>
            <p class="text-gray-600">Cette page affichera les détails complets du dossier.</p>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour au dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection