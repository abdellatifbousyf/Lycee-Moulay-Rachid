{{-- ✅ المسار الصحيح: resources/views/admin/matiere/addMatiere.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ajouter une Matière</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('matieres.index') }}" class="btn btn-secondary float-sm-right">
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
                    <form action="{{ route('save.matiere') }}" method="POST">
                        @csrf

                        {{-- ✅ عرض الأخطاء العامة --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            {{-- 👈 Nom de la matière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom de la matière <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="nom"
                                        id="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom') }}"
                                        placeholder="Ex: Mathématiques, Physique..."
                                        required
                                    >
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 @error('filiere') is-invalid @enderror"
                                        style="width: 100%;"
                                        name="filiere"
                                        id="filiere"
                                        required
                                    >
                                        <option value="">-- Sélectionner une filière --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option
                                                    value="{{ $filiere->id }}"
                                                    {{ old('filiere') == $filiere->id ? 'selected' : '' }}
                                                >
                                                    {{ $filiere->nom_filiere }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('filiere')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Professeur responsable --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prof">Professeur responsable</label>
                                    <select
                                        class="form-control select2 @error('prof') is-invalid @enderror"
                                        style="width: 100%;"
                                        name="prof"
                                        id="prof"
                                    >
                                        <option value="">-- Sélectionner un professeur --</option>
                                        @isset($profs)
                                            @foreach ($profs as $prof)
                                                <option
                                                    value="{{ $prof->id }}"
                                                    {{ old('prof') == $prof->id ? 'selected' : '' }}
                                                >
                                                    {{ $prof->nom_ens }} {{ $prof->prenom_ens }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('prof')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Semestre --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="semestre">Semestre <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 @error('semestre') is-invalid @enderror"
                                        style="width: 100%;"
                                        name="semestre"
                                        id="semestre"
                                        required
                                    >
                                        <option value="">-- Sélectionner un semestre --</option>
                                        @isset($semestres)
                                            @foreach ($semestres as $semestre)
                                                <option
                                                    value="{{ $semestre->id }}"
                                                    {{ old('semestre') == $semestre->id ? 'selected' : '' }}
                                                >
                                                    {{ $semestre->nom_sem }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('semestre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ✅ أزرار الحفظ والإلغاء --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Ajouter
                            </button>
                            <a href="{{ route('matieres.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ تهيئة Select2 --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Sélectionner une option',
            allowClear: true,
            language: 'fr'
        });
    });
</script>
@endpush
