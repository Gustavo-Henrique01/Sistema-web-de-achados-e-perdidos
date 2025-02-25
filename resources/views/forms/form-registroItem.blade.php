@extends('usuario.home')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">{{ isset($item) ? 'Editar Item' : 'Registrar Item' }}</h1>
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
                    <option value="Documentos" {{ old('categoria', $item->categoria ?? '') == 'Documentos' ? 'selected' : '' }}>Documentos</option>
                    <option value="Eletrônicos" {{ old('categoria', $item->categoria ?? '') == 'Eletrônicos' ? 'selected' : '' }}>Eletrônicos</option>
                    <option value="Acessórios" {{ old('categoria', $item->categoria ?? '') == 'Acessórios' ? 'selected' : '' }}>Acessórios</option>
                    <option value="Roupas" {{ old('categoria', $item->categoria ?? '') == 'Roupas' ? 'selected' : '' }}>Roupas</option>
                    <option value="Outros" {{ old('categoria', $item->categoria ?? '') == 'Outros' ? 'selected' : '' }}>Outros</option>
                </select>
            </div>
            <div class="mb-3" id="categoriaOutros" style="display: {{ old('categoria', $item->categoria ?? '') == 'Outros' ? 'block' : 'none' }};">
                <label for="categoriaOutrosInput" class="form-label">Digite a categoria</label>
                <input type="text" class="form-control" id="categoriaOutrosInput" name="categoriaOutros" placeholder="Digite a categoria" value="{{ old('categoriaOutros', $item->categoria ?? '') }}">
            </div>

            <!-- Tipo -->
            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoAchado" name="tipo" value="achado" class="form-check-input" required {{ old('tipo', $item->tipo ?? '') == 'achado' ? 'checked' : '' }}>
                        <label for="tipoAchado" class="form-check-label">Achado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoPerdido" name="tipo" value="perdido" class="form-check-input" required {{ old('tipo', $item->tipo ?? '') == 'perdido' ? 'checked' : '' }}>
                        <label for="tipoPerdido" class="form-check-label">Perdido</label>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descreva o item (cor, características, etc.)" required>{{ old('descricao', $item->descricao ?? '') }}</textarea>
            </div>

            <!-- Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" id="foto" class="form-control">
                @if (isset($item) && $item->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto do Item" class="img-fluid" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            <!-- Formulário de Endereço -->
            <div class="mb-3 border p-3 rounded">
                <h4 class="mb-3">Informe o Local onde perdido ou achado o Item</h4>

                <!-- Campo de busca de endereço -->
                <div class="mb-3">
                    <label for="endereco" class="form-label">Buscar Endereço</label>
                    <input type="text" id="endereco" class="form-control" placeholder="Digite o endereço">
                    <div id="sugestoes" class="list-group mt-2" style="display: none;"></div> <!-- Lista de sugestões -->
                </div>

                <!-- Mapa para visualização -->
                <div id="map" style="height: 300px; width: 100%;" class="mb-3"></div>

                <!-- Rua -->
                <div class="mb-3">
                    <label for="rua" class="form-label">Rua</label>
                    <input type="text" name="rua" id="rua" class="form-control" placeholder="Digite a rua" value="{{ old('rua', $item->endereco->rua ?? '') }}" required>
                </div>

                <!-- Número -->
                <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" name="numero" id="numero" class="form-control" placeholder="Digite o número" value="{{ old('numero', $item->endereco->numero ?? '') }}">
                </div>

                <!-- Bairro -->
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="form-control" placeholder="Digite o bairro" value="{{ old('bairro', $item->endereco->bairro ?? '') }}" required>
                </div>

                <!-- Referência -->
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referência (opcional)</label>
                    <textarea name="referencia" id="referencia" class="form-control" rows="2" placeholder="Ponto de referência">{{ old('referencia', $item->endereco->referencia ?? '') }}</textarea>
                </div>

                <!-- Mensagem de erro de endereço -->
                <div id="enderecoError" class="alert alert-danger mt-3" style="display: none;">Endereço inválido ou fora de Campo Grande, MS.</div>
            </div>

            <!-- Botão de envio -->
            <button type="submit" class="btn btn-primary">
                {{ isset($item) ? 'Atualizar Item' : 'Registrar Item' }}
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS (para o mapa) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Token de acesso do LocationIQ
        const token = 'pk.e9f555d4e9c8aa5e6015c55fa37a16f4';

        // Coordenadas de Campo Grande (latitude, longitude)
        const campoGrandeBounds = {
            latMin: -20.551,
            lonMin: -54.722,
            latMax: -20.350,
            lonMax: -54.480,
        };

        // Inicializa o mapa
        const map = L.map('map').setView([-20.469, -54.622], 13); // Centro de Campo Grande
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Função para buscar sugestões de endereços
        document.getElementById('endereco').addEventListener('input', function (e) {
            const query = e.target.value;

            // Faz a requisição para a API de Autocomplete do LocationIQ
            fetch(`https://api.locationiq.com/v1/autocomplete?key=${token}&q=${query}&limit=5&countrycodes=br&bounded=1&viewbox=${campoGrandeBounds.lonMin},${campoGrandeBounds.latMin},${campoGrandeBounds.lonMax},${campoGrandeBounds.latMax}`)
                .then(response => response.json())
                .then(data => {
                    const sugestoes = document.getElementById('sugestoes');
                    sugestoes.innerHTML = ''; // Limpa as sugestões anteriores

                    if (data.length > 0) {
                        data.forEach(result => {
                            const item = document.createElement('div');
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = result.display_name;
                            item.addEventListener('click', () => {
                                // Preenche os campos de endereço
                                document.getElementById('endereco').value = result.display_name;
                                document.getElementById('rua').value = result.address.road || '';
                                document.getElementById('numero').value = result.address.house_number || '';
                                document.getElementById('bairro').value = result.address.suburb || result.address.neighbourhood || '';

                                // Centraliza o mapa no local selecionado
                                map.setView([result.lat, result.lon], 15);

                                // Adiciona um marcador no local
                                L.marker([result.lat, result.lon]).addTo(map)
                                    .bindPopup('Local selecionado')
                                    .openPopup();

                                // Esconde as sugestões
                                sugestoes.style.display = 'none';
                            });
                            sugestoes.appendChild(item);
                        });

                        // Exibe as sugestões
                        sugestoes.style.display = 'block';
                    } else {
                        sugestoes.style.display = 'none';
                    }
                })
                .catch(error => console.error('Erro ao buscar sugestões:', error));
        });

        // Função para validar o endereço manualmente
        function validarEndereco() {
            const rua = document.getElementById('rua').value;
            const numero = document.getElementById('numero').value;
            const bairro = document.getElementById('bairro').value;

            if (!rua || !bairro) {
                document.getElementById('enderecoError').style.display = 'block';
                return false;
            }

            // Faz a requisição para a API de Geocodificação do LocationIQ
            fetch(`https://api.locationiq.com/v1/search.php?key=${token}&q=${rua}+${numero}+${bairro}+Campo+Grande+MS&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Verifica se o endereço está dentro dos limites de Campo Grande
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        if (
                            lat >= campoGrandeBounds.latMin &&
                            lat <= campoGrandeBounds.latMax &&
                            lon >= campoGrandeBounds.lonMin &&
                            lon <= campoGrandeBounds.lonMax
                        ) {
                            document.getElementById('enderecoError').style.display = 'none';
                            // Centraliza o mapa no local selecionado
                            map.setView([lat, lon], 15);

                            // Adiciona um marcador no local
                            L.marker([lat, lon]).addTo(map)
                                .bindPopup('Local selecionado')
                                .openPopup();
                        } else {
                            document.getElementById('enderecoError').style.display = 'block';
                        }
                    } else {
                        document.getElementById('enderecoError').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erro ao validar endereço:', error);
                    document.getElementById('enderecoError').style.display = 'block';
                });
        }

        // Valida o endereço quando o usuário sai dos campos de endereço
        document.getElementById('rua').addEventListener('blur', validarEndereco);
        document.getElementById('numero').addEventListener('blur', validarEndereco);
        document.getElementById('bairro').addEventListener('blur', validarEndereco);

        // Função para exibir o campo de texto se "Outros" for selecionado
        document.getElementById('categoria').addEventListener('change', function () {
            var categoria = this.value;
            var categoriaOutrosInput = document.getElementById('categoriaOutros');
            
            if (categoria === 'Outros') {
                categoriaOutrosInput.style.display = 'block';
            } else {
                categoriaOutrosInput.style.display = 'none';
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