<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Painel Administrativo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #343a40 0%, #212529 100%);
            color: white;
            padding-top: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 1000;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }
        
        .content-fluid {
            flex: 1;
            margin-left: 250px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .navbar-top {
            background: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 900;
        }
        
        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 60px);
        }
        
        .sidebar-header {
            padding: 0 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-brand i {
            margin-right: 10px;
            font-size: 1.75rem;
        }
        
        .user-info {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
        }
        
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-radius: 5px;
            margin: 2px 10px;
        }
        
        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 500;
        }
        
        .nav-toggler {
            cursor: pointer;
            color: #343a40;
            display: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                position: fixed;
                height: 100%;
                transition: margin 0.3s;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .content-fluid {
                margin-left: 0;
            }
            
            .nav-toggler {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.principal') }}" class="sidebar-brand">
                <i class="fas fa-shield-alt"></i>
                <span>Admin</span>
            </a>
            <div class="user-info">
                Bem-vindo, {{ Auth::user()->name }}
            </div>
        </div>
        
        <a href="{{ route('admin.principal') }}" class="{{ request()->routeIs('admin.principal') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.listar-itens') }}" class="{{ request()->routeIs('admin.listar-itens') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Gerenciar Itens
            <span class="badge rounded-pill bg-primary ms-auto">{{ App\Models\Item::where('status', 'pendente')->count() }}</span>
        </a>
        <a href="{{ route('admin.log-acoes') }}" class="{{ request()->routeIs('admin.log-acoes') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Log de Ações
        </a>
        <a href="{{ route('admin.listar-usuarios') }}" class="{{ request()->routeIs('admin.listar-usuarios') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Usuários Cadastrados
        </a>
        <a href="{{ route('admin.listar-admins') }}" class="{{ request()->routeIs('admin.listar-admins') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i> Administradores
        </a>
        <a href="{{ route('cadastro-categoria') }}" class="{{ request()->routeIs('cadastro-categoria') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Cadastrar Categoria
        </a>
        <a href="{{ route('listar-categorias') }}" class="{{ request()->routeIs('listar-categorias') ? 'active' : '' }}">
            <i class="fas fa-th-list"></i> Listar Categorias
        </a>
        <a href="{{ route('admin.perfil') }}" class="{{ request()->routeIs('admin.perfil') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>Meu Perfil</span>
        </a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content-fluid">
        <!-- Top Navbar -->
        <div class="navbar-top">
            <div class="nav-toggler" id="sidebarToggler">
                <i class="fas fa-bars"></i>
            </div>
            <div>
                <span class="badge bg-primary">Achados e Perdidos - Painel Administrativo</span>
            </div>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggler').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggler = document.getElementById('sidebarToggler');
            
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(event.target) && 
                !toggler.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
