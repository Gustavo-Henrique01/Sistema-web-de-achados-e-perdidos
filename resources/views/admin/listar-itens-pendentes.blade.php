<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2>Itens Pendentes</h2>
    <div class="row">
        @if ($itens->isEmpty())
            <div class="col-12">
                <p class="text-center text-muted">Nenhum item pendente encontrado.</p>
            </div>
        @else
            @foreach ($itens as $item)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <!-- Foto -->
                        <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/default-item.png') }}" 
                             class="card-img-top" alt="Foto do item">
                        <div class="card-body">
                            <!-- Categoria -->
                            <h5 class="card-title">Categoria: {{ $item->categoria }}</h5>
                            <!-- Descrição -->
                            <p class="card-text">Descrição: {{ $item->descricao }}</p>
                            <p class="card-text">Tipo: {{ ucfirst($item->tipo) }}</p>
                            <!-- Data de Registro -->
                            <p class="card-text">
                                <small class="text-muted">Registrado em: {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</small>
                            </p>
                            <!-- Botões de Ação -->
                            <form action="{{ route('admin.itens.aprovar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Aprovar</button>
                            </form>
                            <form action="{{ route('admin.itens.rejeitar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Rejeitar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
