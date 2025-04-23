@extends('layouts.parceiro')

@section('title', 'Vincular Item')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Vincular Item ao Estabelecimento</h1>
        <a href="{{ route('parceiro.itens') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Lista de Itens Disponíveis -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Itens Disponíveis para Vinculação</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Data de Registro</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($itens as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->foto)
                                                    <img src="{{ asset('storage/' . $item->foto) }}" 
                                                         alt="{{ $item->nome }}" 
                                                         class="rounded me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $item->nome }}</div>
                                                    <div class="small text-muted">{{ Str::limit($item->descricao, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
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
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('parceiro.vincular-item') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-link me-1"></i>Vincular
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-2x mb-3"></i>
                                                <p class="mb-0">Nenhum item disponível para vinculação</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($itens->hasPages())
                    <div class="card-footer bg-white">
                        {{ $itens->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informações do Estabelecimento -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Seu Estabelecimento</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($parceiro->logo)
                            <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                 alt="{{ $parceiro->nome_estabelecimento }}" 
                                 class="rounded-circle mb-3"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $parceiro->nome_estabelecimento }}</h5>
                        <p class="text-muted mb-0">
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

            <!-- Dicas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Dicas</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Você pode vincular itens que foram encontrados em seu estabelecimento.
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Apenas itens com status "pendente" podem ser vinculados.
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Após vincular um item, ele aparecerá na lista de itens do seu estabelecimento.
                        </li>
                        <li>
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Você pode desvincular um item a qualquer momento.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 