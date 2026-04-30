{{-- ✅ المسار: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            {{-- ✅ رسائل التنبيه - Bootstrap 4 Style --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- ✅ بطاقات الإحصائيات السريعة --}}
            <div class="row">

                {{-- 👥 إجمالي الطلاب --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>
                                @php
                                    echo class_exists('\App\Models\Etudiant') ? \App\Models\Etudiant::count() : 0;
                                @endphp
                            </h3>
                            <p>Total Étudiants</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <a href="{{ route('admin.students.show.all') }}" class="small-box-footer">
                            Voir plus <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- 📚 عدد الشعب --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                @php
                                    echo class_exists('\App\Models\Filiere') ? \App\Models\Filiere::count() : 0;
                                @endphp
                            </h3>
                            <p>Filières</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Voir plus <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- 📝 الغيابات اليوم --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                @php
                                    echo (class_exists('\App\Models\Absence'))
                                        ? \App\Models\Absence::whereDate('created_at', today())->count()
                                        : 0;
                                @endphp
                            </h3>
                            <p>Absences (Aujourd'hui)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Voir plus <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                {{-- 👨‍🏫 الأساتذة --}}
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                @php
                                    echo (class_exists('\App\Models\User'))
                                        ? \App\Models\User::where('role', 'prof')->count()
                                        : 0;
                                @endphp
                            </h3>
                            <p>Professeurs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Voir plus <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

            </div>
            {{-- /.row --}}

            {{-- ✅ روابط سريعة للإدارة --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-bolt me-1"></i>Actions rapides
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.students.add.form') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Ajouter un étudiant
                                </a>
                                <a href="{{ route('admin.students.show.all') }}" class="btn btn-info">
                                    <i class="fas fa-list me-1"></i> Gérer les étudiants
                                </a>
                                <a href="#" class="btn btn-success" onclick="return alert('Fonctionnalité en développement')">
                                    <i class="fas fa-file-export me-1"></i> Exporter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ آخر النشاطات --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-clock me-1"></i>Activité récente
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @php
                                    $lastStudents = class_exists('\App\Models\Etudiant')
                                        ? \App\Models\Etudiant::latest()->limit(3)->get()
                                        : collect();
                                @endphp
                                @forelse($lastStudents as $student)
                                <li class="item">
                                    <div class="product-img">
                                        <i class="fas fa-user-graduate text-info fa-2x"></i>
                                    </div>
                                    <div class="product-info">
                                        <a href="{{ route('admin.students.edit.form', $student->id) }}" class="product-title">
                                            {{ $student->nom_etu }} {{ $student->prenom_etu }}
                                        </a>
                                        <span class="product-description">
                                            CNE: {{ $student->cne }} • {{ $student->filiere->nom_filiere ?? '-' }}
                                        </span>
                                    </div>
                                </li>
                                @empty
                                <li class="text-center text-muted py-3">Aucune activité récente</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /.row --}}

        </div>
    </section>
</div>
@endsection

{{-- ✅ Scripts إضافية للداشبورد --}}
@push('scripts')
<script>
    $(function() {
        // ✅ أي تأثيرات إضافية للداشبورد (اختياري)
        // مثال: تحديث الإحصائيات كل دقيقة
        // setInterval(function() {
        //     location.reload();
        // }, 60000);
    });
</script>
@endpush
