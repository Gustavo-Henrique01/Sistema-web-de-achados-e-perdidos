@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalhes do Parceiro</h5>
                    <div>
                        <a href="{{ route('admin.parceiros.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Informações do Estabelecimento -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informações do Estabelecimento</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-center">
                                        @if($parceiro->logo)
                                            <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                                alt="{{ $parceiro->nome_estabelecimento }}" 
                                                class="img-fluid rounded" 
                                                style="max-height: 120px;">
                                        @else
                                            <div class="bg-light rounded p-3 text-center">
                                                <i class="fas fa-store fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Nome:</div>
                                        <div class="col-md-8">{{ $parceiro->nome_estabelecimento }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Tipo:</div>
                                        <div class="col-md-8">
                                            @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                                <span class="badge bg-info">Ponto de Coleta</span>
                                            @elseif($parceiro->tipo_parceiro == 'evento')
                                                <span class="badge bg-primary">Evento</span>
                                            @else
                                                <span class="badge bg-secondary">Ambos</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Descrição:</div>
                                        <div class="col-md-8">{{ $parceiro->descricao ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Horário:</div>
                                        <div class="col-md-8">{{ $parceiro->horario_funcionamento ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Telefone:</div>
                                        <div class="col-md-8">{{ $parceiro->telefone_comercial ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Início Parceria:</div>
                                        <div class="col-md-8">{{ $parceiro->data_inicio_parceria ? $parceiro->data_inicio_parceria->format('d/m/Y') : 'Não informado' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Responsável e Status -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informações do Responsável</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Nome:</div>
                                        <div class="col-md-8">{{ $parceiro->usuario->name ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Email:</div>
                                        <div class="col-md-8">{{ $parceiro->usuario->email ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Telefone:</div>
                                        <div class="col-md-8">{{ $parceiro->usuario->telefone ?? 'Não informado' }}</div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">CPF:</div>
                                        <div class="col-md-8">{{ $parceiro->usuario->cpf ?? 'Não informado' }}</div>
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
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-ban me-1"></i> {{ $parceiro->ativo ? 'Desativar' : 'Ativar' }} Parceiro
                                            </button>
                                        </form>
                                        @endif

                                        <a href="{{ route('admin.parceiros.itens', $parceiro) }}" class="btn btn-info">
                                            <i class="fas fa-box me-1"></i> Ver Itens
                                        </a>
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
@endsection 