{{-- ✅ المسار: resources/views/admin/etudiant/update.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier un étudiant</h1>
                </div>
                <div class="col-sm-6">
                    {{-- ✅ Route الصحيح --}}
                    <a href="{{ route('admin.students.show.all') }}" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>Formulaire de modification
                    </h3>
                </div>

                <div class="card-body">
                    {{-- ✅ الفورم الصحيح - أسماء الحقول مطابقة للـ Controller --}}
                    <form action="{{ route('admin.students.update', $etudiant->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ✅ حقل مخفي للـ ID (ضروري للتحقق) --}}
                        <input type="hidden" name="id" value="{{ $etudiant->id }}">

                        {{-- ✅ عرض الأخطاء --}}
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

                        <div class="row">
                            {{-- 👈 CNE (ممنوع التعديل عليه عادةً) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cne">CNE <span class="text-danger">*</span></label>
                                    <input type="text" name="cne" id="cne"
                                           class="form-control @error('cne') is-invalid @enderror"
                                           value="{{ old('cne', $etudiant->cne) }}"
                                           required maxlength="20" readonly>
                                    <small class="text-muted">Le CNE ne peut pas être modifié</small>
                                    @error('cne')
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
                                           value="{{ old('nom', $etudiant->nom_etu) }}"
                                           required placeholder="Ex: BOUSYF">
                                    @error('nom')
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
                                           value="{{ old('prenom', $etudiant->prenom_etu) }}"
                                           required placeholder="Ex: ABD-ELLATIF">
                                    @error('prenom')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Téléphone (nullable) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Téléphone</label>
                                    <input type="tel" name="phone" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $etudiant->phone_etu) }}"
                                           pattern="[0-9]{10}" placeholder="0612345678">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- 👈 Filière --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere">Filière <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="filiere" id="filiere"
                                            style="width: 100%;" required>
                                        <option value="">-- Sélectionner --</option>
                                        @isset($filieres)
                                            @foreach ($filieres as $filiere)
                                                <option value="{{ $filiere->id }}"
                                                    {{ old('filiere', $etudiant->id_filiere) == $filiere->id ? 'selected' : '' }}>
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

                        {{-- ✅ أزرار الحفظ والإلغاء --}}
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer
                            </button>
                            <a href="{{ route('admin.students.show.all') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

{{-- ✅ Select2 Initialization - French + AdminLTE --}}
@push('scripts')
<script>
    $(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: 'Sélectionner une filière',
                allowClear: true,
                language: 'fr',
                width: 'resolve',
                dropdownParent: $('.select2').closest('.card') // مهم لـ AdminLTE
            });
        }
    });
</script>
@endpush
