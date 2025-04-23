@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gerenciamento de Parceiros</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Parceiros</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Estabelecimento</th>
                            <th>CNPJ</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parceiros as $parceiro)
                        <tr>
                            <td>{{ $parceiro->id }}</td>
                            <td>{{ $parceiro->nome_estabelecimento }}</td>
                            <td>{{ $parceiro->cnpj }}</td>
                            <td>
                                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                    <span class="badge bg-info">Ponto de Coleta</span>
                                @elseif($parceiro->tipo_parceiro == 'evento')
                                    <span class="badge bg-primary">Evento</span>
                                @else
                                    <span class="badge bg-secondary">Ambos</span>
                                @endif
                            </td>
                            <td>
                                @if($parceiro->status == 'aprovado')
                                    <span class="badge bg-success">Aprovado</span>
                                @elseif($parceiro->status == 'reprovado')
                                    <span class="badge bg-danger">Reprovado</span>
                                @else
                                    <span class="badge bg-warning">Pendente</span>
                                @endif
                            </td>
                            <td>{{ $parceiro->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.parceiros.show', $parceiro->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($parceiro->status == 'pendente')
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#aprovarModal{{ $parceiro->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reprovarModal{{ $parceiro->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Modal de Aprovação -->
                                <div class="modal fade" id="aprovarModal{{ $parceiro->id }}" tabindex="-1" aria-labelledby="aprovarModalLabel{{ $parceiro->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="aprovarModalLabel{{ $parceiro->id }}">Aprovar Parceiro</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.parceiros.aprovar', $parceiro->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Tem certeza que deseja aprovar o parceiro <strong>{{ $parceiro->nome_estabelecimento }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Aprovar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de Reprovação -->
                                <div class="modal fade" id="reprovarModal{{ $parceiro->id }}" tabindex="-1" aria-labelledby="reprovarModalLabel{{ $parceiro->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reprovarModalLabel{{ $parceiro->id }}">Reprovar Parceiro</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.parceiros.reprovar', $parceiro->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="motivo_reprovacao" class="form-label">Motivo da Reprovação</label>
                                                        <textarea class="form-control" id="motivo_reprovacao" name="motivo_reprovacao" rows="3" required></textarea>
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