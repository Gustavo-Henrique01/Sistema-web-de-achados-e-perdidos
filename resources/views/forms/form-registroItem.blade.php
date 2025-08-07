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

@if(!auth()->user()->ativo)
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Conta Inativa</h4>
            <p>Sua conta está atualmente inativa. Você não pode cadastrar novos itens até que sua conta seja reativada.</p>
            <hr>
            <p class="mb-0">Entre em contato com a administração para mais informações.</p>
        </div>
    </div>
@else
<div class="container mt-5">
    <h1 class="mb-4 text-dark">{{ isset($item) ? 'Editar Item' : 'Registrar Item' }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Item cadastrado com sucesso!</h5>
            <p>Seu item foi registrado e está aguardando aprovação. O processo de avaliação pode levar até <strong>5 dias</strong> úteis.</p>
            <p class="mb-0">Você receberá uma notificação assim que seu item for aprovado.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('usuario.atualizar-item', $item->id) : route('registrar-item') }}" method="POST" enctype="multipart/form-data" class="needs-validation" id="item-form" novalidate>
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
                <select name="id_categoria" id="id_categoria" class="form-select @error('id_categoria') is-invalid @enderror" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('id_categoria', $item->id_categoria ?? '') == $categoria->id ? 'selected' : '' }}>
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

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->localizacao->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->localizacao->longitude ?? '') }}">

            <!-- Campo para nome do local -->
            <div class="mb-3">
<<<<<<< HEAD
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
=======
                <label class="form-label">Tipo</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoAchado" name="tipo" value="achado" class="form-check-input @error('tipo') is-invalid @enderror" required {{ old('tipo', $item->tipo ?? '') == 'achado' ? 'checked' : '' }}>
                        <label for="tipoAchado" class="form-check-label">Achado</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="tipoPerdido" name="tipo" value="perdido" class="form-check-input @error('tipo') is-invalid @enderror" required {{ old('tipo', $item->tipo ?? '') == 'perdido' ? 'checked' : '' }}>
                        <label for="tipoPerdido" class="form-check-label">Perdido</label>
                    </div>
                    <div class="invalid-feedback d-block" id="tipo-feedback" style="display: none !important;">
                        Por favor, selecione se o item foi achado ou perdido.
                    </div>
                    @error('tipo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data de Perdido ou Encontrado -->
            <div class="mb-3" id="campo_data_perdido" style="display: {{ old('tipo', $item->tipo ?? '') == 'perdido' ? 'block' : 'none' }};">
                <label for="data_perdido" class="form-label">Data em que o item foi perdido</label>
                <input type="date" name="data_perdido" id="data_perdido" class="form-control @error('data_perdido') is-invalid @enderror" max="{{ date('Y-m-d') }}" value="{{ old('data_perdido', $item->data_perdido ?? '') }}">
                <div class="invalid-feedback" id="data-perdido-feedback">
                    Por favor, selecione uma data válida (não pode ser futura).
                </div>
                @error('data_perdido')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3" id="campo_data_encontrado" style="display: {{ old('tipo', $item->tipo ?? '') == 'achado' ? 'block' : 'none' }};">
                <label for="data_encontrado" class="form-label">Data em que o item foi encontrado</label>
                <input type="date" name="data_encontrado" id="data_encontrado" class="form-control @error('data_encontrado') is-invalid @enderror" max="{{ date('Y-m-d') }}" value="{{ old('data_encontrado', $item->data_encontrado ?? '') }}">
                <div class="invalid-feedback" id="data-encontrado-feedback">
                    Por favor, selecione uma data válida (não pode ser futura).
                </div>
                @error('data_encontrado')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" 
                    rows="4" placeholder="Ex: descreva as informações sobre o item, como cor, marca, modelo, estado, e como encontrou ou perdeu o item etc .." 
                    required minlength="10" maxlength="500">{{ old('descricao', $item->descricao ?? '') }}</textarea>
                <div class="invalid-feedback" id="descricao-feedback">
                    A descrição deve ter entre 10 e 500 caracteres.
                </div>
                <small class="text-muted">
                    Mínimo de 10 caracteres, máximo de 500. <span id="contador-caracteres">0</span>/500
                </small>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Fotos -->
        <div class="section-container">
            <h4 class="section-title">Fotos do item</h4>
            
            <label for="fotos" class="form-label">Fotos (Recomendado: ajuda na identificação, máximo 3)</label>
            
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
                <input type="file" name="foto_temporaria" id="foto_temporaria" 
                       class="form-control @error('fotos') is-invalid @enderror" 
                       accept="image/jpeg, image/png, image/webp">
                <button type="button" id="adicionar_foto" class="btn btn-secondary mt-2">
                    Adicionar Foto
                </button>
                <div id="foto-status" class="mt-2"></div>
                <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG, WEBP. Tamanho máximo por foto: 2MB.</small>
                <small class="text-primary d-block mt-1"><i class="fas fa-info-circle"></i> Incluir fotos facilita muito a identificação do item!</small>
                @error('fotos')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div id="fotos_selecionadas" class="row"></div>

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
                <input type="text" id="endereco" class="form-control @error('endereco') is-invalid @enderror" 
                       placeholder="Digite o endereço completo (Rua, Número, Bairro)" 
                       value="{{ old('endereco', $item->localizacao->endereco ?? '') }}" required>
                <input type="hidden" name="endereco" id="endereco_input" value="{{ old('endereco', $item->localizacao->endereco ?? '') }}">
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
            
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $item->localizacao->latitude ?? '') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $item->localizacao->longitude ?? '') }}">

            <!-- Campo para nome do local -->
            <div class="mb-3">
                <label for="nome_local" class="form-label">Nome do Local</label>
                <input type="text" name="nome_local" id="nome_local" class="form-control @error('nome_local') is-invalid @enderror" 
                       placeholder="Ex: Shopping Campo Grande, Terminal Rodoviário, etc." 
                       required value="{{ old('nome_local', $item->localizacao->nome_local ?? '') }}">
                <div class="invalid-feedback" id="nome-local-feedback">
                    Por favor, informe o nome do local.
                </div>
                @error('nome_local')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo para referência -->
            <div class="mb-3">
                <label for="referencia" class="form-label">Ponto de Referência</label>
                <input type="text" name="referencia" id="referencia" class="form-control @error('referencia') is-invalid @enderror" 
                       placeholder="Ex: Próximo ao Banco do Brasil, Na praça de alimentação, etc." 
                       required value="{{ old('referencia', $item->localizacao->referencia ?? '') }}">
                <div class="invalid-feedback" id="referencia-feedback">
                    Por favor, informe um ponto de referência.
                </div>
                @error('referencia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
<!-- Script para o autocompletar de endereços do Google Maps e validações -->
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

                // Verifica se o endereço está em Campo Grande, MS (apenas informativo, não bloqueia)
                const cidade = place.address_components.find(component => 
                    component.types.includes('locality') || 
                    component.types.includes('administrative_area_level_2'))?.long_name || '';
                
                const estado = place.address_components.find(component => 
                    component.types.includes('administrative_area_level_1'))?.short_name || '';
                
                // Se o endereço contém Campo Grande ou está no MS, consideramos válido
                const enderecoCampoGrande = cidade.includes('Campo Grande') || 
                                            place.formatted_address.includes('Campo Grande') || 
                                            (estado === 'MS' && place.formatted_address.includes('MS'));
                
                if (enderecoCampoGrande) {
                    document.getElementById('enderecoError').style.display = 'none';
                } else {
                    // Apenas avisa, mas não impede o envio
                    document.getElementById('enderecoError').style.display = 'block';
                    document.getElementById('enderecoError').innerHTML = 
                        '<i class="fas fa-info-circle"></i> O endereço parece não estar em Campo Grande, MS. Se estiver correto, pode continuar.';
                    document.getElementById('enderecoError').classList.remove('invalid-feedback');
                    document.getElementById('enderecoError').classList.add('text-warning');
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
            document.getElementById('data_encontrado').setAttribute('required', 'required');
            document.getElementById('data_perdido').removeAttribute('required');
        });

        tipoPerdido.addEventListener('change', function() {
            campoDataPerdido.style.display = 'block';
            campoDataAchado.style.display = 'none';
            document.getElementById('data_perdido').setAttribute('required', 'required');
            document.getElementById('data_encontrado').removeAttribute('required');
        });

        // Validação de data não futura
        const dataHoje = new Date().toISOString().split('T')[0];
        document.getElementById('data_perdido').setAttribute('max', dataHoje);
        document.getElementById('data_encontrado').setAttribute('max', dataHoje);

        // Contador de caracteres para descrição
        const descricaoTextarea = document.getElementById('descricao');
        const contadorCaracteres = document.getElementById('contador-caracteres');
        

        descricaoTextarea.addEventListener('input', function() {
            const count = this.value.length;
            contadorCaracteres.textContent = count;
            
            if (count < 10 || count > 500) {
                this.classList.add('is-invalid');
                document.getElementById('descricao-feedback').style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                document.getElementById('descricao-feedback').style.display = 'none';
            }
        });

        // Executar contagem inicial
        contadorCaracteres.textContent = descricaoTextarea.value.length;

        // Lógica para adicionar fotos uma por vez
        const fotoTemporaria = document.getElementById('foto_temporaria');
        const adicionarFoto = document.getElementById('adicionar_foto');
        const fotosContainer = document.getElementById('preview-container');
        const fotosHiddenContainer = document.getElementById('fotos_selecionadas');
        const fotosStatus = document.getElementById('foto-status');

        // Array para armazenar os arquivos selecionados
        let fotosArray = [];

        function mostrarStatus(mensagem, tipo = 'info') {
            fotosStatus.className = `alert alert-${tipo} mt-2`;
            fotosStatus.textContent = mensagem;
        }

        // Evento para adicionar uma foto
        adicionarFoto.addEventListener('click', function() {
            const file = fotoTemporaria.files[0];
            if (!file) {
                mostrarStatus('Por favor, selecione uma foto primeiro.', 'warning');
                return;
            }
            
            // Verificar se já atingiu o limite de 3 fotos
            if (fotosArray.length >= 3) {
                mostrarStatus('Você já selecionou o máximo de 3 fotos.', 'warning');
                return;
            }
            
            // Verificar se é uma imagem
            if (!file.type.match('image/(jpeg|jpg|png|webp)')) {
                mostrarStatus('Por favor, selecione apenas arquivos nos formatos: JPG, PNG ou WEBP.', 'danger');
                return;
            }
            
            // Verificar tamanho máximo (2MB)
            if (file.size > 2 * 1024 * 1024) {
                mostrarStatus('O tamanho máximo permitido por imagem é 2MB.', 'danger');
                return;
            }
            
            // Adicionar o arquivo ao array
            fotosArray.push(file);
            mostrarStatus(`Foto "${file.name}" adicionada com sucesso! (${(file.size/1024/1024).toFixed(2)}MB)`, 'success');
            
            // Atualizar o preview
            atualizarPreview();
            
            // Limpar o campo de seleção para a próxima foto
            fotoTemporaria.value = '';
        });

        // Função para atualizar o preview das fotos
        function atualizarPreview() {
            // Limpar os containers
            fotosContainer.innerHTML = '';
            fotosHiddenContainer.innerHTML = '';
            
            // Adicionar contador de fotos
            if (fotosArray.length > 0) {
                const counterDiv = document.createElement('div');
                counterDiv.className = 'col-12 mb-3';
                counterDiv.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-camera"></i> <strong>${fotosArray.length}</strong> foto(s) selecionada(s) de 3 permitidas
                    </div>
                `;
                fotosContainer.appendChild(counterDiv);
            }
            
            // Para cada arquivo no array, criar um preview
            fotosArray.forEach((file, index) => {
                const imgURL = URL.createObjectURL(file);
                
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-4 col-sm-6 mb-3';
                
                colDiv.innerHTML = `
                    <div class="card">
                        <img src="${imgURL}" class="card-img-top" alt="Preview" style="object-fit: cover; height: 200px;">
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input type="radio" name="foto_principal_index" value="${index}" 
                                       class="form-check-input" ${index === 0 ? 'checked' : ''}>
                                <label class="form-check-label">Foto Principal</label>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm w-100" 
                                    onclick="removerFotoTemp(${index})">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    </div>
                `;
                
                fotosContainer.appendChild(colDiv);
                
                // Criar input hidden para o arquivo
                const dt = new DataTransfer();
                dt.items.add(file);
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = `fotos[${index}]`;
                hiddenInput.style.display = 'none';
                hiddenInput.files = dt.files;
                
                fotosHiddenContainer.appendChild(hiddenInput);
            });
        }

        // Função para remover foto temporária
        window.removerFotoTemp = function(index) {
            fotosArray.splice(index, 1);
            mostrarStatus('Foto removida com sucesso.', 'info');
            atualizarPreview();
        };

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

        // Validação do formulário antes do envio
        const form = document.getElementById('item-form');
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validar categoria
            const categoria = document.getElementById('id_categoria');
            if (categoria.value === '') {
                categoria.classList.add('is-invalid');
                document.getElementById('categoria-feedback').style.display = 'block';
                isValid = false;
            } else {
                categoria.classList.remove('is-invalid');
                document.getElementById('categoria-feedback').style.display = 'none';
            }
            
            // Validar tipo (achado/perdido)
            if (!tipoAchado.checked && !tipoPerdido.checked) {
                document.getElementById('tipo-feedback').style.display = 'block !important';
                isValid = false;
            } else {
                document.getElementById('tipo-feedback').style.display = 'none !important';
            }
            
            // Validar data conforme o tipo selecionado
            if (tipoAchado.checked) {
                const dataEncontrado = document.getElementById('data_encontrado');
                if (!dataEncontrado.value) {
                    dataEncontrado.classList.add('is-invalid');
                    document.getElementById('data-encontrado-feedback').style.display = 'block';
                    isValid = false;
                } else {
                    dataEncontrado.classList.remove('is-invalid');
                    document.getElementById('data-encontrado-feedback').style.display = 'none';
                }
            } else if (tipoPerdido.checked) {
                const dataPerdido = document.getElementById('data_perdido');
                if (!dataPerdido.value) {
                    dataPerdido.classList.add('is-invalid');
                    document.getElementById('data-perdido-feedback').style.display = 'block';
                    isValid = false;
                } else {
                    dataPerdido.classList.remove('is-invalid');
                    document.getElementById('data-perdido-feedback').style.display = 'none';
                }
            }
            
            // Validar descrição
            const descricao = document.getElementById('descricao');
            if (descricao.value.length < 10 || descricao.value.length > 500) {
                descricao.classList.add('is-invalid');
                document.getElementById('descricao-feedback').style.display = 'block';
                isValid = false;
            } else {
                descricao.classList.remove('is-invalid');
                document.getElementById('descricao-feedback').style.display = 'none';
            }
            
            // Validar fotos (não obrigatório, apenas verifica se há fotos selecionadas para feedback)
            const temFotosExistentes = document.querySelectorAll('input[name="foto_principal"]').length > 0;
            if (fotosArray.length === 0 && !temFotosExistentes) {
                // Não marca como inválido, apenas mostra um aviso
                document.getElementById('foto_temporaria').classList.remove('is-invalid');
                
                // Se não existir feedback de foto, não precisa fazer nada
                if (document.getElementById('foto-feedback')) {
                    document.getElementById('foto-feedback').style.display = 'none';
                }
            }
            
            // Validar campos de localização
            const endereco = document.getElementById('endereco');
            if (!endereco.value) {
                endereco.classList.add('is-invalid');
                document.getElementById('endereco-feedback').style.display = 'block';
                isValid = false;
            } else {
                endereco.classList.remove('is-invalid');
                document.getElementById('endereco-feedback').style.display = 'none';
            }
            
            const nomeLocal = document.getElementById('nome_local');
            if (!nomeLocal.value) {
                nomeLocal.classList.add('is-invalid');
                document.getElementById('nome-local-feedback').style.display = 'block';
                isValid = false;
            } else {
                nomeLocal.classList.remove('is-invalid');
                document.getElementById('nome-local-feedback').style.display = 'none';
            }
            
            const referencia = document.getElementById('referencia');
            if (!referencia.value) {
                referencia.classList.add('is-invalid');
                document.getElementById('referencia-feedback').style.display = 'block';
                isValid = false;
            } else {
                referencia.classList.remove('is-invalid');
                document.getElementById('referencia-feedback').style.display = 'none';
            }
            
            // Se o formulário não for válido, impede o envio
            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
                // Rola para o primeiro elemento com erro
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
</script>
@endif
>>>>>>> funcionalidades
@endsection