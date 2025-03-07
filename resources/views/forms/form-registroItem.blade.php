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
                <label for="id_categoria" class="form-label">Categoria</label>
                <select name="id_categoria" id="id_categoria" class="form-select" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('id_categoria', $item->id_categoria ?? '') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome_categoria }}
                        </option>
                    @endforeach
                </select>
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

            <!-- Data de Perdido ou Encontrado -->
            <div class="mb-3" id="campo_data_perdido" style="display: {{ old('tipo', $item->tipo ?? '') == 'perdido' ? 'block' : 'none' }};">
                <label for="data_perdido" class="form-label">Data em que o item foi perdido</label>
                <input type="date" name="data_perdido" id="data_perdido" class="form-control" value="{{ old('data_perdido', $item->data_perdido ?? '') }}">
            </div>
            <div class="mb-3" id="campo_data_encontrado" style="display: {{ old('tipo', $item->tipo ?? '') == 'achado' ? 'block' : 'none' }};">
                <label for="data_encontrado" class="form-label">Data em que o item foi encontrado</label>
                <input type="date" name="data_encontrado" id="data_encontrado" class="form-control" value="{{ old('data_encontrado', $item->data_encontrado ?? '') }}">
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

            <!-- Localização -->
                <!-- Localização -->
   <!-- Localização -->
<div class="mb-3 border p-3 rounded">
    <h4 class="mb-3">Informe o Local onde o item foi perdido ou achado</h4>

    <!-- Campo de endereço com autocomplete -->
    <div class="mb-3">
        <label for="endereco" class="form-label">Pesquisar Endereço</label>
        <div id="endereco" class="autocomplete-container"></div>
        <input type="hidden" name="endereco" id="endereco_input">
    </div>

    <!-- Campos ocultos para latitude e longitude -->
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    <!-- Campo para nome do local -->
    <div class="mb-3">
        <label for="nome_local" class="form-label">Nome do Local</label>
        <input type="text" name="nome_local" id="nome_local" class="form-control" placeholder="Ex: Shopping Campo Grande" required>
    </div>

    <!-- Campo para referência -->
    <div class="mb-3">
        <label for="referencia" class="form-label">Referência</label>
        <input type="text" name="referencia" id="referencia" class="form-control" placeholder="Ex: Próximo ao Banco do Brasil">
    </div>

    
            <!-- Botão de envio -->
            <button type="submit" class="btn btn-primary">
                {{ isset($item) ? 'Atualizar Item' : 'Registrar Item' }}
            </button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Biblioteca do Geoapify Autocomplete -->
    <script src="https://unpkg.com/@geoapify/geocoder-autocomplete@1.x/dist/index.min.js"></script>

    <!-- Script para o autocompletar de endereços -->
    <script>
      // Chave de API do Geoapify
      const myAPIKey = 'ae3d02cb3064452fbe92218ccb0bc14f'; // Substitua pela sua chave de API

// Configuração do autocomplete para o campo de endereço
const enderecoInput = new autocomplete.GeocoderAutocomplete(
    document.getElementById("endereco"),
    myAPIKey, {
        type: "street", // Tipo de busca (rua)
        allowNonVerifiedHouseNumber: true,
        allowNonVerifiedStreet: true,
        skipDetails: false, // Precisamos dos detalhes completos
        skipIcons: true,
        placeholder: "Digite o endereço"
    }
);

// Evento de seleção de um endereço
enderecoInput.on('select', (endereco) => {
    if (endereco) {
        // Preenche os campos de latitude e longitude
        document.getElementById('latitude').value = endereco.properties.lat; // Latitude
        document.getElementById('longitude').value = endereco.properties.lon; // Longitude

        // Preenche o campo de endereço visível e oculto
        document.getElementById('endereco').value = endereco.properties.formatted || '';
        document.getElementById('endereco_input').value = endereco.properties.formatted || '';

        // Verifica se o endereço está em Campo Grande, MS
        const cidade = endereco.properties.city;
        const estado = endereco.properties.state;

        if (cidade === 'Campo Grande' && estado === 'MS') {
            document.getElementById('enderecoError').style.display = 'none';
        } else {
            document.getElementById('enderecoError').style.display = 'block';
        }
    }
});

// Evento de mudança no campo de endereço
enderecoInput.on('change', (endereco) => {
    if (!endereco) {
        // Limpa os campos se o endereço for apagado
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('endereco_input').value = '';
    }
});
        document.addEventListener('DOMContentLoaded', function() {
    const tipoAchado = document.getElementById('tipoAchado');
    const tipoPerdido = document.getElementById('tipoPerdido');
    const campoDataAchado = document.getElementById('campo_data_encontrado');
    const campoDataPerdido = document.getElementById('campo_data_perdido');

    tipoAchado.addEventListener('change', function() {
        campoDataAchado.style.display = 'block';
        campoDataPerdido.style.display = 'none';
    });

    tipoPerdido.addEventListener('change', function() {
        campoDataPerdido.style.display = 'block';
        campoDataAchado.style.display = 'none';
    });
});
    </script>

    <!-- Estilos para o container de sugestões -->
    <style>
        /* Estilo para o container do autocomplete */
        .autocomplete-container {
            position: relative;
        }
    
        /* Estilo para o input do autocomplete */
        .geoapify-autocomplete-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
    
        /* Estilo para o container das sugestões */
        .geoapify-autocomplete-items {
            position: absolute;
            border: 1px solid #ccc;
            border-top: none;
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
            background-color: #fff;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    
        /* Estilo para cada item da lista de sugestões */
        .geoapify-autocomplete-items div {
            padding: 8px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
    
        /* Estilo para o hover nos itens da lista */
        .geoapify-autocomplete-items div:hover {
            background-color: #f1f1f1;
        }
    
        /* Estilo para o item ativo (selecionado com as setas do teclado) */
        .geoapify-autocomplete-items .active {
            background-color: #e9e9e9;
        }
    </style>
@endsection