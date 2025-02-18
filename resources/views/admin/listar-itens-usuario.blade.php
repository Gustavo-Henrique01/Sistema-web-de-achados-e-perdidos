@extends('admin.dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2>Perfil do Usuário</h2>
    <div class="card mb-4">
        <div class="card-body text-center">
            <img src="{{ asset('storage/'.$usuario->foto) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Foto do usuário">
            <h4>{{ $usuario->nome }}</h4>
            <p>Email: {{ $usuario->email }}</p>
            <p>Telefone: {{ $usuario->telefone }}</p>
            <p>CPF: {{ $usuario->cpf }}</p>
            <p>Status: {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}</p>
        </div>
    </div>
    <h3>Itens cadastrados por {{ $usuario->nome }} (ID: {{ $usuario->id }})</h3>
    <a href="{{ route('admin.listar-itens-all') }}" class="btn btn-secondary mb-3">Voltar para a listagem geral</a>
    <div class="row">
        @foreach ($itens as $item)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset('storage/'.$item->foto) }}" class="card-img-top" alt="Foto do item">
                    <div class="card-body">
                        <h5 class="card-title">Categoria: {{ $item->categoria }}</h5>
                        <p class="card-text">Descrição: {{ $item->descricao }}</p>
                        <p class="card-text">Tipo: {{ $item->tipo }}</p>
                        <p class="card-text">
                            <small class="text-muted">Registrado em: {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</small>
                        </p>
                        <p class="card-text">Status: {{ $item->status }}</p>
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
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
