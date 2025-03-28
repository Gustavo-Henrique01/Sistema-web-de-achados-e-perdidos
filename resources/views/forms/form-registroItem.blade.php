@extends('usuario.home')

@section('content')

<style>
    /* Estilo para o mapa */
    #map {
        height: 400px;
        margin-bottom: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    
    /* Melhorias visuais do formulário */
    .form-control, .form-select {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: #505050;
        margin-bottom: 6px;
    }
    
    .section-container {
        background-color: #f8f9fc;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        border-left: 4px solid #4e73df;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .section-title {
        color: #4e73df;
        font-size: 1.1rem;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .img-thumbnail {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.03);
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
    }
    
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    
    .alert-success {
        background-color: #E8F4FF;
        border-color: #BFDFFF;
        color: #1a75ff;
    }
    
    /* Melhorias de responsividade */
    @media (max-width: 768px) {
        .section-container {
            padding: 15px;
        }
        
        .col-md-4 {
            width: 100%;
        }
        
        h1 {
            font-size: 1.8rem;
        }
        
        .btn-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        
        .img-thumbnail {
            max-width: 100% !important;
            height: auto;
        }
    }
    
    @media (max-width: 576px) {
        .section-container {
            padding: 12px;
            margin-bottom: 15px;
        }
        
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 1rem;
        }
        
        .form-label {
            margin-bottom: 4px;
        }
        
        .mb-3 {
            margin-bottom: 0.75rem !important;
        }
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4 text-dark">{{ isset($item) ? 'Editar Item' : 'Registrar Item' }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('usuario.atualizar-item', $item->id) : route('registrar-item') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @if (isset($item))
            @method('PUT') <!-- Método HTTP para atualização -->
        @endif

        <!-- Informações básicas -->
        <div class="section-container">
            <h4 class="section-title">Informações básicas</h4>
            
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
        </div>

        <!-- Fotos -->
        <div class="section-container">
            <h4 class="section-title">Fotos do item</h4>
            
            <label for="fotos" class="form-label">Fotos (Máximo 3)</label>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Exibir fotos atuais, se existirem (para edição) -->
            @if (isset($item) && $item->fotos->count() > 0)
                <div class="row mb-3">
                    @foreach ($item->fotos as $foto)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto do item" class="img-thumbnail" style="max-width: 200px; height: auto;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="foto_principal" value="{{ $foto->id }}" {{ $foto->is_principal ? 'checked' : '' }}>
                                <label class="form-check-label">Principal</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger mt-1 w-100" onclick="removerFoto(this, {{ $foto->id }})">Remover</button>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Campos para upload de novas fotos -->
            <div class="mb-3 p-3 bg-light rounded border border-1 border-secondary-subtle">
                <input type="file" name="foto_temporaria" id="foto_temporaria" class="form-control mb-2" accept="image/jpeg, image/png, image/gif">
                <button type="button" id="adicionar_foto" class="btn btn-secondary w-100">Adicionar Foto</button>
                <small class="text-muted d-block mt-2">Você pode enviar até 3 fotos. Tamanho máximo por foto: 2MB.</small>
            </div>
            <div id="fotos_selecionadas"></div>
            
            <!-- Preview das novas fotos selecionadas -->
            <div id="preview-container" class="row mt-3"></div>
        </div>

        <!-- Localização -->
        <div class="section-container">
            <h4 class="section-title">Localização</h4>
            <p class="mb-3">Informe o local onde o item foi perdido ou achado</p>

            <!-- Campo de endereço com autocomplete do Google Maps -->
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
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
                <label for="referencia" class="form-label">Ponto de Referência</label>
                <input type="text" name="referencia" id="referencia" class="form-control" placeholder="Ex: Próximo ao Banco do Brasil" required value="{{ old('referencia', $item->localizacao->referencia ?? '') }}">
            </div>
        </div>

        <!-- Botão de envio -->
        <div class="mt-4 mb-5 text-end">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                {{ isset($item) ? 'Atualizar Item' : 'Registrar Item' }}
            </button>
        </div>
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

        // Lógica para adicionar fotos uma por vez
        const fotoTemporaria = document.getElementById('foto_temporaria');
        const adicionarFoto = document.getElementById('adicionar_foto');
        const fotosContainer = document.getElementById('preview-container');
        const fotosHiddenContainer = document.getElementById('fotos_selecionadas');
        
        // Array para armazenar os arquivos selecionados
        let fotosArray = [];
        
        // Evento para adicionar uma foto
        adicionarFoto.addEventListener('click', function() {
            if (!fotoTemporaria.files || !fotoTemporaria.files[0]) {
                alert('Por favor, selecione uma foto primeiro.');
                return;
            }
            
            // Verificar se já atingiu o limite de 3 fotos
            if (fotosArray.length >= 3) {
                alert('Você já selecionou o máximo de 3 fotos.');
                return;
            }
            
            const file = fotoTemporaria.files[0];
            
            // Verificar se é uma imagem
            if (!file.type.startsWith('image/')) {
                alert('Por favor, selecione apenas arquivos de imagem.');
                return;
            }
            
            // Adicionar o arquivo ao array
            fotosArray.push(file);
            
            // Atualizar o preview
            atualizarPreview();
            
            // Limpar o campo de seleção para a próxima foto
            fotoTemporaria.value = '';
        });
        
        // Função para atualizar o preview das fotos
        function atualizarPreview() {
            // Limpar o container de preview
            fotosContainer.innerHTML = '';
            fotosHiddenContainer.innerHTML = '';
            
            // Adicionar um contador de fotos
            const counterDiv = document.createElement('div');
            counterDiv.className = 'col-12 mb-3';
            counterDiv.innerHTML = `<div class="alert alert-success mt-3">
                <strong>${fotosArray.length} foto(s) selecionada(s)</strong> de 3 permitidas.
            </div>`;
            fotosContainer.appendChild(counterDiv);
            
            // Para cada arquivo no array, criar um preview
            fotosArray.forEach((file, index) => {
                // Criar um objeto FormData para o arquivo
                const formData = new FormData();
                
                // Criar um objeto URL para o arquivo
                const imgURL = URL.createObjectURL(file);
                
                // Criar os elementos para o preview
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-4 col-sm-6 mb-3';
                
                const img = document.createElement('img');
                img.src = imgURL;
                img.className = 'img-thumbnail';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                
                // Botão para remover a foto
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-danger btn-sm mt-2 w-100';
                removeBtn.innerHTML = 'Remover';
                removeBtn.onclick = function() {
                    // Remover do array
                    fotosArray.splice(index, 1);
                    // Atualizar o preview
                    atualizarPreview();
                };
                
                // Opção para marcar como principal
                const radioDiv = document.createElement('div');
                radioDiv.className = 'form-check mt-2';
                
                const radioInput = document.createElement('input');
                radioInput.type = 'radio';
                radioInput.name = 'foto_principal_index';
                radioInput.value = index;
                radioInput.className = 'form-check-input';
                radioInput.checked = index === 0; // Primeira foto é a principal por padrão
                
                const radioLabel = document.createElement('label');
                radioLabel.className = 'form-check-label';
                radioLabel.innerHTML = 'Principal';
                
                radioDiv.appendChild(radioInput);
                radioDiv.appendChild(radioLabel);
                
                // Adicionar elementos ao container
                colDiv.appendChild(img);
                colDiv.appendChild(radioDiv);
                colDiv.appendChild(removeBtn);
                fotosContainer.appendChild(colDiv);
                
                // Criar um blob e anexá-lo ao FormData para envio posterior
                const blob = new Blob([file], { type: file.type });
                
                // Criar um input file oculto para cada arquivo
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = `fotos[${index}]`;
                hiddenInput.style.display = 'none';
                
                // Usar DataTransfer para anexar o arquivo ao input
                const dt = new DataTransfer();
                dt.items.add(file);
                hiddenInput.files = dt.files;
                
                fotosHiddenContainer.appendChild(hiddenInput);
            });
        }
        
        // Função para remover foto (em caso de edição)
        window.removerFoto = function(button, fotoId) {
            if (confirm('Tem certeza que deseja remover esta foto?')) {
                // Adiciona o ID da foto a ser removida em um input hidden
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fotos_removidas[]';
                input.value = fotoId;
                button.parentNode.appendChild(input);
                
                // Oculta a foto na interface
                button.parentNode.style.display = 'none';
            }
        };
    });
</script>
@endsection