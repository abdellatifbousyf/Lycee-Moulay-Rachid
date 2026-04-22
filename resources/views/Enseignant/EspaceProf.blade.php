{{-- ✅ المسار: resources/views/enseignant/EspaceProf.blade.php --}}
@extends('layouts.enseignant')

@section('content')
<section class="content py-4">
    <div class="container">
        {{-- 🎓 قسم الترحيب --}}
        <div class="text-center mb-5">
            <h1 class="display-5">Espace <span class="text-primary">Professeurs</span></h1>
            <p class="lead text-muted mt-3">
                Bienvenue dans votre espace. Utilisez cette application <strong>Gestion des absences</strong>
                pour créer des séances, enregistrer les absences et consulter l'historique des étudiants.
            </p>
        </div>

        {{-- 🛠️ قسم الخدمات --}}
        <div class="row g-4 justify-content-center">
            {{-- 1. Créer une séance --}}
            <div class="col-md-4 col-12">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3 text-primary">
                            <i class="fas fa-calendar-plus fa-3x"></i>
                        </div>
                        <h4 class="card-title mb-3">Créer une séance</h4>
                        <p class="card-text text-muted">
                            Créez une nouvelle séance dans laquelle vous pourrez noter les absences.
                        </p>
                        <a href="{{ route('create.seance') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Commencer
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. Enregistrer les absences --}}
            <div class="col-md-4 col-12">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3 text-success">
                            <i class="fas fa-clipboard-check fa-3x"></i>
                        </div>
                        <h4 class="card-title mb-3">Enregistrer les absences</h4>
                        <p class="card-text text-muted">
                            Enregistrez les absences pour les séances que vous avez créées.
                        </p>
                        <a href="{{ route('list.seance') }}" class="btn btn-success mt-2">
                            <i class="fas fa-list me-1"></i> Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. Consulter l'historique --}}
            <div class="col-md-4 col-12">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3 text-info">
                            <i class="fas fa-history fa-3x"></i>
                        </div>
                        <h4 class="card-title mb-3">Consulter l'historique</h4>
                        <p class="card-text text-muted">
                            Consultez l'historique des absences des étudiants dans votre matière.
                        </p>
                        <a href="{{ route('historique.absence') }}" class="btn btn-info mt-2 text-white">
                            <i class="fas fa-chart-line me-1"></i> Consulter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
