@extends('layouts.parceiro')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Bem-vindo ao seu painel de controle</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parceiro.cadastrar-item.form') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i><span>Cadastrar Novo Item</span>
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-box-open text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Total de Itens</h6>
                            <h3 class="card-title mb-0">{{ $itens->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-hand-holding-heart text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Itens Achados</h6>
                            <h3 class="card-title mb-0">{{ $itens->where('tipo', 'achado')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-search text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Itens Perdidos</h6>
                            <h3 class="card-title mb-0">{{ $itens->where('tipo', 'perdido')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-check-circle text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-subtitle text-muted mb-1">Itens Devolvidos</h6>
                            <h3 class="card-title mb-0">{{ $itens->where('status', 'devolvido')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Estabelecimento -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informações do Estabelecimento</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nome do Estabelecimento</label>
                        <p class="mb-0">{{ $parceiro->nome_estabelecimento }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Tipo de Parceiro</label>
                        <p class="mb-0">
                            @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                Ponto de Coleta
                            @elseif($parceiro->tipo_parceiro == 'evento')
                                Local de Evento
                            @else
                                Ponto de Coleta e Local de Evento
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Endereço</label>
                        <p class="mb-0">{{ $parceiro->localizacao->endereco }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Horário de Funcionamento</label>
                        <p class="mb-0">{{ $parceiro->horario_funcionamento ?? 'Não informado' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Telefone</label>
                        <p class="mb-0">{{ $parceiro->telefone_comercial ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Últimos Itens Vinculados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($itens->take(5) as $item)
                                    <tr>
                                        <td>{{ $item->nome }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->tipo == 'achado' ? 'success' : 'warning' }}">
                                                {{ ucfirst($item->tipo) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $item->status == 'devolvido' ? 'info' : 'secondary' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">Nenhum item vinculado ainda</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($itens->count() > 5)
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('parceiro.itens') }}" class="btn btn-link">Ver todos os itens</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 