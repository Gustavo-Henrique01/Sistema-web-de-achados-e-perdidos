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
                <!-- Lista de Itens Cadastrados pelo Usuário -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title">Itens Cadastrados</h5>
                    </div>
                    <div class="card-body">
                        @if ($usuario->itens->isEmpty())
                            <p class="text-muted">Nenhum item cadastrado.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th>Data de Registro</th>
                                            <th>Status</th>
                                            <th>Tipo</th>
                                            <th>Endereço</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuario->itens as $item)
                                            <tr>
                                                <td>{{ $item->categoria }}</td>
                                                <td>{{ $item->descricao }}</td>
                                                <td>{{ $item->data_registro->format('d/m/Y') }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->tipo }}</td>
                                                <td>{{ $item->endereco->logradouro ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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