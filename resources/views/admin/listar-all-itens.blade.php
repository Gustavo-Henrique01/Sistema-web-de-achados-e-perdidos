@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Gerenciar Itens</h1>

    <!-- Filtro de Status -->
    <form method="GET" action="{{ route('admin.listar-itens') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="todos" {{ $status == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="pendente" {{ $status == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                    <option value="aprovado" {{ $status == 'aprovado' ? 'selected' : '' }}>Aprovados</option>
                    <option value="reprovado" {{ $status == 'reprovado' ? 'selected' : '' }}>Reprovados</option>
                </select>
            </div>
        </div>
    </form>
    

    <!-- Exibição dos Itens -->
    @if ($itens->isEmpty())
        <p class="text-muted">Nenhum item encontrado.</p>
    @else
        <div class="row">
            @foreach ($itens as $item)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">{{ $item->categoria->nome_categoria }}

                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Foto -->
                            <div class="text-center mb-3">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                @else
                                    <p class="text-muted">Sem foto disponível</p>
                                @endif
                            </div>
                            <p><strong>Descrição:</strong> {{ $item->descricao }}</p>
                            <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</p>
                            <p><strong>Usuário:</strong> 
                                <a href="{{ route('admin.perfilUser', $item->usuario->id) }}">
                                    {{ $item->usuario->nome ?? 'Usuário não encontrado' }}
                                </a>
                            </p>
                            <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                        </div>
                        <div class="card-footer text-center">
                            <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm me-2">
                                    <i class="bi bi-check-circle"></i> Aprovar
                                </button>
                            </form>
                            <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-x-circle"></i> Rejeitar
                                </button>
                            </form>
                            <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-4">
            {{ $itens->links() }}
        </div>
    @endif
</div>
@endsection
