{{-- ✅ المسار الصحيح: resources/views/admin/etudiants/showAll.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des étudiants</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('add.student') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus me-1"></i> Ajouter un étudiant
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            {{-- ✅ رسائل النجاح/التحديث/الحذف --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('update'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('update') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-trash-alt me-2"></i>{{ session('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des étudiants</h3>
                            <div class="card-tools">
                                {{-- ✅ بحث بسيط (اختياري) --}}
                                <form action="{{ route('etudiants.index') }}" method="GET" class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control form-control-sm"
                                            placeholder="Rechercher..."
                                            value="{{ request('search') }}"
                                        >
                                        <button type="submit" class="btn btn-sm btn-info">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- ✅ حالة الفراغ: لا توجد بيانات --}}
                            @forelse($students ?? [] as $student)
                                {{-- ✅ DataTables: الجدول --}}
                                <table id="studentsTable" class="table table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>CNE</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Téléphone</th>
                                            <th>Filière</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $index => $student)
                                        <tr>
                                            <td>{{ $students->firstItem() + $index }}</td> {{-- ✅ رقم الصف مع pagination --}}
                                            <td>{{ $student->cne }}</td>
                                            <td>{{ strtoupper($student->nom_etu) }}</td>
                                            <td>{{ ucfirst($student->prenom_etu) }}</td>
                                            <td>
                                                <a href="tel:{{ $student->phone_etu }}" class="text-decoration-none">
                                                    <i class="fas fa-phone-alt me-1"></i>{{ $student->phone_etu }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $student->filiere->nom_filiere ?? 'Non définie' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    {{-- ✏️ Edit --}}
                                                    <a href="{{ route('edit.student', $student->id) }}"
                                                       class="btn btn-success"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    {{-- 🗑️ Delete (POST Method + CSRF + Confirmation) --}}
                                                    <form action="{{ route('delete.student', $student->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action est irréversible.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-title="Supprimer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>

                                                    {{-- 👁️ View Details (اختياري) --}}
                                                    <a href="{{ route('student.show', $student->id) }}"
                                                       class="btn btn-info"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @empty
                                {{-- ✅ حالة الفراغ: رسالة لطيفة --}}
                                <div class="text-center py-5">
                                    <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucun étudiant trouvé</h5>
                                    <p class="text-muted">Commencez par ajouter un nouvel étudiant.</p>
                                    <a href="{{ route('add.student') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Ajouter un étudiant
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        {{-- ✅ Pagination Links (Laravel Style) --}}
                        @if(isset($students) && $students->hasPages())
                        <div class="card-footer clearfix">
                            {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ JavaScript لتهيئة DataTables + Tooltips --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Initialize DataTables (إذا كان المثال2 هو اسم الجدول)
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#studentsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
                },
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
                "order": [[1, "asc"]] // ترتيب حسب CNE
            });
        }

        // ✅ Initialize Bootstrap Tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endpush
