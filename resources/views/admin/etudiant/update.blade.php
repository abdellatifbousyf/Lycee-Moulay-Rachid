{{-- ✅ المسار الصحيح: resources/views/admin/etudiants/update.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier un étudiant</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('etudiants.index') }}" class="btn btn-secondary float-sm-right">
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
                    <h3 class="card-title">Formulaire de modification</h3>
                </div>

                <div class="card-body">
                    {{-- ✅ الفورم خاصها تكون خارج الـ form-groups و تلف كلشي --}}
                    <form action="{{ route('update.student', $etudiant->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- ✅ PUT هو المعيار الرسمي للتحديث فـ Laravel --}}

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

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        <div class="row">
                            {{-- 👈 CNE --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cne">CNE</label>
                                    <input type="text" name="cne" id="cne"
                                           class="form-control @error('cne') is-invalid @enderror"
                                           value="{{ old('cne', $etudiant->cne) }}" required>
                                    @error('cne')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Nom (تم تصحيح الخلط بين Nom و Prenom) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom_etu">Nom</label>
                                    <input type="text" name="nom_etu" id="nom_etu"
                                           class="form-control @error('nom_etu') is-invalid @enderror"
                                           value="{{ old('nom_etu', $etudiant->nom_etu) }}" required>
                                    @error('nom_etu')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Prénom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom_etu">Prénom</label>
                                    <input type="text" name="prenom_etu" id="prenom_etu"
                                           class="form-control @error('prenom_etu') is-invalid @enderror"
                                           value="{{ old('prenom_etu', $etudiant->prenom_etu) }}" required>
                                    @error('prenom_etu')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Téléphone --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_etu">Téléphone</label>
                                    <input type="tel" name="phone_etu" id="phone_etu"
                                           class="form-control @error('phone_etu') is-invalid @enderror"
                                           value="{{ old('phone_etu', $etudiant->phone_etu) }}"
                                           pattern="[0-9]{10}" placeholder="06XXXXXXXX">
                                    @error('phone_etu')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière</label>
                                    <select class="form-control select2" name="id_filiere" id="filiere" style="width: 100%;" required>
                                        <option value="">-- Sélectionner une filière --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}"
                                                    {{ old('id_filiere', $etudiant->id_filiere) == $filiere->id ? 'selected' : '' }}>
                                                    {{ $filiere->nom_filiere }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('id_filiere')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
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
