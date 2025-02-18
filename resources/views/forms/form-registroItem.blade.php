@extends('usuario.home')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Item</h1>
        <form action="{{ route('registrar-item') }}" method="POST" enctype="multipart/form-data">
            <!-- Token de segurança CSRF -->
            @csrf

            <!-- Categoria -->
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select name="categoria" id="categoria" class="form-select" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Documentos">Documentos</option>
                    <option value="Eletrônicos">Eletrônicos</option>
                    <option value="Acessórios">Acessórios</option>
                    <option value="Roupas">Roupas</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
            <div class="mb-3" id="categoriaOutros" style="display: none;">
                <label for="categoriaOutrosInput" class="form-label">Digite a categoria</label>
                <input type="text" class="form-control" id="categoriaOutrosInput" name="categoriaOutros" placeholder="Digite a categoria">
            </div>

            <!-- Tipo -->
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoAchado" name="tipo" value="achado" class="form-check-input" required>
                        <label for="tipoAchado" class="form-check-label">Achado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoPerdido" name="tipo" value="perdido" class="form-check-input" required>
                        <label for="tipoPerdido" class="form-check-label">Perdido</label>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descreva o item (cor, características, etc.)" required></textarea>
            </div>

            <!-- Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control" placeholder="Insira as fotos do Item " required>
            </div>

            <!-- Formulário de Endereço -->
            <div class="mb-3 border p-3 rounded">
                <h4 class="mb-3">Informe o Local onde perdido ou achado o Item</h4>
                <!-- Rua -->
                <div class="mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" name="rua" id="rua" class="form-control" placeholder="Digite a rua" required>
                </div>

                <!-- Número -->
                <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" name="numero" id="numero" class="form-control" placeholder="Digite o número">
                </div>

                <!-- Bairro -->
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Digite o bairro" required>
                </div>

                <!-- Referência -->
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referência (opcional)</label>
                    <textarea name="referencia" id="referencia" class="form-control" rows="2" placeholder="Ponto de referência"></textarea>
                </div>
            </div>

        

            <!-- Botão de envio -->
            <button type="submit" class="btn btn-primary">Registrar Item</button>
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
    </script>
    @endsection
