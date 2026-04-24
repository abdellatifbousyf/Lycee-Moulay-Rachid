{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="display-1 text-primary fw-bold">404</h1>
            <h2 class="mb-4">Page non trouvée</h2>
            <p class="lead text-muted mb-4">
                Désolé, la page que vous cherchez n'existe pas ou a été déplacée.
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home me-1"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
