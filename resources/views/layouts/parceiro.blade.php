<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Painel do Parceiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/parceiro.css') }}" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #212529;
            color: white;
            padding: 1rem;
            transition: all 0.3s ease;
            z-index: 1000;
            transform: translateX(-100%);
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.sidebar-active {
            margin-left: var(--sidebar-width);
        }
        
        .sidebar-header {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            margin-top: 1rem;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.8rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-menu a.active {
            background-color: #0d6efd;
            color: white;
        }
        
        .sidebar-menu i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.5rem;
        }
        
        .partner-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .partner-name {
            margin-top: 1rem;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .partner-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #212529;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: #0d6efd;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .sidebar-toggle {
                display: none;
            }
            
            .sidebar-overlay {
                display: none;
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
            <a href="{{ route('mapa') }}" class="{{ request()->routeIs('mapa') ? 'active' : '' }}">
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