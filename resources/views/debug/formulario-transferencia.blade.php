<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Formulário de Transferência</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <style>
        body { padding: 20px; }
        .debug-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .debug-section { margin-bottom: 30px; }
        pre { background-color: #212529; color: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Debug Formulário de Transferência</h1>
        
        <div class="debug-info">
            <h3>Informações do Item</h3>
            <p><strong>ID:</strong> {{ $item->id }}</p>
            <p><strong>Descrição:</strong> {{ $item->descricao }}</p>
            <p><strong>Status:</strong> {{ $item->status }}</p>
            <p><strong>Usuário:</strong> {{ $item->usuario->name }} (ID: {{ $item->usuario->id }})</p>
        </div>
        
        <div class="debug-section">
            <h3>Formulário AJAX (com validação do client-side)</h3>
            <form id="ajax-form" class="mb-4">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                
                <div class="mb-3">
                    <label for="parceiro_id" class="form-label">Ponto de Coleta</label>
                    <select class="form-select" id="parceiro_id" name="parceiro_id" required>
                        <option value="">Selecione um ponto de coleta</option>
                        @foreach ($parceiros as $parceiro)
                            <option value="{{ $parceiro->id }}">
                                {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar (AJAX)</button>
            </form>
            
            <div id="ajax-result" class="alert d-none"></div>
        </div>
        
        <div class="debug-section">
            <h3>Formulário Direto (método real)</h3>
            <form action="{{ route('item.enviar-para-parceiro', ['item' => $item->id]) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <p><strong>Rota do formulário:</strong> {{ route('item.enviar-para-parceiro', ['item' => $item->id]) }}</p>
                </div>
                
                <div class="mb-3">
                    <label for="direct_parceiro_id" class="form-label">Ponto de Coleta</label>
                    <select class="form-select" id="direct_parceiro_id" name="parceiro_id" required>
                        <option value="">Selecione um ponto de coleta</option>
                        @foreach ($parceiros as $parceiro)
                            <option value="{{ $parceiro->id }}">
                                {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="direct_observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="direct_observacoes" name="observacoes" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Enviar (Direto)</button>
            </form>
        </div>
        
        <div class="debug-section">
            <h3>Teste Sequencial</h3>
            <div class="mb-4">
                <a href="/teste-transferencia/{{ $item->id }}/1" class="btn btn-warning" target="_blank">
                    Testar Transferência (Parceiro ID: 1)
                </a>
                <a href="/teste-log/{{ $item->id }}/1" class="btn btn-info ms-2" target="_blank">
                    Testar Log
                </a>
            </div>
        </div>
        
        <div class="debug-section">
            <h3>Informações dos Parceiros</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parceiros as $parceiro)
                    <tr>
                        <td>{{ $parceiro->id }}</td>
                        <td>{{ $parceiro->nome_estabelecimento }}</td>
                        <td>{{ $parceiro->localizacao->endereco }}</td>
                        <td>
                            <span class="badge {{ $parceiro->ativo ? 'bg-success' : 'bg-danger' }}">
                                {{ $parceiro->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ajaxForm = document.getElementById('ajax-form');
            const ajaxResult = document.getElementById('ajax-result');
            
            ajaxForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                ajaxResult.classList.remove('d-none', 'alert-success', 'alert-danger');
                ajaxResult.textContent = 'Enviando dados...';
                
                const formData = new FormData(ajaxForm);
                
                fetch('{{ route("debug.processamento") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    ajaxResult.classList.add('alert-success');
                    ajaxResult.innerHTML = '<p><strong>Sucesso!</strong></p><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                })
                .catch(error => {
                    ajaxResult.classList.add('alert-danger');
                    ajaxResult.innerHTML = '<p><strong>Erro!</strong></p><p>' + error.message + '</p>';
                });
            });
        });
    </script>
</body>
</html> 