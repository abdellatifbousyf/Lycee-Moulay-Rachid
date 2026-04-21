{{-- ✅ المسار: resources/views/admin/includes/sidebar.blade.php --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Gestion Absence') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Search -->
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Rechercher..." aria-label="Rechercher">
                <div class="input-group-append">
                    <button class="btn btn-sidebar"><i class="fas fa-search fa-fw"></i></button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- 🏠 Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Tableau de bord</p>
                    </a>
                </li>

                {{-- 👨‍🎓 Étudiants --}}
                <li class="nav-item {{ request()->routeIs('etudiants.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('etudiants.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Étudiants
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('etudiants.index') }}" class="nav-link {{ request()->routeIs('etudiants.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Liste des étudiants</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('etudiants.create') }}" class="nav-link {{ request()->routeIs('etudiants.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ajouter un étudiant</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 👨‍🏫 Enseignants --}}
                <li class="nav-item {{ request()->routeIs('enseignants.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('enseignants.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                            Enseignants
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('enseignants.index') }}" class="nav-link {{ request()->routeIs('enseignants.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Liste des enseignants</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('enseignants.create') }}" class="nav-link {{ request()->routeIs('enseignants.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ajouter un enseignant</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 📚 Matières --}}
                <li class="nav-item {{ request()->routeIs('matieres.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('matieres.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Matières
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('matieres.index') }}" class="nav-link {{ request()->routeIs('matieres.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Liste des matières</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('matieres.create') }}" class="nav-link {{ request()->routeIs('matieres.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ajouter une matière</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 📅 Absences (مشروع 4ayab 🔥) --}}
                <li class="nav-item {{ request()->routeIs('absences.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('absences.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-times"></i>
                        <p>
                            Absences
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('absences.index') }}" class="nav-link {{ request()->routeIs('absences.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gérer les absences</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 🔒 Déconnexion --}}
                <li class="nav-item mt-3">
                    <a href="{{ route('logout') }}" class="nav-link text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Déconnexion</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

{{-- ✅ Form Logout Hidden --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
