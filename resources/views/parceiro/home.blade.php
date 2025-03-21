<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Parceiro - {{ $parceiro->nome_estabelecimento }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar a {
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 16px;
        }
        
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
        }
        
        .logo-container img {
            max-width: 100px;
            border-radius: 50%;
            border: 3px solid white;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .stats-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4e73df;
        }
        
        .item-card {
            transition: transform 0.3s;
        }
        
        .item-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            @if($parceiro->logo)
                <img src="{{ asset('storage/' . $parceiro->logo) }}" alt="{{ $parceiro->nome_estabelecimento }}" class="img-fluid">
            @else
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; margin: 0 auto;">
                    <span class="fs-1">{{ substr($parceiro->nome_estabelecimento, 0, 1) }}</span>
                </div>
            @endif
            <h5 class="mt-3">{{ $parceiro->nome_estabelecimento }}</h5>
            <span class="status-badge bg-{{ $parceiro->ativo ? 'success' : 'danger' }}">
                {{ $parceiro->ativo ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
        
        <a href="{{ route('parceiro.home') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        <a href="{{ route('parceiro.itens') }}"><i class="fas fa-box-open me-2"></i> Itens no Estabelecimento</a>
        <a href="{{ route('parceiro.vincular-item.form') }}"><i class="fas fa-link me-2"></i> Vincular Item</a>
        <a href="{{ route('mapa') }}"><i class="fas fa-map-marker-alt me-2"></i> Ver Mapa</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-2"></i> Sair
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        <div class="container-fluid">
            <h1 class="mb-4">Bem-vindo, {{ $parceiro->nome_estabelecimento }}</h1>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ $itens->count() }}</div>
                        <div class="text-muted">Itens no Estabelecimento</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ $itens->where('tipo', 'achado')->count() }}</div>
                        <div class="text-muted">Itens Achados</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ $itens->where('tipo', 'perdido')->count() }}</div>
                        <div class="text-muted">Itens Perdidos</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Informações do Estabelecimento</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Tipo:</strong> 
                                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                    Ponto de Coleta
                                @elseif($parceiro->tipo_parceiro == 'evento')
                                    Local de Evento
                                @else
                                    Ponto de Coleta e Local de Evento
                                @endif
                            </p>
                            <p><strong>Endereço:</strong> {{ $parceiro->localizacao->endereco }}</p>
                            <p><strong>Horário de Funcionamento:</strong> {{ $parceiro->horario_funcionamento ?? 'Não informado' }}</p>
                            <p><strong>Telefone:</strong> {{ $parceiro->telefone_comercial ?? 'Não informado' }}</p>
                            <p><strong>Parceiro desde:</strong> {{ $parceiro->data_inicio_parceria->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Itens Recentes</h5>
                        </div>
                        <div class="card-body">
                            @if($itens->isEmpty())
                                <p class="text-center">Nenhum item vinculado ao estabelecimento.</p>
                            @else
                                <div class="list-group">
                                    @foreach($itens->take(5) as $item)
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $item->categoria->nome_categoria }}</h6>
                                                <small>{{ $item->tipo }}</small>
                                            </div>
                                            <p class="mb-1">{{ \Illuminate\Support\Str::limit($item->descricao, 50) }}</p>
                                        </a>
                                    @endforeach
                                </div>
                                @if($itens->count() > 5)
                                    <a href="{{ route('parceiro.itens') }}" class="btn btn-sm btn-outline-primary mt-3">Ver todos os itens</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 