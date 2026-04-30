//- ✅ المسار: resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Tableau de bord') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            {{-- 🔔 رسائل الجلسة --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            {{-- 👋 بطاقة الترحيب --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>👋 {{ __('Bienvenue') }}, <strong>{{ auth()->user()->name }}</strong></h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-shield-alt"></i> {{ auth()->user()->role?->type ?? 'Administrateur' }}
                                • {{ now()->translatedFormat('l j F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 📊 إحصائيات سريعة (AdminLTE Small Boxes) --}}
            <div class="row">
                {{-- Étudiants --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['etudiants'] ?? 0 }}</h3>
                            <p>{{ __('Étudiants') }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-graduate"></i></div>
                        <a href="{{ route('etudiants.index') }}" class="small-box-footer">
                            {{ __('Voir la liste') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Enseignants --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['enseignants'] ?? 0 }}</h3>
                            <p>{{ __('Enseignants') }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <a href="{{ route('enseignants.index') }}" class="small-box-footer">
                            {{ __('Voir la liste') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Matières --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['matieres'] ?? 0 }}</h3>
                            <p>{{ __('Matières') }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-book"></i></div>
                        <a href="{{ route('matieres.index') }}" class="small-box-footer">
                            {{ __('Voir la liste') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Absences Aujourd'hui --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['absences_today'] ?? 0 }}</h3>
                            <p>{{ __('Absences aujourd\'hui') }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-times"></i></div>
                        <a href="{{ route('absences.index') }}" class="small-box-footer">
                            {{ __('Gérer') }} <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 🚀 روابط سريعة --}}
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bolt text-warning mr-2"></i> {{ __('Accès rapide') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('add.student') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-user-plus"></i> {{ __('Ajouter étudiant') }}
                                    </a>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('add.prof') }}" class="btn btn-outline-success btn-block">
                                        <i class="fas fa-chalkboard-teacher"></i> {{ __('Ajouter enseignant') }}
                                    </a>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('add.matiere') }}" class="btn btn-outline-warning btn-block">
                                        <i class="fas fa-book-open"></i> {{ __('Ajouter matière') }}
                                    </a>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <a href="{{ route('absences.create') }}" class="btn btn-outline-danger btn-block">
                                        <i class="fas fa-clipboard-check"></i> {{ __('Saisir absence') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

</body>
</html>
