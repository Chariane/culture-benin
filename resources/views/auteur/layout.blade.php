<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Espace Auteur' }}</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        /* ===== THEMES ===== */
        body.light {
            --sidebar-bg: #ffffff;
            --sidebar-text: #111827;
            --sidebar-link: #4b5563;
            --sidebar-hover: #f3f4f6;
            --sidebar-active: #e5e7eb;
            background: #f8fafc;
            color: #111827;
        }
        body.dark {
            --sidebar-bg: #0b1220;
            --sidebar-text: #e6eef6;
            --sidebar-link: #9ca3af;
            --sidebar-hover: #1a2538;
            --sidebar-active: #233149;
            background: #071129;
            color: #e6eef6;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid rgba(0,0,0,0.08);
        }

        .sidebar h4 {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            color: var(--sidebar-link);
            padding: 12px 14px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.25s ease-in-out;
        }

        .sidebar a:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
            transform: translateX(6px);
        }

        .sidebar a.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text);
            font-weight: 600;
            border-left: 4px solid #3b82f6;
        }

        .sidebar .section-title {
            text-transform: uppercase;
            font-size: 11px;
            margin-top: 18px;
            margin-bottom: 6px;
            font-weight: 600;
            opacity: 0.6;
        }

        /* ===== CONTENT ===== */
        .content {
            margin-left: 260px;
            padding: 28px;
            min-height: 100vh;
        }

        nav.navbar {
            background: var(--sidebar-bg);
        }
    </style>
</head>

<body>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <h4 class="text-center mb-2">AUTEUR – CULTURE BENIN</h4>

        <!-- ===== PHOTO DE PROFIL ===== -->
        <div class="text-center mb-4">

            @php
                $user = auth()->user();
                $defaultPhoto = $user->sexe === 'F'
                    ? asset('female.jpg')
                    : asset('male.jpg');

                $photoUrl = $user->photo
                    ? asset('storage/' . $user->photo)
                    : $defaultPhoto;
            @endphp

            <img src="{{ $photoUrl }}"
                alt="Photo de profil"
                class="rounded-circle shadow"
                style="width: 120px; height: 120px; object-fit: cover;">
        </div>


        <!-- ===== MENU ===== -->
        <a href="{{ route('auteur.dashboard') }}"
           class="{{ request()->routeIs('auteur.dashboard') ? 'active' : '' }}">
           <i class="bi bi-speedometer2"></i> Tableau de Bord
        </a>

        <div class="section-title">Créer & Gérer</div>

        <a href="{{ route('auteur.contenus.index') }}"
           class="{{ request()->routeIs('auteur.contenus.*') ? 'active' : '' }}">
           <i class="bi bi-journal-richtext"></i> Mes Contenus
        </a>

        <a href="{{ route('auteur.type_contenus.index') }}"
           class="{{ request()->routeIs('auteur.type_contenus.*') ? 'active' : '' }}">
           <i class="bi bi-tags"></i> Types de Contenus
        </a>

        <a href="{{ route('auteur.medias.index') }}"
           class="{{ request()->routeIs('auteur.medias.*') ? 'active' : '' }}">
           <i class="bi bi-images"></i> Mes Médias
        </a>

        <div class="section-title">Paramètres</div>

        <a href="{{ route('auteur.type_medias.index') }}"
           class="{{ request()->routeIs('auteur.type_medias.*') ? 'active' : '' }}">
           <i class="bi bi-collection"></i> Types de Médias
        </a>
    </div>

    <!-- ===== CONTENT ===== -->
    <div class="content">

        <nav class="navbar navbar-expand-lg shadow-sm rounded mb-4 px-3 py-2">
            <div class="container-fluid d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">
                    <button id="toggleTheme" class="btn btn-outline-secondary me-3">
                        <i id="themeIcon" class="bi"></i>
                    </button>

                    <span class="me-3 fw-bold">{{ auth()->user()->nom }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Déconnexion</button>
                </form>

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
