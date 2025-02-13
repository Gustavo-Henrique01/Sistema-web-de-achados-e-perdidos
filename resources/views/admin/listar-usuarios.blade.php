@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Usuários Cadastrados</h1>

 
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Foto</th>
                <th>Role</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td> <a href="{{ route('admin.perfilUser', $item->usuario->id) }}">{{ $usuario->nome }} </a> </td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->telefone ?? 'Não informado' }}</td>
                    <td>{{ $usuario->cpf }}</td>
                    <td>
                        @if ($usuario->foto)
                            <img src="{{ $usuario->foto }}" alt="Foto do usuário" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span>Sem foto</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($usuario->role->value) }}</td>
                    <td>
                        <form action="{{ route('admin.deletar-usuario', $usuario->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection