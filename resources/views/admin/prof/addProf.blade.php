{{-- ✅ المسار الصحيح: resources/views/admin/prof/addProf.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ajouter un Enseignant</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('enseignants.index') }}" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Formulaire d'ajout</h3>
                </div>

                <div class="card-body">
                    {{-- ✅ الفورم تلف جميع الحقول بشكل صحيح --}}
                    <form action="{{ route('save.prof') }}" method="POST">
                        @csrf

                        {{-- ✅ عرض الأخطاء العامة --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        <div class="row">
                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom"
                                           class="form-control @error('nom') is-invalid @enderror"
                                           value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Prénom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" id="prenom"
                                           class="form-control @error('prenom') is-invalid @enderror"
                                           value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Adresse --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" name="adresse" id="adresse"
                                           class="form-control @error('adresse') is-invalid @enderror"
                                           value="{{ old('adresse') }}">
                                    @error('adresse')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Téléphone --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tel">Numéro de Téléphone</label>
                                    <input type="tel" name="tel" id="tel"
                                           class="form-control @error('tel') is-invalid @enderror"
                                           value="{{ old('tel') }}"
                                           pattern="[0-9]{10}" placeholder="06XXXXXXXX">
                                    @error('tel')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
