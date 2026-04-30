{{-- ✅ المسار الصحيح: resources/views/admin/etudiants/addStudent.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestion des étudiants</h1> {{-- ✅ تصحيح: étudiants (جمع) --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Ajouter un étudiant</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('save.student') }}" method="POST">
                        @csrf

                        {{-- ✅ عرض الأخطاء العامة --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert"> {{-- ✅ تصحيح: alert-success --}}
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
{{-- ✅ المسار: resources/views/admin/etudiant/addStudent.blade.php --}}
{{-- ✅ @extends لازم تكون أول سطر! --}}
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
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.show.all') }}">Étudiants</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Ajouter un étudiant</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    {{-- ✅ الفورم الصحيح --}}
                    <form action="{{ route('admin.students.save') }}" method="POST">
                        @csrf

                        {{-- ✅ عرض الأخطاء - تصميم AdminLTE --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
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
                                    <label for="cne">CNE <span class="text-danger">*</span></label>
                                    <input type="text" name="cne" id="cne"
                                        class="form-control @error('cne') is-invalid @enderror"
                                        value="{{ old('cne') }}"
                                        placeholder="Ex: A123456789"
                                        required maxlength="20">
                                    @error('cne')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Prénom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" id="prenom"
                                        class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom') }}"
                                        placeholder="Ex: ABD-ELLATIF"
                                        required>
                                    @error('prenom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom') }}"
                                        placeholder="Ex: Bousyf"
                                        required>
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Téléphone --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Téléphone</label>
                                    <input type="tel" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        placeholder="Ex: 0612345678"
                                        pattern="[0-9]{10}"
                                        title="10 chiffres requis">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('filiere') is-invalid @enderror"
                                        name="filiere" id="filiere" required style="width: 100%;">
                                        <option value="">-- Sélectionner --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}"
                                                    {{ old('filiere') == $filiere->id ? 'selected' : '' }}>
                                                    {{ $filiere->nom_filiere }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('filiere')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ✅ أزرار الحفظ والرجوع --}}
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Ajouter
                            </button>
                            <a href="{{ route('admin.students.show.all') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ Select2 Initialization --}}
@push('scripts')
<script>
    $(function() {
        // تهيئة Select2 إذا كانت متوفرة
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: 'Sélectionner une option',
                allowClear: true,
                language: 'fr',
                width: 'resolve'
            });
        }
    });
</script>
@endpush
                        <div class="row">
                            {{-- 👈 CNE --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cne">CNE <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="cne"
                                        id="cne"
                                        class="form-control @error('cne') is-invalid @enderror"
                                        value="{{ old('cne') }}" {{-- ✅ حفظ القيمة بعد الخطأ --}}
                                        placeholder="مثال: A123456789"
                                        required
                                    >
                                    @error('cne')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Prénom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prenom">Prénom <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="prenom"
                                        id="prenom"
                                        class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom') }}"
                                        placeholder="مثال: ABD-ELLATIF"
                                        required
                                    >
                                    @error('prenom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Nom --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="nom"
                                        id="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom') }}"
                                        placeholder="مثال: Bousyf"
                                        required
                                    >
                                    @error('nom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Téléphone --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Numéro de Téléphone</label>
                                    <input
                                        type="tel" {{-- ✅ type="tel" أحسن للموبايل --}}
                                        name="phone"
                                        id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        placeholder= "مثال: 66**00**06
                                        pattern="[0-9]{10}"
                                    >
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 @error('filiere') is-invalid @enderror"
                                        style="width: 100%;"
                                        name="filiere"
                                        id="filiere"
                                        required
                                    >
                                        <option value="">-- Sélectionner une filière --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option
                                                    value="{{ $filiere->id }}"
                                                    {{ old('filiere') == $filiere->id ? 'selected' : '' }} {{-- ✅ تحديد الخيار المحفوظ --}}
                                                >
                                                    {{ $filiere->nom_filiere }}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    @error('filiere')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Ajouter
                            </button>
                            <a href="{{ route('etudiants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ JavaScript لتهيئة Select2 (يلا ماكاينش فـ layout) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                placeholder: 'Sélectionner une option',
                allowClear: true,
                language: 'fr'
            });
        }
    });
</script>
@endpush

</body>
</html>
