@extends('admin.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Itens do Parceiro: {{ $parceiro->nome_estabelecimento }}</h5>
                    <div>
                        <a href="{{ route('admin.parceiros.show', $parceiro) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Voltar para Detalhes
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($itens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagem</th>
                                    <th>Descrição</th>
                                    <th>Categoria</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itens as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->fotos->count() > 0)
                                            <img src="{{ asset('storage/' . $item->fotos->where('is_principal', true)->first()->caminho ?? $item->fotos->first()->caminho) }}" 
                                                alt="{{ $item->descricao }}" 
                                                class="img-thumbnail" 
                                                style="max-width: 50px; max-height: 50px;">
                                        @else
                                            <div class="bg-light text-center rounded p-2">
                                                <i class="fas fa-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->descricao, 50) }}</td>
                                    <td>{{ $item->categoria->nome_categoria ?? 'N/A' }}</td>
                                    <td>
                                        @if($item->tipo == 'achado')
                                            <span class="badge bg-success">Achado</span>
                                        @elseif($item->tipo == 'perdido')
                                            <span class="badge bg-warning">Perdido</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->tipo }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'aprovado')
                                            <span class="badge bg-success">Aprovado</span>
                                        @elseif($item->status == 'pendente')
                                            <span class="badge bg-warning">Pendente</span>
                                        @elseif($item->status == 'reprovado')
                                            <span class="badge bg-danger">Reprovado</span>
                                        @elseif($item->status == 'em_transferencia')
                                            <span class="badge bg-info">Em Transferência</span>
                                        @elseif($item->status == 'em_estabelecimento')
                                            <span class="badge bg-primary">No Estabelecimento</span>
                                        @elseif($item->status == 'devolvido')
                                            <span class="badge bg-secondary">Devolvido</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.ver-item', $item->id) }}" class="btn btn-info" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $itens->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Este parceiro não possui itens associados.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 