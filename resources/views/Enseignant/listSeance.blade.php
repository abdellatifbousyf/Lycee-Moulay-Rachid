{{-- ✅ المسار: resources/views/enseignant/listSeance.blade.php --}}
@extends('layouts.enseignant')

@section('content')
<section class="content py-4">
    <div class="container-fluid">

        {{-- 📌 Header with Title & Add Button --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Liste des Séances</h1>
            <a href="{{ route('create.seance') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nouvelle Séance
            </a>
        </div>

        {{-- 🔔 Messages d'alerte --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- 📊 Table Card --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Matière</th>
                                <th scope="col">Date</th>
                                <th scope="col">Début</th>
                                <th scope="col">Fin</th>
                                <th scope="col">Type</th>
                                <th scope="col">État</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($seances as $index => $seance)
                                <tr>
                                    {{-- Numéro de ligne avec pagination --}}
                                    <th scope="row">{{ $seances->firstItem() + $index }}</th>

                                    {{-- Matière (avec null-safe) --}}
                                    <td>{{ $seance->matiere->nom_mat ?? 'N/A' }}</td>

                                    {{-- Date & Times --}}
                                    <td>{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $seance->heure_debut }}</td>
                                    <td>{{ $seance->heure_fin }}</td>

                                    {{-- Type avec Badge --}}
                                    <td>
                                        <span class="badge bg-{{ $seance->type === 'cour' ? 'primary' : ($seance->type === 'TD' ? 'info' : 'warning') }}">
                                            {{ strtoupper($seance->type) }}
                                        </span>
                                    </td>

                                    {{-- État (Active/Inactive) --}}
                                    <td>
                                        @if($seance->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Terminée</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <a href="{{ route('pageAbsence', $seance->id) }}"
                                           class="btn btn-success btn-sm {{ $seance->active ? '' : 'disabled' }}"
                                           @if(!$seance->active) tabindex="-1" aria-disabled="true" title="Séance terminée" @endif>
                                            <i class="fas fa-edit me-1"></i>Noter
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                        Aucune séance trouvée.
                                        <br>
                                        <a href="{{ route('create.seance') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i>Créer votre première séance
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 📄 Pagination --}}
                @if($seances instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-3">
                        {{ $seances->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ✅ Tooltip Initialization (AdminLTE 3 / Bootstrap 4) --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
@endsection
