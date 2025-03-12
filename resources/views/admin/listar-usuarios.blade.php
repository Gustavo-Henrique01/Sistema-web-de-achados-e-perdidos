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
            @foreach ($users as $user) <!-- Alterado de $usuarios para $users -->
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <a href="{{ route('admin.perfilUser', $user->id) }}">{{ $user->name }}</a> <!-- Alterado de 'nome' para 'name' -->
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telefone ?? 'Não informado' }}</td>
                    <td>{{ $user->cpf }}</td>
                    <td>
                        @if ($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto do usuário" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <span>Sem foto</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($user->role->value) }}</td>
                    <td>
                        <form action="{{ route('admin.deletar-usuario', $user->id) }}" method="POST" class="d-inline">
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