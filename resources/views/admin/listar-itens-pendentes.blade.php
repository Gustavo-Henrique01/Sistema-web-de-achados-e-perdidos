@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Itens Pendentes</h1>

    @if ($itens->isEmpty())
        <p class="text-muted">Nenhum item pendente encontrado.</p>
    @else
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                    <th>Foto</th>
                    <th>Data de Registro</th>
                    <th>Usuário</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($itens as $item)
                    <tr>
                        <td class="text-center">{{ $item->id }}</td>
                        <td>{{ $item->categoria }}</td>
                        <td>{{ $item->descricao }}</td>
                        <!-- Foto -->
                        <td class="text-center">
                            @if ($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <span class="text-muted">Sem foto</span>
                            @endif
                        </td>
                        <!-- Data de Registro -->
                        <td>{{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</td>
                        <!-- Usuário -->
                        <td>
                            <a href="{{ route('admin.ver-usuario-perfil', $item->usuario->id) }}">
                                {{ $item->usuario->nome ?? 'Usuário não encontrado' }}
                            </a>
                        </td>
                        
                        <td>
                            @if ($item->endereco)
                                {{ $item->endereco->rua }}, {{ $item->endereco->numero ?? 'S/N' }} - {{ $item->endereco->bairro }}
                            @else
                                <span class="text-muted">Endereço não encontrado</span>
                            @endif
                        </td>
                        <!-- Ações -->
                        <td class="text-center">
                            <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Aprovar item">
                                    <i class="bi bi-check-circle"></i> Aprovar
                                </button>
                            </form>
                            <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Rejeitar item">
                                    <i class="bi bi-x-circle"></i> Rejeitar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginação -->
        <div class="d-flex justify-content-center">
            {{ $itens->links() }}
        </div>
    @endif
</div>
@endsection