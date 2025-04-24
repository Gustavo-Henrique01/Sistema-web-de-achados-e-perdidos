@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Cabeçalho da página -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Itens do Parceiro</h2>
                <p class="text-muted mb-0">{{ $parceiro->nome_estabelecimento }}</p>
            </div>
            <div>
                <a href="{{ route('admin.parceiros.show', $parceiro) }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Voltar para Detalhes
                </a>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Lista de Itens</h5>
                        <span class="badge bg-primary rounded-pill">{{ $itens->total() }} itens</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($itens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Imagem</th>
                                    <th>Descrição</th>
                                    <th>Categoria</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itens as $item)
                                <tr class="border-bottom">
                                    <td class="ps-4 fw-medium">{{ $item->id }}</td>
                                    <td>
                                        @if($item->fotos->count() > 0)
                                            <div class="rounded-circle overflow-hidden" style="width: 45px; height: 45px; background-color: #f8f9fa;">
                                                <img src="{{ asset('storage/' . $item->fotos->where('is_principal', true)->first()->caminho ?? $item->fotos->first()->caminho) }}" 
                                                    alt="{{ $item->descricao }}" 
                                                    class="w-100 h-100 object-fit-cover">
                                            </div>
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-light" style="width: 45px; height: 45px;">
                                                <i class="fas fa-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-medium">{{ \Illuminate\Support\Str::limit($item->descricao, 50) }}</p>
                                        <small class="text-muted">ID: #{{ $item->id }}</small>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                                            <i class="fas fa-tag me-1 text-primary"></i> {{ $item->categoria->nome_categoria ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->tipo == 'achado')
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                                <i class="fas fa-search me-1"></i> Achado
                                            </span>
                                        @elseif($item->tipo == 'perdido')
                                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">
                                                <i class="fas fa-question-circle me-1"></i> Perdido
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                <i class="fas fa-info-circle me-1"></i> {{ $item->tipo }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'aprovado')
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> Aprovado
                                            </span>
                                        @elseif($item->status == 'pendente')
                                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Pendente
                                            </span>
                                        @elseif($item->status == 'reprovado')
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i> Reprovado
                                            </span>
                                        @elseif($item->status == 'em_transferencia')
                                            <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2">
                                                <i class="fas fa-exchange-alt me-1"></i> Em Transferência
                                            </span>
                                        @elseif($item->status == 'em_estabelecimento')
                                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                <i class="fas fa-store me-1"></i> No Estabelecimento
                                            </span>
                                        @elseif($item->status == 'devolvido')
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                <i class="fas fa-handshake me-1"></i> Devolvido
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                <i class="fas fa-info-circle me-1"></i> {{ $item->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $item->created_at->format('d/m/Y') }}</span>
                                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ver-item', $item->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                            <div class="mb-3 mb-md-0">
                                <p class="text-muted mb-0">Mostrando {{ $itens->firstItem() ?? 0 }} a {{ $itens->lastItem() ?? 0 }} de {{ $itens->total() }} itens</p>
                            </div>
                            <div>
                                {{ $itens->links() }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="p-4">
                        <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-3 d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x text-info"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Nenhum item encontrado</h5>
                                <p class="mb-0">Este parceiro não possui itens associados no momento.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 