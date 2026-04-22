{{-- ✅ المسار: resources/views/enseignant/HistoriqueAbsence.blade.php --}}
@extends('layouts.enseignant')

@section('content')
<section class="content py-4">
    <div class="container-fluid">

        {{-- 📌 Header with Title & Back Button --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Historique des Absences</h1>
            <a href="{{ route('list.seance') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        {{-- 🔍 Filters Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    {{-- Matière Filter --}}
                    <div class="col-md-4">
                        <label class="form-label">Matière</label>
                        <select class="form-control select2" id="matiereFilter" data-placeholder="Toutes les matières">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom_mat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filière Filter --}}
                    <div class="col-md-4">
                        <label class="form-label">Filière</label>
                        <select class="form-control select2" id="filiereFilter" data-placeholder="Toutes les filières">
                            <option value="">Toutes les filières</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ request('filiere') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Filter --}}
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="dateFilter" value="{{ request('date') }}">
                    </div>
                </div>

                {{-- Filter Actions --}}
                <div class="mt-3">
                    <button id="applyFilters" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('historique.absence') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </div>

        {{-- 📊 Table Card --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="absencesTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>État</th>
                                <th>Étudiant(e)</th>
                                <th>Date</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Type</th>
                                <th>Matière</th>
                                <th>Filière</th>
                                <th>Semestre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absences as $absence)
                                {{-- ✅ Data attributes for client-side filtering --}}
                                <tr data-matiere="{{ $absence->seance->matiere->id ?? '' }}"
                                    data-filiere="{{ $absence->seance->matiere->filiere->id ?? '' }}"
                                    data-date="{{ $absence->seance->date ?? '' }}">

                                    {{-- État --}}
                                    <td>
                                        @if($absence->etat)
                                            <span class="badge bg-danger">Absent(e)</span>
                                        @else
                                            <span class="badge bg-success">Présent(e)</span>
                                        @endif
                                    </td>

                                    {{-- Étudiant --}}
                                    <td>
                                        {{ $absence->etudiant->nom_etu ?? 'N/A' }}
                                        {{ $absence->etudiant->prenom_etu ?? '' }}
                                    </td>

                                    {{-- Date & Times --}}
                                    <td>{{ $absence->seance->date ?? 'N/A' }}</td>
                                    <td>{{ $absence->seance->heure_debut ?? 'N/A' }}</td>
                                    <td>{{ $absence->seance->heure_fin ?? 'N/A' }}</td>

                                    {{-- Type --}}
                                    <td>
                                        <span class="badge bg-info">
                                            {{ strtoupper($absence->seance->type ?? '') }}
                                        </span>
                                    </td>

                                    {{-- Matière / Filière / Semestre --}}
                                    <td>{{ $absence->seance->matiere->nom_mat ?? 'N/A' }}</td>
                                    <td>{{ $absence->seance->matiere->filiere->nom_filiere ?? 'N/A' }}</td>
                                    <td>{{ $absence->seance->matiere->semestre->nom_sem ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Aucune absence trouvée pour les critères sélectionnés.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 📄 Pagination --}}
                @if($absences instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-3">
                        {{ $absences->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ✅ Scripts --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                language: 'fr'
            });
        }

        // Filter button: Server-side filtering (recommended for large datasets)
        document.getElementById('applyFilters')?.addEventListener('click', function() {
            const matiere = document.getElementById('matiereFilter').value;
            const filiere = document.getElementById('filiereFilter').value;
            const date = document.getElementById('dateFilter').value;

            const params = new URLSearchParams();
            if (matiere) params.append('matiere', matiere);
            if (filiere) params.append('filiere', filiere);
            if (date) params.append('date', date);

            window.location.href = '{{ route('historique.absence') }}?' + params.toString();
        });

        // Optional: Real-time client-side filtering (for small datasets only)
        const filters = {
            matiere: document.getElementById('matiereFilter'),
            filiere: document.getElementById('filiereFilter'),
            date: document.getElementById('dateFilter')
        };

        function applyClientFilters() {
            const matiereVal = filters.matiere?.value || '';
            const filiereVal = filters.filiere?.value || '';
            const dateVal = filters.date?.value || '';

            document.querySelectorAll('#absencesTable tbody tr').forEach(row => {
                if (!row.dataset.matiere) return; // Skip empty row

                const matchMatiere = !matiereVal || row.dataset.matiere === matiereVal;
                const matchFiliere = !filiereVal || row.dataset.filiere === filiereVal;
                const matchDate = !dateVal || row.dataset.date === dateVal;

                row.style.display = (matchMatiere && matchFiliere && matchDate) ? '' : 'none';
            });
        }

        // Attach event listeners for real-time filtering
        Object.values(filters).forEach(filter => {
            filter?.addEventListener('change', applyClientFilters);
        });
    });
</script>
@endpush
@endsection
