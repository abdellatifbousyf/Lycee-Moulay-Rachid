{{-- ✅ المسار: resources/views/admin/matiere/addMatiere.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ajouter une Matière</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Formulaire d'ajout</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('save.matiere') }}" method="POST">
                        @csrf

                        {{-- ✅ عرض الأخطاء --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="row">
                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom de la matière</label>
                                    <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Filière</label>
                                    <select class="form-control select2" style="width: 100%;" name="filiere" required>
                                        <option value="">-- Sélectionner --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}" {{ old('filiere') == $filiere->id ? 'selected' : '' }}>
                                                    {{ $filiere->nom_filiere }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('filiere')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Prof --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Professeur</label>
                                    <select class="form-control select2" style="width: 100%;" name="prof">
                                        <option value="">-- Sélectionner --</option>
                                        @isset($profs)
                                            @foreach ($profs as $prof)
                                                <option value="{{ $prof->id }}" {{ old('prof') == $prof->id ? 'selected' : '' }}>
                                                    {{ $prof->nom_ens }} {{ $prof->prenom_ens }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('prof')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Semestre --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Semestre</label>
                                    <select class="form-control select2" style="width: 100%;" name="semestre" required>
                                        <option value="">-- Sélectionner --</option>
                                        @isset($semestres)
                                            @foreach ($semestres as $semestre)
                                                <option value="{{ $semestre->id }}" {{ old('semestre') == $semestre->id ? 'selected' : '' }}>
                                                    {{ $semestre->nom_sem }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('semestre')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Ajouter</button>
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
