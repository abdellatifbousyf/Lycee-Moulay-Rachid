{{-- ✅ المسار: resources/views/enseignant/listSeance.blade.php --}}
@extends('layouts.enseignant')

@section('content')
<section class="content py-4">
    <div class="container-fluid">

        {{-- 📌 Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Noter les Absences</h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d F Y') }}
                    • {{ strtoupper($seance->type) }}
                    • {{ $seance->matiere->nom_mat ?? 'N/A' }}
                </p>
            </div>
            <a href="{{ route('list.seance') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        {{-- 🔔 Messages d'erreur --}}
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

        {{-- 📝 Formulaire --}}
        <form action="{{ route('save.absence') }}" method="POST" id="absenceForm">
            @csrf
            <input type="hidden" name="id_sea" value="{{ $seance->id }}">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Étudiant(e)</th>
                                    <th scope="col" class="text-center">Présent(e)</th>
                                    <th scope="col" class="text-center">Absent(e)</th>
                                    <th scope="col">Justification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($etudiants as $index => $etudiant)
                                    <tr>
                                        {{-- Numéro --}}
                                        <th scope="row">{{ $index + 1 }}</th>

                                        {{-- Nom & Prénom --}}
                                        <td>
                                            <strong>{{ strtoupper($etudiant->nom_etu) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($etudiant->prenom_etu) }}</small>
                                            {{-- Hidden ID --}}
                                            <input type="hidden" name="absence[{{ $index }}][id_etu]" value="{{ $etudiant->id }}">
                                        </td>

                                        {{-- Présent (value="0") --}}
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="absence[{{ $index }}][etat]"
                                                    id="present_{{ $etudiant->id }}"
                                                    value="0"
                                                    {{ old("absence.$index.etat", '0') == '0' ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label text-success" for="present_{{ $etudiant->id }}">
                                                    <i class="fas fa-check-circle"></i>
                                                </label>
                                            </div>
                                        </td>

                                        {{-- Absent (value="1") --}}
                                        <td class="text-center">
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="absence[{{ $index }}][etat]"
                                                    id="absent_{{ $etudiant->id }}"
                                                    value="1"
                                                    {{ old("absence.$index.etat", '0') == '1' ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label text-danger" for="absent_{{ $etudiant->id }}">
                                                    <i class="fas fa-times-circle"></i>
                                                </label>
                                            </div>
                                        </td>

                                        {{-- Justification --}}
                                        <td>
                                            <select
                                                class="form-control form-control-sm"
                                                name="absence[{{ $index }}][justification]"
                                                id="justif_{{ $etudiant->id }}"
                                            >
                                                <option value="Non Justifié" {{ old("absence.$index.justification", 'Non Justifié') == 'Non Justifié' ? 'selected' : '' }}>
                                                    Non Justifié
                                                </option>
                                                <option value="Justifié" {{ old("absence.$index.justification") == 'Justifié' ? 'selected' : '' }}>
                                                    ✓ Justifié
                                                </option>
                                                <option value="En attente" {{ old("absence.$index.justification") == 'En attente' ? 'selected' : '' }}>
                                                    ⏳ En attente
                                                </option>
                                            </select>
                                            @error("absence.$index.justification")
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-user-graduate fa-2x mb-2 d-block"></i>
                                            Aucun étudiant trouvé pour cette séance.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer avec boutons --}}
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Par défaut: tous les étudiants sont marqués comme <strong>Présents</strong>.
                    </small>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary" id="selectAllPresent">
                            <i class="fas fa-check-double me-1"></i>Tous Présents
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- ✅ Scripts --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Bouton "Tous Présents"
        document.getElementById('selectAllPresent')?.addEventListener('click', function() {
            document.querySelectorAll('input[type="radio"][value="0"]').forEach(radio => {
                radio.checked = true;
            });
        });

        // ✅ Désactiver la justification si l'étudiant est présent
        document.querySelectorAll('input[type="radio"][name*="[etat]"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const index = this.name.match(/\[(\d+)\]/)[1];
                const justifSelect = document.getElementById(`justif_${this.closest('tr').querySelector('input[name*="[id_etu]"]').value}`);

                if (justifSelect) {
                    // Trouver le select correspondant via l'index
                    const allSelects = document.querySelectorAll('select[name*="[justification]"]');
                    if (allSelects[index]) {
                        allSelects[index].disabled = (this.value === '0');
                        if (this.value === '0') {
                            allSelects[index].value = 'Non Justifié';
                        }
                    }
                }
            });
        });

        // ✅ Confirmation avant soumission
        document.getElementById('absenceForm')?.addEventListener('submit', function(e) {
            const absentCount = document.querySelectorAll('input[type="radio"][value="1"]:checked').length;
            if (absentCount > 0) {
                if (!confirm(`Vous avez noté ${absentCount} absence(s). Confirmer l'enregistrement ?`)) {
                    e.preventDefault();
                }
            }
        });

        // ✅ Initialisation: désactiver justifications pour les présents par défaut
        document.querySelectorAll('input[type="radio"][value="0"]:checked').forEach(radio => {
            radio.dispatchEvent(new Event('change'));
        });
    });
</script>
@endpush
@endsection
