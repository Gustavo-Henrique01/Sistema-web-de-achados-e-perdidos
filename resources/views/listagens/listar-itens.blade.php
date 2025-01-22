
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2>Listagem de Itens</h2>
    <div class="row">
        @foreach ($itens as $item)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <!-- Foto -->
                    <img src="{{ asset('storage/'.$item->foto) }}" class="card-img-top" alt="Foto do item">
                    <div class="card-body">
                        <!-- Categoria -->
                        <h5 class="card-title">Categoria: {{ $item->categoria }}</h5>
                        <!-- Descrição -->
                        <p class="card-text">Descrição: {{ $item->descricao }}</p>
                        <!-- Data de Registro -->
                        <p class="card-text">
                            <small class="text-muted">Registrado em: {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</small>
                        </p>
                        <!-- Botões de Ação -->
                      
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

