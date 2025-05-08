@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-store me-2 text-primary"></i>Gerenciamento de Parceiros</h2>
                <p class="text-muted mb-0">Visualize, aprove e gerencie todos os parceiros do sistema</p>
            </div>
         
        </div>
    </div>

    <!-- Dashboard de Status -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.parceiros.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden border-primary border-start border-5 hover-shadow">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Todos os Parceiros</p>
                                <h3 class="fw-bold mb-0 text-primary">{{ App\Models\Parceiro::count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-store fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.parceiros.index', ['status' => 'aprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden border-success border-start border-5 hover-shadow">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Parceiros Aprovados</p>
                                <h3 class="fw-bold mb-0 text-success">{{ App\Models\Parceiro::where('status', 'aprovado')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.parceiros.index', ['status' => 'pendente']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden border-warning border-start border-5 hover-shadow">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Parceiros Pendentes</p>
                                <h3 class="fw-bold mb-0 text-warning">{{ App\Models\Parceiro::where('status', 'pendente')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-clock fa-lg text-warning"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.parceiros.index', ['status' => 'reprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden border-danger border-start border-5 hover-shadow">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Parceiros Reprovados</p>
                                <h3 class="fw-bold mb-0 text-danger">{{ App\Models\Parceiro::where('status', 'reprovado')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fas fa-times-circle fa-lg text-danger"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            cursor: pointer;
        }
    </style>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Lista de Parceiros</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Estabelecimento</th>
                            <th>CNPJ</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Data de Cadastro</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parceiros as $parceiro)
                        <tr class="border-bottom">
                            <td class="ps-4 fw-medium">{{ $parceiro->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-small me-3 bg-light rounded-circle p-2">
                                        @if($parceiro->logo)
                                            <img src="{{ asset('storage/' . $parceiro->logo) }}" alt="Logo" class="rounded-circle" width="40" height="40">
                                        @else
                                            <i class="fas fa-store text-primary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $parceiro->nome_estabelecimento }}</p>
                                        <small class="text-muted">{{ $parceiro->usuario->email ?? 'Sem email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $parceiro->cnpj }}</td>
                            <td>
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
                            </td>
                            <td>
                                @if($parceiro->status == 'aprovado')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Aprovado
                                    </span>
                                @elseif($parceiro->status == 'reprovado')
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i> Reprovado
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> Pendente
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $parceiro->created_at->format('d/m/Y') }}</span>
                                    <small class="text-muted">{{ $parceiro->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.parceiros.show', $parceiro->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        <i class="fas fa-eye me-1"></i> Detalhes
                                    </a>
                                    @if($parceiro->status == 'pendente')
                                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#aprovarModal{{ $parceiro->id }}">
                                            <i class="fas fa-check me-1"></i> Aprovar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#reprovarModal{{ $parceiro->id }}">
                                            <i class="fas fa-times me-1"></i> Rejeitar
                                        </button>
                                    @endif
                                </div>

                                <!-- Modal Aprovar -->
                                <div class="modal fade" id="aprovarModal{{ $parceiro->id }}" tabindex="-1" aria-labelledby="aprovarModalLabel{{ $parceiro->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-success bg-opacity-10 border-bottom-0">
                                                <h5 class="modal-title text-success fw-bold" id="aprovarModalLabel{{ $parceiro->id }}">
                                                    <i class="fas fa-check-circle me-2"></i>Aprovar Parceiro
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="text-center mb-4">
                                                    <div class="avatar-large mx-auto bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                                                        <i class="fas fa-store fa-2x text-success"></i>
                                                    </div>
                                                    <h4 class="fw-bold">{{ $parceiro->nome_estabelecimento }}</h4>
                                                    <p class="text-muted">CNPJ: {{ $parceiro->cnpj }}</p>
                                                </div>
                                                <div class="alert alert-success bg-success bg-opacity-10 border-0">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <span>Ao aprovar este parceiro, ele poderá receber itens para devolução e terá acesso ao sistema.</span>
                                                </div>
                                                <p class="mb-0">Você confirma a aprovação deste parceiro?</p>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-2"></i>Cancelar
                                                </button>
                                                <form action="{{ route('admin.parceiros.aprovar', $parceiro->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success rounded-pill px-4">
                                                        <i class="fas fa-check me-2"></i>Confirmar Aprovação
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Reprovar -->
                                <div class="modal fade" id="reprovarModal{{ $parceiro->id }}" tabindex="-1" aria-labelledby="reprovarModalLabel{{ $parceiro->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger bg-opacity-10 border-bottom-0">
                                                <h5 class="modal-title text-danger fw-bold" id="reprovarModalLabel{{ $parceiro->id }}">
                                                    <i class="fas fa-times-circle me-2"></i>Reprovar Parceiro
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.parceiros.reprovar', $parceiro->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <div class="text-center mb-4">
                                                        <div class="avatar-large mx-auto bg-danger bg-opacity-10 rounded-circle p-3 mb-3">
                                                            <i class="fas fa-store fa-2x text-danger"></i>
                                                        </div>
                                                        <h4 class="fw-bold">{{ $parceiro->nome_estabelecimento }}</h4>
                                                        <p class="text-muted">CNPJ: {{ $parceiro->cnpj }}</p>
                                                    </div>
                                                    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 mb-4">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        <span>Ao reprovar este parceiro, ele não poderá receber itens para devolução e não terá acesso ao sistema.</span>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <textarea class="form-control" id="motivo_reprovacao" name="motivo_reprovacao" style="height: 120px" required placeholder="Informe o motivo"></textarea>
                                                        <label for="motivo_reprovacao">Motivo da reprovação</label>
                                                        <div class="form-text">Este motivo será enviado ao parceiro por e-mail.</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                        <i class="fas fa-ban me-2"></i>Confirmar Reprovação
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection