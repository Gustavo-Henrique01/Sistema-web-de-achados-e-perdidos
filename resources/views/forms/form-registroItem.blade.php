@extends('usuario.home')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Editar Item</h1>
        <form action="{{ isset($item) ? route('usuario.atualizar-item', $item->id) : route('registrar-item') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($item))
                @method('PUT') <!-- Método PUT para atualização -->
          
                @endif

            <!-- Categoria -->
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select name="categoria" id="categoria" class="form-select" required>
                    <option value="" disabled>Selecione uma categoria</option>
                    <option value="Documentos" {{ old('categoria', $item->categoria) == 'Documentos' ? 'selected' : '' }}>Documentos</option>
                    <option value="Eletrônicos" {{ old('categoria', $item->categoria) == 'Eletrônicos' ? 'selected' : '' }}>Eletrônicos</option>
                    <option value="Acessórios" {{ old('categoria', $item->categoria) == 'Acessórios' ? 'selected' : '' }}>Acessórios</option>
                    <option value="Roupas" {{ old('categoria', $item->categoria) == 'Roupas' ? 'selected' : '' }}>Roupas</option>
                    <option value="Outros" {{ old('categoria', $item->categoria) == 'Outros' ? 'selected' : '' }}>Outros</option>
                </select>
            </div>
            <div class="mb-3" id="categoriaOutros" style="display: {{ old('categoria', $item->categoria) == 'Outros' ? 'block' : 'none' }};">
                <label for="categoriaOutrosInput" class="form-label">Digite a categoria</label>
                <input type="text" class="form-control" id="categoriaOutrosInput" name="categoriaOutros" placeholder="Digite a categoria" value="{{ old('categoriaOutros', $item->categoria == 'Outros' ? $item->categoria : '') }}">
            </div>

            <!-- Tipo -->
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoAchado" name="tipo" value="achado" class="form-check-input" required {{ old('tipo', $item->tipo) == 'achado' ? 'checked' : '' }}>
                        <label for="tipoAchado" class="form-check-label">Achado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoPerdido" name="tipo" value="perdido" class="form-check-input" required {{ old('tipo', $item->tipo) == 'perdido' ? 'checked' : '' }}>
                        <label for="tipoPerdido" class="form-check-label">Perdido</label>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descreva o item (cor, características, etc.)" required>{{ old('descricao', $item->descricao) }}</textarea>
            </div>

            <!-- Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control">
                @if ($item->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto do Item" class="img-fluid" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <!-- Formulário de Endereço -->
            <div class="mb-3 border p-3 rounded">
                <h4 class="mb-3">Informe o Local onde perdido ou achado o Item</h4>
                <!-- Rua -->
                <div class="mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" name="rua" id="rua" class="form-control" placeholder="Digite a rua" value="{{ old('rua', $item->endereco->rua) }}" required>
                </div>

                <!-- Número -->
                <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" name="numero" id="numero" class="form-control" placeholder="Digite o número" value="{{ old('numero', $item->endereco->numero) }}">
                </div>

                <!-- Bairro -->
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Digite o bairro" value="{{ old('bairro', $item->endereco->bairro) }}" required>
                </div>

                <!-- Referência -->
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referêncial (opcional)</label>
                    <textarea name="referencial" id="referencial" class="form-control" rows="2" placeholder="Ponto de referência">{{ old('referencial', $item->endereco->referencial) }}</textarea>
                </div>
            </div>

            <!-- Botão de envio -->
            <button type="submit" class="btn btn-primary">Atualizar Item</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Função para exibir o campo de texto se "Outros" for selecionado
        document.getElementById('categoria').addEventListener('change', function () {
            var categoria = this.value;
            var categoriaOutrosInput = document.getElementById('categoriaOutros');
            
            // Verifica se a categoria selecionada é "Outros"
            if (categoria === 'Outros') {
                categoriaOutrosInput.style.display = 'block'; // Exibe o campo de texto
            } else {
                categoriaOutrosInput.style.display = 'none'; // Esconde o campo de texto
            }
        });

        // Exibe o campo "Outros" se já estiver selecionado ao carregar a página
        document.addEventListener('DOMContentLoaded', function () {
            var categoria = document.getElementById('categoria').value;
            var categoriaOutrosInput = document.getElementById('categoriaOutros');
            
            if (categoria === 'Outros') {
                categoriaOutrosInput.style.display = 'block';
            }
        });
    </script>
@endsection