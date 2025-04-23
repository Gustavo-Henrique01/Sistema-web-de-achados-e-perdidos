@extends('usuario.home')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Enviar Item para Ponto de Coleta</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Informações do Item</h5>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if ($item->fotos && $item->fotos->isNotEmpty())
                                            <img src="{{ asset('storage/' . $item->fotos->where('is_principal', true)->first()->caminho ?? $item->fotos->first()->caminho) }}" 
                                                alt="Foto do Item" class="img-fluid rounded">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="card-title">{{ $item->categoria->nome_categoria }}</h5>
                                        <p><strong>Descrição:</strong> {{ $item->descricao }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-success">{{ ucfirst($item->status) }}</span>
                                        </p>
                                        <p><strong>Local:</strong> {{ $item->localizacao->nome_local ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('item.enviar-para-parceiro', ['item' => $item->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="parceiro_id" class="form-label">Selecione o Ponto de Coleta</label>
                            <select class="form-select" name="parceiro_id" id="parceiro_id" required>
                                <option value="">Selecione um ponto de coleta</option>
                                @foreach($parceiros as $parceiro)
                                    <option value="{{ $parceiro->id }}">
                                        {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações (opcional)</label>
                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('usuario.perfil') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Confirmar Envio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 