<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Administration' }}</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        /* THEMES */
        body.light {
            --sidebar-bg: #ffffff;
            --sidebar-text: #111827;
            --sidebar-link: #374151;
            --sidebar-hover: #f1f5f9;
            --navbar-bg: #ffffff;
            background: #f8fafc;
            color: #111827;
        }
        body.dark {
            --sidebar-bg: #0b1220;
            --sidebar-text: #e6eef6;
            --sidebar-link: #9ca3af;
            --sidebar-hover: #111827;
            --navbar-bg: #0f1724;
            background: #071129;
            color: #e6eef6;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            padding: 18px;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .sidebar h4 { color: var(--sidebar-text); }

        .sidebar a {
            color: var(--sidebar-link);
            padding: 10px 14px;
            display: block;
            text-decoration: none;
            font-size: 0.98rem;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
        }

        .admin-info {
            margin-top: 25px;
            padding: 14px;
            background: var(--sidebar-hover);
            border-radius: 10px;
            text-align: center;
        }

        .admin-info img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
        }

        .admin-info small {
            font-size: 0.8rem;
            color: var(--sidebar-link);
        }

        .content {
            margin-left: 260px;
            padding: 28px;
            min-height: 100vh;
        }
    </style>
</head>

<body>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <h4 class="text-center mb-3 fw-bold">CULTURE BENIN</h4>

        <!-- 👇 Bloc admin affiché ici -->
        <div class="admin-info mb-4">
            @php
                $admin = auth()->user();
                $avatar = $admin->photo
                    ? asset('storage/' . $admin->photo)
                    : ($admin->sexe === 'Femme' ? asset('female.jpg') : asset('male.jpg'));
            @endphp

            <img src="{{ $avatar }}" alt="Avatar">
            <div class="mt-2 fw-bold">{{ $admin->nom }}</div>
            <small>{{ $admin->role->nom ?? 'Administrateur' }}</small>

            <div class="mt-3">
                <a href="{{ route('admin.utilisateurs.edit', $admin->id_utilisateur) }}" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="bi bi-person"></i> Profil
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        <!-- 👆 FIN Bloc admin -->

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Tableau de Bord
        </a>

        <h6 class="text-uppercase mt-3 mb-2 small">Gestion</h6>
        <a href="{{ route('admin.contenus.index') }}"><i class="bi bi-journal-richtext me-2"></i>Contenus</a>
        <a href="{{ route('admin.medias.index') }}"><i class="bi bi-images me-2"></i>Médias</a>
        <a href="{{ route('admin.commentaires.index') }}"><i class="bi bi-chat-text me-2"></i>Commentaires</a>
        <a href="{{ route('admin.utilisateurs.index') }}"><i class="bi bi-people me-2"></i>Utilisateurs</a>

        <h6 class="text-uppercase mt-3 mb-2 small">Catégories</h6>
        <a href="{{ route('admin.type_contenus.index') }}"><i class="bi bi-tags me-2"></i>Types de Contenus</a>
        <a href="{{ route('admin.type_medias.index') }}"><i class="bi bi-collection me-2"></i>Types de Médias</a>

        <h6 class="text-uppercase mt-3 mb-2 small">Localisation</h6>
        <a href="{{ route('admin.regions.index') }}"><i class="bi bi-geo me-2"></i>Régions</a>
        <a href="{{ route('admin.langues.index') }}"><i class="bi bi-translate me-2"></i>Langues</a>
        <a href="{{ route('admin.parlers.index') }}"><i class="bi bi-people-fill me-2"></i>Parler</a>

        <h6 class="text-uppercase mt-3 mb-2 small">Sécurité</h6>
        <a href="{{ route('admin.roles.index') }}"><i class="bi bi-shield-lock me-2"></i>Rôles</a>
    </div>

    <!-- ===== CONTENT ===== -->
    <div class="content">

        <!-- Navbar top -->
        <nav class="navbar navbar-expand-lg shadow-sm rounded mb-4 px-3 py-2">
            <div class="container-fluid">

                <div class="d-flex align-items-center">

                    <!-- DARK/LIGHT MODE BUTTON -->
                    <button id="toggleTheme" class="btn btn-outline-secondary me-3">
                        <i id="themeIcon" class="bi"></i>
                    </button>

                    <span class="me-3 fw-bold">{{ auth()->user()->nom ?? 'Admin' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">Déconnexion</button>
                    </form>
                </div>
            </div>
        </nav>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('Content')

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables FR -->
    <script>
        const datatablesFrUrl = "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json";
    </script>

    <!-- THEME SWITCH -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.classList.add(savedTheme);

        const icon = document.getElementById('themeIcon');
        icon.className = savedTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';

        document.getElementById('toggleTheme').addEventListener('click', () => {
            const isDark = document.body.classList.contains('dark');
            document.body.classList.toggle('dark');
            document.body.classList.toggle('light');
            const newTheme = isDark ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            icon.className = newTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        });
    </script>

    @stack('scripts')

</body>
</html>
