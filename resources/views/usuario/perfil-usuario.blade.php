@extends('usuario.home')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Card de Informações do Usuário -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">Informações do Usuário</h5>
                    </div>
                    <div class="card-body">
                        @if ($usuario->foto)
                            <img src="{{ asset('storage/' . $usuario->foto) }}" alt="Foto do Usuário" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                <span>Sem Foto</span>
                            </div>
                        @endif
                        <p><strong>Nome:</strong> {{ $usuario->nome }}</p>
                        <p><strong>Email:</strong> {{ $usuario->email }}</p>
                        <p><strong>Telefone:</strong> {{ $usuario->telefone }}</p>
                        <p><strong>CPF:</strong> {{ $usuario->cpf }}</p>
                        <p><strong>Status:</strong> {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}</p>
                        <p><strong>Função:</strong> {{ $usuario->role->value }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Lista de Itens Cadastrados pelo Usuário em Cards -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title">Itens Cadastrados</h5>
                    </div>
                    <div class="card-body">
                        @if ($usuario->itens->isEmpty())
                            <p class="text-muted">Nenhum item cadastrado.</p>
                        @else
                            <div class="row">
                                @foreach ($usuario->itens as $item)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            @if ($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto do Item" class="card-img-top" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                                    <span>Sem Foto</span>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $item->categoria }}</h5>
                                                <p class="card-text"><strong>Descrição:</strong> {{ $item->descricao }}</p>
                                                <p class="card-text"><strong>Data de Registro:</strong> {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</p>
                                                <p class="card-text"><strong>Status:</strong> {{ $item->status }}</p>
                                                <p class="card-text"><strong>Tipo:</strong> {{ $item->tipo }}</p>
                                                <p class="card-text"><strong>Endereço:</strong> {{ $item->endereco->logradouro ?? 'N/A' }}</p>
                                                
                                            </div>
                                            <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-primary">Editar</a>
                                            
                                            <form action="{{ route('usuario.deletar-item', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@endsection
