{{-- ✅ المسار الصحيح: resources/views/enseignant/CreateS.blade.php --}}
@extends('layouts.enseignant')

@section('content')
<section class="content py-4">
    <div class="container">
        <h1 class="mb-4">Création d'une Séance</h1>

        {{-- ✅ عرض الأخطاء العامة --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card card-default">
            <div class="card-body">
                <form action="{{ route('save.seance') }}" method="POST">
                    @csrf

                    {{-- Matière --}}
                    <div class="form-group">
                        <label for="matiere">Matière</label>
                        <select name="matiere" id="matiere" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            @isset($matieres)
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom_mat }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('matiere')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="form-group">
                        <label for="type_seance">Type</label>
                        <select name="type_seance" id="type_seance" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="cour" {{ old('type_seance') == 'cour' ? 'selected' : '' }}>Cours</option>
                            <option value="TD" {{ old('type_seance') == 'TD' ? 'selected' : '' }}>TD</option>
                            <option value="TP" {{ old('type_seance') == 'TP' ? 'selected' : '' }}>TP</option>
                        </select>
                        @error('type_seance')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control"
                               value="{{ old('date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Heure Début / Fin --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="H_debut">Heure Début</label>
                                <input type="time" name="H_debut" id="H_debut" class="form-control"
                                       value="{{ old('H_debut') }}" required>
                                @error('H_debut')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="H_fin">Heure Fin</label>
                                <input type="time" name="H_fin" id="H_fin" class="form-control"
                                       value="{{ old('H_fin') }}" required>
                                @error('H_fin')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="active" value="1">
                    <input type="hidden" name="id_prof" value="{{ $id_prof ?? auth()->id() }}">

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="{{ route('seances.index') }}" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
