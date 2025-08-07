@extends('layouts.parceiro')

@section('title', isset($isEdit) ? 'Editar Item' : 'Cadastrar Item')

@section('content')

<style>
    /* Estilos modernos para o formulário */
    .form-section {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-color);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .form-section:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .section-title {
        color: var(--primary-color);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Estilos para o mapa */
    #map {
        height: 350px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 1rem;
    }
    
    /* Estilos para preview de fotos */
    .photo-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .photo-preview-item {
        position: relative;
        width: calc(33.333% - 0.67rem);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }
    
    .photo-preview-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .photo-preview-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        padding: 0.5rem;
        display: flex;
        justify-content: space-between;
    }
    
    /* Responsividade */
    @media (max-width: 992px) {
        .photo-preview-item {
            width: calc(50% - 0.5rem);
        }
    }
    
    @media (max-width: 576px) {
        .photo-preview-item {
            width: 100%;
        }
        
        .form-section {
            padding: 1rem;
        }
        
        #map {
            height: 250px;
        }
    }
    
    /* Estilos para o container de sugestões do Google Maps */
    .pac-container {
        z-index: 1051;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>

@if(!auth()->user()->ativo)
    <div class="container-fluid">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Conta Inativa</h4>
            <p>Sua conta está atualmente inativa. Você não pode cadastrar novos itens até que sua conta seja reativada.</p>
            <hr>
            <p class="mb-0">Entre em contato com a administração para mais informações.</p>
        </div>
    </div>
@else
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ isset($isEdit) ? 'Editar Item' : 'Cadastrar Item no Estabelecimento' }}</h1>
            <p class="text-muted mb-0">{{ isset($isEdit) ? 'Altere as informações do item conforme necessário' : 'Preencha os dados para cadastrar um novo item encontrado' }}</p>
        </div>
        <a href="{{ route('parceiro.itens') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i>Voltar para Itens
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Atenção!</strong> Corrija os erros abaixo:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ isset($isEdit) ? route('parceiro.itens.atualizar', $item) : route('parceiro.cadastrar-item') }}" method="POST" enctype="multipart/form-data" class="needs-validation" id="item-form" novalidate>
        @csrf
        @if(isset($isEdit))
            @method('PUT')
        @endif

        <!-- Informações básicas -->
        <div class="form-section">
            <h4 class="section-title"><i class="fas fa-info-circle me-2"></i>Informações Básicas</h4>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="id_categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                    <select name="id_categoria" id="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ isset($item) && $item->id_categoria == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome_categoria }}
                        </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="categoria-feedback">
                        Por favor, selecione uma categoria.
                    </div>
                    @error('id_categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Data em que o item foi encontrado -->
                <div class="col-md-6">
                    <label for="data_encontrado" class="form-label">Data em que o item foi encontrado <span class="text-danger">*</span></label>
                    <input type="date" name="data_encontrado" id="data_encontrado" class="form-control @error('data_encontrado') is-invalid @enderror" 
                           max="{{ date('Y-m-d') }}" value="{{ isset($item) && $item->data_encontrado ? (is_string($item->data_encontrado) ? $item->data_encontrado : $item->data_encontrado->format('Y-m-d')) : '' }}" required>
                    <div class="invalid-feedback" id="data-encontrado-feedback">
                        Por favor, selecione uma data válida (não pode ser futura).
                    </div>
                    @error('data_encontrado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Descrição do item -->
                <div class="col-12">
                    <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                    <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                        rows="4" placeholder="Ex: descreva as informações sobre o item, como cor, marca, modelo, estado, e como encontrou ou perdeu o item etc .." 
                        required minlength="10" maxlength="500">{{ isset($item) ? $item->descricao : '' }}</textarea>
                    <div class="invalid-feedback" id="descricao-feedback">
                        A descrição deve ter entre 10 e 500 caracteres.
                    </div>
                    <div class="form-text">
                        <i class="fas fa-lightbulb text-warning me-1"></i> Dica: Quanto mais detalhada a descrição, mais fácil será para o dono identificar o item.
                    </div>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Campos ocultos para tipo e status -->
                @if(isset($isEdit))
                <input type="hidden" name="tipo" value="{{ $item->tipo }}">
                <input type="hidden" name="status" value="{{ $item->status }}">
                @else
                <input type="hidden" name="tipo" value="achado">
                <input type="hidden" name="status" value="em_estabelecimento">
                @endif
            </div>
        </div>
        
        <!-- Fotos do item -->
        <div class="form-section">
            <h4 class="section-title"><i class="fas fa-camera me-2"></i>Fotos do Item</h4>
            
            <!-- Fotos existentes (apenas para edição) -->
            @if(isset($isEdit) && isset($item->fotos) && $item->fotos->count() > 0)
                <div class="mb-4">
                    <h5 class="h6 mb-3">Fotos Atuais</h5>
                    <div class="row g-3">
                        @foreach($item->fotos as $foto)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                         class="card-img-top" 
                                         alt="Foto do item"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        @if($foto->is_principal)
                                            <span class="badge bg-primary mb-2">Foto Principal</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>Você já tem {{ $item->fotos->count() }} foto(s). 
                        O limite é de 3 fotos por item.
                    </div>
                </div>
            @endif
            
            <div class="mb-3">
                <label for="fotos" class="form-label">
                    {{ isset($isEdit) ? 'Adicionar novas fotos' : 'Fotos do item (máximo 3)' }}
                    {{ isset($isEdit) && isset($item->fotos) && $item->fotos->count() >= 3 ? '(limite atingido)' : '' }}
                </label>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="file" name="fotos[]" id="fotos" class="form-control d-none @error('fotos') is-invalid @enderror" 
                           accept="image/jpeg,image/png,image/jpg,image/webp" multiple
                           {{ isset($isEdit) && isset($item->fotos) && $item->fotos->count() >= 3 ? 'disabled' : '' }}>
                    <button type="button" id="btn-add-foto" class="btn btn-primary" {{ isset($isEdit) && isset($item->fotos) && $item->fotos->count() >= 3 ? 'disabled' : '' }}>
                        <i class="fas fa-plus me-2"></i>Adicionar Foto
                    </button>
                    <span class="text-muted small">Clique para adicionar uma foto por vez (máximo 3)</span>
                </div>
                <div class="form-text">
                    <i class="fas fa-info-circle text-primary me-1"></i> Formatos aceitos: JPG, PNG, WEBP. Tamanho máximo: 2MB por foto.
                </div>
                @error('fotos')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Preview das novas fotos selecionadas -->
            <div id="preview-container" class="row mt-3"></div>
            
            <!-- Campo oculto para armazenar as fotos selecionadas -->
            <div id="selected-photos-container"></div>
        </div>
        
        <!-- Localização -->
        <div class="form-section">
            <h4 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>Localização</h4>
            <p class="text-muted mb-3">Informe o local onde o item foi encontrado</p>
            
            <input type="hidden" name="usar_localizacao_parceiro" value="0">
            
            <div id="campos_localizacao">
                <!-- Campo de endereço com autocomplete do Google Maps -->
                <div class="mb-3">
                    <label for="endereco" class="form-label">Endereço onde o item foi encontrado <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" id="endereco" class="form-control @error('endereco') is-invalid @enderror" 
                               placeholder="Digite o endereço completo (Rua, Número, Bairro)" 
                               value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->endereco : old('endereco') }}" required>
                    </div>
                    <input type="hidden" name="endereco" id="endereco_input" value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->endereco : old('endereco') }}">
                    <div class="invalid-feedback" id="endereco-feedback">
                        Por favor, informe um endereço válido.
                    </div>
                    <div id="enderecoError" class="invalid-feedback" style="display: none;">
                        O endereço deve estar localizado em Campo Grande, MS.
                    </div>
                    @error('endereco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <input type="hidden" name="latitude" id="latitude" value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->latitude : old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->longitude : old('longitude') }}">

                <!-- Campo para nome do local -->
                <div class="mb-3">
                    <label for="nome_local" class="form-label">Nome do Local <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" name="nome_local" id="nome_local" class="form-control @error('nome_local') is-invalid @enderror" 
                               placeholder="Ex: Shopping Campo Grande, Terminal Rodoviário, etc." 
                               value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->nome_local : old('nome_local') }}" required>
                    </div>
                    <div class="invalid-feedback" id="nome-local-feedback">
                        Por favor, informe o nome do local.
                    </div>
                    @error('nome_local')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo para referência -->
                <div class="mb-3">
                    <label for="referencia" class="form-label">Ponto de Referência <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                        <input type="text" name="referencia" id="referencia" class="form-control @error('referencia') is-invalid @enderror" 
                               placeholder="Ex: Próximo ao Banco do Brasil, Na praça de alimentação, etc." 
                               value="{{ isset($item) && isset($item->localizacao) ? $item->localizacao->referencia : old('referencia') }}" required>
                    </div>
                    <div class="invalid-feedback" id="referencia-feedback">
                        Por favor, informe um ponto de referência.
                    </div>
                    @error('referencia')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 mb-5">
            <a href="{{ route('parceiro.itens') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-{{ isset($isEdit) ? 'save' : 'plus-circle' }} me-2"></i>
                {{ isset($isEdit) ? 'Salvar Alterações' : 'Cadastrar Item' }}
            </button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o autocomplete do Google Maps
        const enderecoInput = document.getElementById('endereco');
        if (!enderecoInput) return; // Sair se o elemento não existir
        
        const autocomplete = new google.maps.places.Autocomplete(enderecoInput, {
            types: ['address'],
            componentRestrictions: { country: 'br' }
        });
        
        // Quando o usuário seleciona um endereço no autocomplete
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            
            if (!place.geometry) {
                document.getElementById('enderecoError').style.display = 'block';
                document.getElementById('enderecoError').textContent = 'Por favor, selecione um endereço válido.';
                return;
            }
            
            // Verificar se o endereço está em Campo Grande, MS
            let isCampoGrande = false;
            
            // Coordenadas aproximadas de Campo Grande, MS
            const campoGrandeLat = -20.4697;
            const campoGrandeLng = -54.6201;
            const maxDistanceKm = 30; // Distância máxima em km do centro de Campo Grande
            
            // Calcular distância entre dois pontos (fórmula de Haversine)
            function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                const R = 6371; // Raio da Terra em km
                const dLat = deg2rad(lat2 - lat1);
                const dLon = deg2rad(lon2 - lon1);
                const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2); 
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                const d = R * c; // Distância em km
                return d;
            }
            
            function deg2rad(deg) {
                return deg * (Math.PI/180);
            }
            
            // Verificar se está dentro da área de Campo Grande
            const distance = getDistanceFromLatLonInKm(
                place.geometry.location.lat(), 
                place.geometry.location.lng(),
                campoGrandeLat,
                campoGrandeLng
            );
            
            if (distance <= maxDistanceKm) {
                isCampoGrande = true;
                
                // Verificação adicional pelos componentes do endereço
                if (place.address_components) {
                    let foundCampoGrande = false;
                    let foundMS = false;
                    
                    for (let i = 0; i < place.address_components.length; i++) {
                        const component = place.address_components[i];
                        
                        // Verificar se é Campo Grande (cidade)
                        if ((component.types.includes('locality') || 
                             component.types.includes('administrative_area_level_2')) && 
                            component.long_name.toLowerCase().includes('campo grande')) {
                            foundCampoGrande = true;
                        }
                        
                        // Verificar se é MS (estado)
                        if (component.types.includes('administrative_area_level_1') && 
                            (component.short_name === 'MS' || 
                             component.long_name.toLowerCase().includes('mato grosso do sul'))) {
                            foundMS = true;
                        }
                    }
                    
                    // Se encontrou explicitamente outro estado que não seja MS, invalidar
                    let foundOtherState = false;
                    for (let i = 0; i < place.address_components.length; i++) {
                        const component = place.address_components[i];
                        if (component.types.includes('administrative_area_level_1') && 
                            component.short_name !== 'MS' && 
                            !component.long_name.toLowerCase().includes('mato grosso do sul')) {
                            foundOtherState = true;
                        }
                    }
                    
                    // Se encontrou outro estado explicitamente, não é Campo Grande, MS
                    if (foundOtherState) {
                        isCampoGrande = false;
                    }
                    
                    // Se encontrou explicitamente Campo Grande e MS, é válido
                    if (foundCampoGrande && foundMS) {
                        isCampoGrande = true;
                    }
                }
            }
            
            if (!isCampoGrande) {
                document.getElementById('enderecoError').style.display = 'block';
                document.getElementById('enderecoError').textContent = 'O endereço deve estar localizado em Campo Grande, MS.';
                return;
            }
            
            document.getElementById('enderecoError').style.display = 'none';
            document.getElementById('endereco_input').value = place.formatted_address;
            
            // Atualiza os inputs de latitude e longitude
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
        });
        
        // Manipulação de upload de fotos
        const fotosInput = document.getElementById('fotos');
        const previewContainer = document.getElementById('preview-container');
        const selectedPhotosContainer = document.getElementById('selected-photos-container');
        const btnAddFoto = document.getElementById('btn-add-foto');
        
        // Array para armazenar os arquivos de fotos selecionados
        let selectedPhotos = [];
        
        if (btnAddFoto) {
            btnAddFoto.addEventListener('click', function() {
                fotosInput.click();
            });
        }
        
        if (fotosInput) {
            fotosInput.addEventListener('change', function() {
                if (!this.files || this.files.length === 0) return;
                
                const currentPhotos = selectedPhotos.length;
                const newPhotos = this.files.length;
                
                if (currentPhotos + newPhotos > 3) {
                    alert('Você pode enviar no máximo 3 fotos.');
                    return;
                }
                
                // Adicionar os novos arquivos ao array de fotos selecionadas
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    selectedPhotos.push(file);
                    
                    const reader = new FileReader();
                    const photoIndex = currentPhotos + i;
                    
                    reader.onload = function(e) {
                        const imgURL = e.target.result;
                        
                        const colDiv = document.createElement('div');
                        colDiv.className = 'col-lg-4 col-md-6 mb-3 photo-preview-item';
                        colDiv.dataset.index = photoIndex;
                        
                        colDiv.innerHTML = `
                            <div class="card">
                                <img src="${imgURL}" class="card-img-top" 
                                     alt="Preview" style="object-fit: cover; height: 200px;">
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input type="radio" name="foto_principal_index" value="${photoIndex}" 
                                               id="foto_principal_${photoIndex}" class="form-check-input" 
                                               ${photoIndex === 0 && currentPhotos === 0 ? 'checked' : ''}>
                                        <label for="foto_principal_${photoIndex}" class="form-check-label">
                                            Foto principal
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger remove-preview" data-index="${photoIndex}">
                                        <i class="fas fa-trash me-1"></i>Remover
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        previewContainer.appendChild(colDiv);
                        
                        // Adicionar evento para remover a prévia
                        const removeBtn = colDiv.querySelector('.remove-preview');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function() {
                                const index = parseInt(this.dataset.index);
                                // Remover o arquivo do array
                                selectedPhotos.splice(index, 1);
                                // Remover a prévia
                                colDiv.remove();
                                // Atualizar os índices dos elementos restantes
                                updatePhotoIndices();
                            });
                        }
                    };
                    
                    reader.readAsDataURL(file);
                }
                
                // Atualizar o formulário com os arquivos selecionados
                updateFormFiles();
            });
        }
        
        // Função para atualizar os índices das fotos após remover uma
        function updatePhotoIndices() {
            const previewItems = document.querySelectorAll('.photo-preview-item');
            previewItems.forEach((item, index) => {
                item.dataset.index = index;
                const radioInput = item.querySelector('input[type="radio"]');
                const removeBtn = item.querySelector('.remove-preview');
                
                if (radioInput) {
                    radioInput.id = `foto_principal_${index}`;
                    radioInput.value = index;
                    const label = item.querySelector('label');
                    if (label) {
                        label.setAttribute('for', `foto_principal_${index}`);
                    }
                }
                
                if (removeBtn) {
                    removeBtn.dataset.index = index;
                }
            });
            
            // Atualizar o formulário
            updateFormFiles();
        }
        
        // Função para atualizar os arquivos no formulário
        function updateFormFiles() {
            // Limpar o container de fotos selecionadas
            selectedPhotosContainer.innerHTML = '';
            
            // Criar um novo FormData
            const formData = new FormData();
            
            // Adicionar cada arquivo ao FormData
            selectedPhotos.forEach((file, index) => {
                // Criar um input de arquivo para cada foto
                const input = document.createElement('input');
                input.type = 'file';
                input.name = `fotos[${index}]`;
                input.style.display = 'none';
                
                // Criar um objeto DataTransfer para adicionar o arquivo ao input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                
                // Adicionar o input ao container
                selectedPhotosContainer.appendChild(input);
            });
        }
        
        // Validação do formulário
        const form = document.getElementById('item-form');
        
        if (form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Validar campos obrigatórios
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Validar descrição (mínimo 10 caracteres)
                const descricao = document.getElementById('descricao');
                if (descricao && descricao.value.trim().length < 10) {
                    descricao.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Validar coordenadas
                const latitudeInput = document.getElementById('latitude');
                const longitudeInput = document.getElementById('longitude');
                if (!latitudeInput.value || !longitudeInput.value) {
                    document.getElementById('endereco').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Rolar para o primeiro campo com erro
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    // Mostrar alerta
                    alert('Por favor, corrija os erros no formulário antes de enviar.');
                }
            });
        }
    });
</script>

@endif
@endsection