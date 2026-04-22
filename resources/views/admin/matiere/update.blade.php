{{-- ✅ المسار الصحيح: resources/views/admin/matiere/editMatiere.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier une Matière</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('matieres.index') }}" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Formulaire de modification</h3>
                </div>

                <div class="card-body">
                    {{-- ✅ RESTful Update Method --}}
                    <form action="{{ route('update.matiere', $matiere->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom de la matière <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $matiere->nom_mat) }}" required>
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('filiere') is-invalid @enderror" style="width: 100%;" name="filiere" id="filiere" required>
                                        <option value="">-- Sélectionner --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}" {{ old('filiere', $matiere->id_filiere) == $filiere->id ? 'selected' : '' }}>
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

                            {{-- Professeur --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prof">Professeur responsable</label>
                                    <select class="form-control select2 @error('prof') is-invalid @enderror" style="width: 100%;" name="prof" id="prof">
                                        <option value="">-- Sélectionner --</option>
                                        @isset($profs)
                                            @foreach ($profs as $prof)
                                                <option value="{{ $prof->id }}" {{ old('prof', $matiere->id_ens) == $prof->id ? 'selected' : '' }}>
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

                            {{-- Semestre --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="semestre">Semestre <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('semestre') is-invalid @enderror" style="width: 100%;" name="semestre" id="semestre" required>
                                        <option value="">-- Sélectionner --</option>
                                        @isset($semestres)
                                            @foreach ($semestres as $semestre)
                                                <option value="{{ $semestre->id }}" {{ old('semestre', $matiere->id_sem) == $semestre->id ? 'selected' : '' }}>
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

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
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
        $('.select2').select2();
    });
</script>
@endpush
