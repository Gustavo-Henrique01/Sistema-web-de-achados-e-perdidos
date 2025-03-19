@extends('usuario.home')

@section('content')

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

    /* Estilo para o container de sugestões do Google Maps */
    .pac-container {
        background-color: #fff;
        z-index: 1000;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pac-item {
        padding: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    .pac-item:hover {
        background-color: #f1f1f1;
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

    <form action="{{ isset($item) ? route('usuario.atualizar-item', $item->id) : route('registrar-item') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($item))
            @method('PUT') <!-- Método HTTP para atualização -->
        @endif

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
            <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Descreva o item (cor, características, etc.)" required>{{ old('descricao', $item->descricao ?? '') }}</textarea>
        </div>

        <!-- Foto -->
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
        
            <!-- Exibir a foto atual, se existir -->
            @if (isset($item) && $item->foto)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto do item" style="max-width: 200px; height: auto;">
                    <p class="text-muted">Foto atual</p>
                </div>
            @endif
        
            <!-- Campo para upload de nova foto -->
            <input type="file" name="foto" id="foto" class="form-control">
            <small class="text-muted">Deixe em branco para manter a foto atual.</small>
        </div>

        <!-- Localização -->
        <div class="mb-3 border p-3 rounded">
            <h4 class="mb-3">Informe o Local onde o item foi perdido ou achado</h4>

            <!-- Campo de endereço com autocomplete do Google Maps -->
            <div class="mb-3">
                <input type="text" id="endereco" class="form-control" placeholder="Digite o endereço" value="{{ old('endereco', $item->localizacao->endereco ?? '') }}">
                <input type="hidden" name="endereco" id="endereco_input" value="{{ old('endereco', $item->localizacao->endereco ?? '') }}">
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->localizacao->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->localizacao->longitude ?? '') }}">

            <!-- Campo para nome do local -->
            <div class="mb-3">
                <label for="nome_local" class="form-label">Nome do Local</label>
                <input type="text" name="nome_local" id="nome_local" class="form-control" placeholder="Ex: Shopping Campo Grande" required value="{{ old('nome_local', $item->localizacao->nome_local ?? '') }}">
            </div>

            <!-- Campo para referência -->
            <div class="mb-3">
                <label for="referencia" class="form-label">Referêncial</label>
                <input type="text" name="referencia" id="referencia" class="form-control" placeholder="Ex: Próximo ao Banco do Brasil" required value="{{ old('referencia', $item->localizacao->referencia ?? '') }}">
            </div>
        </div>

        <!-- Botão de envio -->
        <button type="submit" class="btn btn-primary">
            {{ isset($item) ? 'Atualizar Item' : 'Registrar Item' }}
        </button>
    </form>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<!-- Script para o autocompletar de endereços do Google Maps -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o autocomplete do Google Maps
        const enderecoInput = document.getElementById('endereco');
        const autocomplete = new google.maps.places.Autocomplete(enderecoInput, {
            types: ['geocode'], // Restringe a busca a endereços
        });


        // Evento de seleção de um endereço
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                // Preenche os campos de latitude e longitude
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();


                // Preenche o campo de endereço visível e oculto
                document.getElementById('endereco').value = place.formatted_address || '';
                document.getElementById('endereco_input').value = place.formatted_address || '';

                // Verifica se o endereço está em Campo Grande, MS
                const cidade = place.address_components.find(component => component.types.includes('locality'))?.long_name || '';
                const estado = place.address_components.find(component => component.types.includes('administrative_area_level_1'))?.short_name || '';

                if (cidade === 'Campo Grande' && estado === 'MS') {
                    document.getElementById('enderecoError').style.display = 'none';
                } else {
                    document.getElementById('enderecoError').style.display = 'block';
                }

            }
        });

        // Evento de mudança no campo de endereço
        enderecoInput.addEventListener('change', function() {
            if (!enderecoInput.value) {
                // Limpa os campos se o endereço for apagado
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('endereco_input').value = '';
            }
        });

        // Lógica para mostrar/ocultar campos de data
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
@endsection