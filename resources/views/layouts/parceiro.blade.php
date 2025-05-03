<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Painel do Parceiro - Sistema de Achados e Perdidos">
    <title>@yield('title') - Painel do Parceiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/parceiro.css') }}" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 0.5rem;
            --box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --transition-speed: 0.3s;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: var(--dark-color);
            color: white;
            padding: 1rem;
            transition: transform var(--transition-speed) ease;
            z-index: 1050;
            transform: translateX(-100%);
            overflow-y: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
            padding: 2rem 1.5rem;
            min-height: 100vh;
            transition: margin-left var(--transition-speed) ease, width var(--transition-speed) ease;
            width: 100%;
        }
        
        .main-content.sidebar-active {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }
        
        /* Card Styles */
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            margin-bottom: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .hover-shadow:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
        }
        
        /* Button Styles */
        .btn {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            border-top: none;
        }
        
        /* Sidebar Elements */
        .sidebar-header {
            padding: 1.25rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }
        
        .sidebar-menu {
            margin-top: 1rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: var(--border-radius);
            margin-bottom: 0.5rem;
            transition: all var(--transition-speed);
            font-weight: 500;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar-menu a.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(13, 110, 253, 0.4);
        }
        
        .sidebar-menu i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        /* Partner Profile */
        .partner-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .partner-logo:hover {
            transform: scale(1.05);
        }
        
        .partner-name {
            margin-top: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .partner-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Toggle Button & Overlay */
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1060;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle:hover {
            background: #0b5ed7;
            transform: scale(1.05);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed) ease;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
        
        /* Item Images */
        .item-image-container {
            position: relative;
            height: 200px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        
        .item-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .item-image-container:hover .item-image {
            transform: scale(1.05);
        }
        
        .item-image-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        /* Responsive Styles */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
            
            .sidebar-toggle {
                display: none;
            }
            
            .sidebar-overlay {
                display: none;
            }
        }
        
        @media (max-width: 991.98px) {
            :root {
                --sidebar-width: 260px;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1.5rem 1rem;
            }
            
            .sidebar-toggle {
                top: 0.75rem;
                left: 0.75rem;
            }
        }
        
        @media (max-width: 767.98px) {
            .main-content {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            h1.h3 {
                font-size: 1.5rem;
            }
            
            .d-flex.gap-2 {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 575.98px) {
            :root {
                --sidebar-width: 100%;
            }
            
            .sidebar {
                padding-top: 3rem;
            }
            
            .sidebar-toggle {
                top: 0.5rem;
                left: 0.5rem;
                padding: 0.4rem 0.8rem;
            }
            
            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .item-image-container {
                height: 180px;
            }
        }
        
        /* Ajustes para telas muito pequenas */
        @media (max-width: 575.98px) {
            :root {
                --sidebar-width: 100%;
            }
            
            .sidebar {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Botão de Toggle para Mobile -->
    <button class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay para fechar a sidebar -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            @if(auth()->user()->parceiro && auth()->user()->parceiro->logo)
                <img src="{{ asset('storage/' . auth()->user()->parceiro->logo) }}" 
                     alt="{{ auth()->user()->parceiro->nome_estabelecimento }}" 
                     class="partner-logo">
            @else
                <div class="partner-logo bg-primary d-flex align-items-center justify-content-center">
                    <i class="fas fa-store fa-2x text-white"></i>
                </div>
            @endif
            
            <div class="partner-name">
                {{ auth()->user()->parceiro->nome_estabelecimento ?? 'Parceiro' }}
            </div>
            
            <span class="partner-status {{ auth()->user()->parceiro->ativo ? 'bg-success' : 'bg-danger' }}">
                {{ auth()->user()->parceiro->ativo ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('parceiro.home') }}" class="{{ request()->routeIs('parceiro.home') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('parceiro.itens') }}" class="{{ request()->routeIs('parceiro.itens') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Itens no Estabelecimento
            </a>
            <a href="{{ route('parceiro.transferencias-pendentes') }}" class="{{ request()->routeIs('parceiro.transferencias-pendentes') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Transferências Pendentes
            </a>
            <a href="{{ route('parceiro.vincular-item.form') }}" class="{{ request()->routeIs('parceiro.vincular-item.form') ? 'active' : '' }}">
                <i class="fas fa-link"></i> Vincular Item
            </a>
            <a href="{{ route('parceiro.perfil') }}" class="{{ request()->routeIs('parceiro.perfil') ? 'active' : '' }}">
                <i class="fas fa-user-edit"></i> Editar Perfil
            </a>
            <a href="{{ route('parceiro.mapa') }}" class="{{ request()->routeIs('parceiro.mapa') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> Ver Mapa
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('sidebar-active');
                sidebarOverlay.classList.toggle('active');
            }

            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', toggleSidebar);

            // Fechar a sidebar quando clicar em um link
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>