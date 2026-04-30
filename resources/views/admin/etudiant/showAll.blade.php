{{-- ✅ المسار: resources/views/admin/etudiant/showAll.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des étudiants</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('admin.students.add.form') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus me-1"></i> Ajouter un étudiant
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            {{-- ✅ رسائل التنبيه - AdminLTE/Bootstrap 4 --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('update'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('update') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if (session('delete'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-trash-alt me-2"></i>{{ session('delete') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Liste des étudiants</h3>
                            <div class="card-tools d-flex gap-2">

                                {{-- ✅ بحث --}}
                                <form action="{{ route('admin.students.show.all') }}" method="GET" class="form-inline">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                               placeholder="Rechercher..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-sm btn-info">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>

                                {{-- ✅ تصدير Excel (اختياري) --}}
                                <a href="{{ route('admin.students.export') ?? '#' }}"
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Exporter la liste en Excel ?')">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>

                            </div>
                        </div>

                        <div class="card-body">

                            {{-- ✅ مؤشر التحميل --}}
                            <div id="table-loading" class="text-center py-3 d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>

                            {{-- ✅ الجدول - هيكلية صحيحة --}}
                            <table id="studentsTable" class="table table-bordered table-hover dataTable" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>CNE</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Filière</th>
                                        <th class="text-center" width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students ?? [] as $index => $student)
                                    <tr>
                                        {{-- ✅ الترقيم مع Pagination --}}
                                        <td>{{ $students->firstItem() + $index }}</td>

                                        <td><strong>{{ $student->cne }}</strong></td>
                                        <td>{{ strtoupper($student->nom_etu) }}</td>
                                        <td>{{ ucfirst($student->prenom_etu) }}</td>

                                        {{-- ✅ الهاتف: حماية إذا كان فارغاً --}}
                                        <td>
                                            @if($student->phone_etu)
                                                <a href="tel:{{ $student->phone_etu }}" class="text-decoration-none">
                                                    <i class="fas fa-phone-alt me-1"></i>{{ $student->phone_etu }}
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge badge-info">
                                                {{ $student->filiere->nom_filiere ?? 'Non définie' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- ✏️ Edit --}}
                                                <a href="{{ route('admin.students.edit.form', $student->id) }}"
                                                   class="btn btn-success btn-sm"
                                                   data-toggle="tooltip" data-placement="top" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- 🗑️ Delete - CSRF + Confirm --}}
                                                <form action="{{ route('admin.students.delete', $student->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            data-toggle="tooltip" data-placement="top" title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-graduate fa-4x mb-3 text-secondary"></i>
                                            <h5>Aucun étudiant trouvé</h5>
                                            <p class="mb-3">Commencez par ajouter un nouvel étudiant.</p>
                                            <a href="{{ route('admin.students.add.form') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Ajouter un étudiant
                                            </a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ✅ Pagination - Bootstrap 4 --}}
                        @if(isset($students) && $students->hasPages())
                        <div class="card-footer clearfix">
                            {{ $students->withQueryString()->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ Scripts: DataTables + Tooltips + SweetAlert2 --}}
@push('scripts')
<!-- SweetAlert2 CDN (للتأكيد الجميل عند الحذف) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {

        // ✅ 1. DataTables Initialization
        if ($.fn.DataTable) {
            $('#table-loading').removeClass('d-none');

            var table = $('#studentsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
                },
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
                "order": [[1, "asc"]], // ترتيب حسب CNE
                "destroy": true,
                "initComplete": function() {
                    $('#table-loading').addClass('d-none');
                }
            });
        }

        // ✅ 2. Bootstrap 4 Tooltips
        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // ✅ 3. SweetAlert2 للحذف (أفضل من confirm العادية)
        $('form[method="POST"][onsubmit*="confirm"]').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
</script>
@endpush
