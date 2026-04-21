{{-- ✅ المسار: resources/views/admin/includes/footer.blade.php --}}
<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        v{{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        &copy; {{ date('Y') }}
        <a href="{{ url('/') }}">{{ config('app.name', 'Gestion Absence') }}</a>.
    </strong>
    Tous droits réservés.
</footer>
