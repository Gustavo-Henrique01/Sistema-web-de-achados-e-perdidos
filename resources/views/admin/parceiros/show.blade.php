@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Cabeçalho da página -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-store me-2 text-primary"></i>Detalhes do Parceiro</h2>
                <p class="text-muted mb-0">{{ $parceiro->nome_estabelecimento }} - {{ $parceiro->cnpj }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.parceiros.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Voltar para Lista
                </a>
                <a href="{{ route('admin.parceiros.itens', $parceiro) }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-box me-1"></i> Ver Itens
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações do Estabelecimento -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-building me-2 text-primary"></i>Informações do Estabelecimento</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center mb-4">
                        @if($parceiro->logo)
                            <div class="rounded-circle overflow-hidden mb-3" style="width: 120px; height: 120px; background-color: #f8f9fa;">
                                <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                    alt="{{ $parceiro->nome_estabelecimento }}" 
                                    class="w-100 h-100 object-fit-cover">
                            </div>
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 mb-3" style="width: 120px; height: 120px;">
                                <i class="fas fa-store fa-3x text-primary"></i>
                            </div>
                        @endif
                        <h4 class="fw-bold mb-1">{{ $parceiro->nome_estabelecimento }}</h4>
                        <p class="text-muted">CNPJ: {{ $parceiro->cnpj }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-tag fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Tipo:</div>
                            <div class="ms-auto">
                                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2">
                                        <i class="fas fa-map-marker-alt me-1"></i> Ponto de Coleta
                                    </span>
                                @elseif($parceiro->tipo_parceiro == 'evento')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                                        <i class="fas fa-calendar-alt me-1"></i> Evento
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                        <i class="fas fa-random me-1"></i> Ambos
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-align-left fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Descrição:</div>
                            <div class="ms-auto text-end">{{ $parceiro->descricao ?? 'Não informado' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-clock fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Horário:</div>
                            <div class="ms-auto text-end">{{ $parceiro->horario_funcionamento ?? 'Não informado' }}</div>
                        </div>
                        
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-phone fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Telefone:</div>
                            <div class="ms-auto text-end">{{ $parceiro->telefone_comercial ?? 'Não informado' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-calendar-alt fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Início Parceria:</div>
                            <div class="ms-auto text-end">
                                @if($parceiro->data_inicio_parceria)
                                    <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                                        <i class="fas fa-calendar-check me-1 text-success"></i> {{ $parceiro->data_inicio_parceria->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informações do Responsável e Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-user me-2 text-primary"></i>Informações do Responsável</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $parceiro->usuario->name ?? 'Não informado' }}</h5>
                        <p class="text-muted">Responsável pelo estabelecimento</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-envelope fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Email:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->email ?? 'Não informado' }}</div>
                        </div>
                        
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-phone fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Telefone:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->telefone ?? 'Não informado' }}</div>
                        </div>
                        
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-id-card fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">CPF:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->cpf ?? 'Não informado' }}</div>
                        </div>
                    </div>
                </div>
            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Status do Parceiro</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Status:</div>
                                        <div class="col-md-8">
                                            @if($parceiro->status == 'pendente')
                                                <span class="badge bg-warning">Pendente</span>
                                            @elseif($parceiro->status == 'aprovado')
                                                <span class="badge bg-success">Aprovado</span>
                                            @else
                                                <span class="badge bg-danger">Reprovado</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Ativo:</div>
                                        <div class="col-md-8">
                                            @if($parceiro->ativo)
                                                <span class="badge bg-success">Sim</span>
                                            @else
                                                <span class="badge bg-danger">Não</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($parceiro->status == 'aprovado')
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Aprovado em:</div>
                                        <div class="col-md-8">{{ $parceiro->data_aprovacao ? $parceiro->data_aprovacao->format('d/m/Y H:i') : 'N/A' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Aprovado por:</div>
                                        <div class="col-md-8">{{ $parceiro->aprovadoPor->name ?? 'N/A' }}</div>
                                    </div>
                                    @endif

                                    @if($parceiro->status == 'reprovado')
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Motivo:</div>
                                        <div class="col-md-8 text-danger">{{ $parceiro->motivo_reprovacao }}</div>
                                    </div>
                                    @endif

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Cadastrado em:</div>
                                        <div class="col-md-8">{{ $parceiro->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Localização -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Localização</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Nome Local:</div>
                                        <div class="col-md-8">{{ $parceiro->localizacao->nome_local ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Endereço:</div>
                                        <div class="col-md-8">{{ $parceiro->localizacao->endereco ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Referência:</div>
                                        <div class="col-md-8">{{ $parceiro->localizacao->referencia ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Coordenadas:</div>
                                        <div class="col-md-8">
                                            @if($parceiro->localizacao && $parceiro->localizacao->latitude && $parceiro->localizacao->longitude)
                                                {{ $parceiro->localizacao->latitude }}, {{ $parceiro->localizacao->longitude }}
                                            @else
                                                Não informado
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Ações</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($parceiro->status == 'pendente')
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                                <i class="fas fa-check me-1"></i> Aprovar Parceiro
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                <i class="fas fa-times me-1"></i> Reprovar Parceiro
                                            </button>
                                        @endif

                                        @if($parceiro->status == 'aprovado')
                                            <form action="{{ route('admin.parceiros.desativar', $parceiro) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn {{ $parceiro->ativo ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="fas {{ $parceiro->ativo ? 'fa-ban' : 'fa-check-circle' }} me-1"></i> 
                                                    {{ $parceiro->ativo ? 'Desativar' : 'Ativar' }} Parceiro
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.parceiros.itens', $parceiro) }}" class="btn btn-info">
                                            <i class="fas fa-box me-1"></i> Ver Itens
                                        </a>

                                        <a href="/chatify/{{ $parceiro->usuario->id }}" class="btn btn-primary">
                                            <i class="fas fa-comments me-1"></i> Abrir Chat
                                        </a>

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash me-1"></i> Excluir Parceiro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Aprovação -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Aprovar Parceiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja aprovar o parceiro <strong>{{ $parceiro->nome_estabelecimento }}</strong>?</p>
                <p>Após a aprovação, o parceiro poderá acessar o sistema e gerenciar os itens achados e perdidos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.parceiros.aprovar', $parceiro) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Aprovar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Reprovação -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reprovar Parceiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.parceiros.reprovar', $parceiro) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Tem certeza que deseja reprovar o parceiro <strong>{{ $parceiro->nome_estabelecimento }}</strong>?</p>
                    <div class="mb-3">
                        <label for="motivo_reprovacao" class="form-label">Motivo da Reprovação:</label>
                        <textarea class="form-control" id="motivo_reprovacao" name="motivo_reprovacao" rows="3" required></textarea>
                        <div class="form-text">Forneça um motivo claro para a reprovação, pois esta informação será enviada ao parceiro.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Reprovar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Excluir Parceiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                </div>
                <p>Tem certeza que deseja excluir o parceiro <strong>{{ $parceiro->nome_estabelecimento }}</strong>?</p>
                <p>Todos os dados relacionados a este parceiro serão removidos permanentemente, incluindo:</p>
                <ul>
                    <li>Informações do estabelecimento</li>
                    <li>Histórico de itens</li>
                    <li>Mensagens do chat</li>
                    <li>Conta de usuário associada</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.parceiros.destroy', $parceiro) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Excluir Permanentemente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection