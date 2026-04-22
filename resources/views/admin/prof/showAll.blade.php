{{-- ✅ المسار: resources/views/admin/prof/showAll.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des Enseignants</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('add.prof') }}" class="btn btn-primary float-sm-right">
                        <i class="fas fa-plus me-1"></i> Ajouter un enseignant
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- ✅ رسائل النظام --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('ajoute'))
                <div class="alert alert-info alert-dismissible fade show">
                    {{ session('ajoute') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('delete'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('delete') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Adresse</th>
                                        <th>Téléphone</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($profs as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ strtoupper($p->nom_ens) }}</td>
                                        <td>{{ ucfirst($p->prenom_ens) }}</td>
                                        <td>{{ $p->adresse_ens ?? '-' }}</td>
                                        <td>{{ $p->phone_ens ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('editprof', $p->id) }}" class="btn btn-success" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- ✅ حذف آمن بـ POST/DELETE + CSRF + تأكيد --}}
                                                <form action="{{ route('deleteprof', $p->id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fas fa-chalkboard-teacher fa-2x mb-2 d-block"></i>
                                            Aucun enseignant trouvé.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
