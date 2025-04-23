@extends('layouts.parceiro')

@section('title', 'Transferências Pendentes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Transferências Pendentes</h1>
    </div>

    <!-- Lista de Transferências Pendentes -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($transferencias->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <h4>Nenhuma transferência pendente</h4>
                    <p class="text-muted">Não há itens aguardando recebimento no momento.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Usuário</th>
                                <th>Data</th>
                                <th>Observações</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transferencias as $transferencia)
                                <tr>
                                    <td>
                                        <a href="{{ route('parceiro.itens.show', $transferencia->item) }}" class="text-decoration-none">
                                            {{ Str::limit($transferencia->item->descricao, 50) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $transferencia->item->categoria->nome_categoria }}
                                        </small>
                                    </td>
                                    <td>
                                        {{ $transferencia->usuario->name }}
                                        <br>
                                        <small class="text-muted">{{ $transferencia->usuario->email }}</small>
                                    </td>
                                    <td>
                                        {{ $transferencia->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $transferencia->observacoes ?? 'Nenhuma' }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <form action="{{ route('parceiro.itens.confirmar-recebimento', $transferencia->item) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Confirmar
                                                </button>
                                            </form>
                                            
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejeitarModal{{ $transferencia->id }}">
                                                <i class="fas fa-times"></i> Rejeitar
                                            </button>
                                        </div>

                                        <!-- Modal de Rejeição -->
                                        <div class="modal fade" id="rejeitarModal{{ $transferencia->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rejeitar Transferência</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('parceiro.itens.rejeitar', $transferencia->item) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="motivo" class="form-label">Motivo da Rejeição</label>
                                                                <textarea class="form-control" 
                                                                          id="motivo" 
                                                                          name="motivo" 
                                                                          rows="3" 
                                                                          required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Confirmar Rejeição</button>
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
            @endif
        </div>
    </div>
</div>
@endsection 