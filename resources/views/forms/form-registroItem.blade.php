@extends('usuario.home')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Leaflet GeoSearch CSS e JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch/dist/geosearch.css" />
<script src="https://unpkg.com/leaflet-geosearch/dist/geosearch.umd.js"></script>

<style>
    /* Estilo para o mapa */
    #map {
        height: 400px;
        margin-bottom: 20px;
    }

    /* Ajustes para a barra de pesquisa */
    .leaflet-control-geosearch {
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .leaflet-control-geosearch input {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .leaflet-control-geosearch ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-height: 200px;
        overflow-y: auto;
    }

    .leaflet-control-geosearch ul li {
        padding: 8px;
        font-size: 14px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .leaflet-control-geosearch ul li:hover {
        background-color: #f0f0f0;
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4">{{ isset($item) ? 'Editar Item' : 'Registrar Item' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('registrar-item') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Categoria -->
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select name="categoria" id="categoria" class="form-select" required>
                <option value="" disabled>Selecione uma categoria</option>
                <option value="Documentos">Documentos</option>
                <option value="Eletrônicos">Eletrônicos</option>
                <option value="Acessórios">Acessórios</option>
                <option value="Roupas">Roupas</option>
                <option value="Outros">Outros</option>
            </select>
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
            <input type="file" name="foto" id="foto" class="form-control" required>
        </div>

        <!-- Endereço -->
        <div class="mb-3 border p-3 rounded">
            <h4 class="mb-3">Informe o Local onde perdido ou achado o Item</h4>

            <!-- Mapa -->
            <div id="map"></div>

            <!-- Campos ocultos para latitude e longitude -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

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
                <label for="referencial" class="form-label">Referência (opcional)</label>
                <textarea name="referencial" id="referencial" class="form-control" rows="2" placeholder="Ponto de referência"></textarea>
            </div>
        </div>

        <!-- Botão de envio -->
        <button type="submit" class="btn btn-primary mt-3">Registrar Item</button>
    </form>

    <script>
        // Inicializa o mapa
        var map = L.map('map').setView([-20.4697, -54.6201], 13); // Coordenadas de Campo Grande
    
        // Adiciona o tile layer do OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    
        // Define os limites (bounds) para Campo Grande, MS
        const bounds = [
            [-54.720, -20.592], // Canto sudoeste (lng, lat)
            [-54.520, -20.347]  // Canto nordeste (lng, lat)
        ];
    
        // Configura o Leaflet GeoSearch com os limites
        const provider = new GeoSearch.OpenStreetMapProvider({
            params: {
                bounded: 1, // Ativa a restrição por bounds
                viewbox: bounds.flat().join(','), // Define a área de restrição
                countrycodes: 'br' // Restringe a busca ao Brasil
            }
        });
    
        const searchControl = new GeoSearch.GeoSearchControl({
            provider: provider,
            style: 'bar', // Estilo da barra de pesquisa
            showMarker: true, // Mostrar marcador no mapa
            autoClose: true, // Fechar a lista de sugestões após seleção
            retainZoomLevel: false, // Manter o nível de zoom ao selecionar um local
            animateZoom: true, // Animação ao mudar o zoom
            searchLabel: 'Digite o endereço em Campo Grande', // Placeholder da barra de pesquisa
            keepResult: true // Manter o resultado no mapa após a seleção
        });
    
        // Adiciona o controle de pesquisa ao mapa
        map.addControl(searchControl);
    
        // Evento quando um endereço é selecionado
        map.on('geosearch/showlocation', function(result) {
            const location = result.location;
            const address = result.location.label; // Endereço completo
            const parts = address.split(','); // Divide o endereço em partes
    
            // Preenche os campos de endereço
            document.getElementById('rua').value = parts[0] || '';
            document.getElementById('numero').value = parts[1] || '';
            document.getElementById('bairro').value = parts[2] || '';
            document.getElementById('latitude').value = location.y; // Latitude
            document.getElementById('longitude').value = location.x; // Longitude
        });
    </script>
</div>
@endsection