<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Parceiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin: 50px auto;
            max-width: 800px;
        }
        .form-title {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .logo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
            margin: 0 auto 20px;
            display: block;
        }
        .logo-upload {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-upload input[type="file"] {
            display: none;
        }
        .logo-upload label {
            cursor: pointer;
            padding: 10px 20px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-block;
        }
        .logo-upload label:hover {
            background: #e9ecef;
        }
        .feedback-error {
            color: #dc3545;
            font-size: 80%;
            margin-top: 0.25rem;
            display: none;
        }
        .section-title {
            color: #3498db;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .map-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <a href="{{ route('login') }}" class="text-decoration-none mb-3 d-inline-block">
                <i class="fas fa-arrow-left me-2"></i>Voltar para Login
            </a>

            <h2 class="form-title">{{ isset($isEdit) && $isEdit ? 'Editar Cadastro de Parceiro' : 'Cadastro de Parceiro' }}</h2>
            <p class="text-center text-muted mb-4">
                @if(isset($isEdit) && $isEdit)
                    Atualize os dados do estabelecimento conforme solicitado pelo administrador
                @else
                    Preencha os dados do estabelecimento para se tornar um parceiro
                @endif
            </p>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('parceiro.store') }}" method="POST" enctype="multipart/form-data">
                @if(isset($isEdit) && $isEdit)
                    <input type="hidden" name="parceiro_id" value="{{ $parceiro->id }}">
                @endif
                @csrf

                <!-- Dados do Usuário -->
                <div class="form-section">
                    <h4 class="section-title">Dados do Responsável</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', isset($usuario) ? $usuario->name : '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                                   id="cpf" name="cpf" value="{{ old('cpf', isset($usuario) ? $usuario->cpf : '') }}" required placeholder="000.000.000-00">
                            @error('cpf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', isset($usuario) ? $usuario->email : '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone', isset($usuario) ? $usuario->telefone : '') }}" required placeholder="(00) 00000-0000">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                                   id="senha" name="senha" {{ isset($isEdit) && $isEdit ? '' : 'required' }}>
                            @if(isset($isEdit) && $isEdit)
                                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                            @endif
                            @error('senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" 
                                   id="senha_confirmation" name="senha_confirmation" {{ isset($isEdit) && $isEdit ? '' : 'required' }}>
                        </div>
                    </div>
                </div>

                <!-- Dados do Estabelecimento -->
                <div class="form-section">
                    <h4 class="section-title">Dados do Estabelecimento</h4>
                    <div class="text-center mb-4">
                        <img id="logo-preview" src="{{ isset($parceiro) && $parceiro->logo ? asset('storage/'.$parceiro->logo) : asset('images/default-logo.png') }}" class="logo-preview">
                        <div class="logo-upload">
                            <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                            <label for="logo">
                                <i class="fas fa-camera me-2"></i>Upload Logo
                            </label>
                            <small class="text-muted d-block">Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 2MB)</small>
                            <div class="feedback-error mt-2" id="logo-error">Selecione uma imagem válida nos formatos: JPG, JPEG, PNG ou GIF (máx. 2MB).</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nome_estabelecimento" class="form-label">Nome do Estabelecimento</label>
                        <input type="text" class="form-control @error('nome_estabelecimento') is-invalid @enderror" 
                               id="nome_estabelecimento" name="nome_estabelecimento" value="{{ old('nome_estabelecimento', isset($parceiro) ? $parceiro->nome_estabelecimento : '') }}" required>
                        @error('nome_estabelecimento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" 
                               id="cnpj" name="cnpj" value="{{ old('cnpj', isset($parceiro) ? $parceiro->cnpj : '') }}" required placeholder="00.000.000/0000-00">
                        @error('cnpj')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                  id="descricao" name="descricao" rows="3">{{ old('descricao', isset($parceiro) ? $parceiro->descricao : '') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="horario_funcionamento" class="form-label">Horário de Funcionamento</label>
                            <input type="text" class="form-control @error('horario_funcionamento') is-invalid @enderror" 
                                   id="horario_funcionamento" name="horario_funcionamento" value="{{ old('horario_funcionamento', isset($parceiro) ? $parceiro->horario_funcionamento : '') }}">
                            @error('horario_funcionamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone_comercial" class="form-label">Telefone Comercial</label>
                            <input type="text" class="form-control @error('telefone_comercial') is-invalid @enderror" 
                                   id="telefone_comercial" name="telefone_comercial" value="{{ old('telefone_comercial', isset($parceiro) ? $parceiro->telefone_comercial : '') }}" placeholder="(00) 00000-0000">
                            @error('telefone_comercial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_parceiro" class="form-label">Tipo de Parceiro</label>
                        <select class="form-select @error('tipo_parceiro') is-invalid @enderror" 
                                id="tipo_parceiro" name="tipo_parceiro" required>
                            <option value="">Selecione...</option>
                            <option value="ponto_coleta" {{ old('tipo_parceiro', isset($parceiro) ? $parceiro->tipo_parceiro : '') == 'ponto_coleta' ? 'selected' : '' }}>
                                Ponto de Coleta
                            </option>
                            <option value="evento" {{ old('tipo_parceiro', isset($parceiro) ? $parceiro->tipo_parceiro : '') == 'evento' ? 'selected' : '' }}>
                                Local de Evento
                            </option>
                            <option value="ambos" {{ old('tipo_parceiro', isset($parceiro) ? $parceiro->tipo_parceiro : '') == 'ambos' ? 'selected' : '' }}>
                                Ambos
                            </option>
                        </select>
                        @error('tipo_parceiro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Localização -->
                <div class="form-section">
                    <h4 class="section-title">Localização</h4>
                    <div class="mb-3">
                        <label for="nome_local" class="form-label">Nome do Local</label>
                        <input type="text" class="form-control @error('nome_local') is-invalid @enderror" 
                               id="nome_local" name="nome_local" value="{{ old('nome_local', isset($localizacao) ? $localizacao->nome_local : '') }}" required>
                        @error('nome_local')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                               id="endereco" name="endereco" value="{{ old('endereco', isset($localizacao) ? $localizacao->endereco : '') }}" required>
                        <div id="map-error" class="map-error d-none"></div>
                        @error('endereco')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude', isset($localizacao) ? $localizacao->latitude : '') }}" required readonly>
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude', isset($localizacao) ? $localizacao->longitude : '') }}" required readonly>
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="referencia" class="form-label">Ponto de Referência</label>
                        <input type="text" class="form-control @error('referencia') is-invalid @enderror" 
                               id="referencia" name="referencia" value="{{ old('referencia', isset($localizacao) ? $localizacao->referencia : '') }}">
                        @error('referencia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>{{ isset($isEdit) && $isEdit ? 'Atualizar Cadastro' : 'Cadastrar Parceiro' }}
                    </button>
                </div>

                <div class="text-center mt-3">
                    @if(!isset($isEdit) || !$isEdit)
                        <p class="mb-0">Já possui uma conta? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Faça login</a>
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funções de utilidade no escopo global
        function showError(input, errorDiv) {
            errorDiv.style.display = 'block';
            input.classList.add('is-invalid');
        }

        function hideError(input, errorDiv) {
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
        }

        function initGoogleMaps() {
            const googleMapsScript = document.createElement('script');
            googleMapsScript.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initAutocomplete`;
            googleMapsScript.async = true;
            googleMapsScript.defer = true;
            document.head.appendChild(googleMapsScript);
        }

        function initAutocomplete() {
            const enderecoInput = document.getElementById('endereco');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const nomeLocalInput = document.getElementById('nome_local');
            const mapError = document.getElementById('map-error');

            try {
                const autocomplete = new google.maps.places.Autocomplete(enderecoInput, {
                    types: ['address'],
                    componentRestrictions: { 
                        country: 'br'
                    },
                    bounds: {
                        north: -20.35,
                        south: -20.55,
                        east: -54.45,
                        west: -54.75
                    },
                    fields: ['address_components', 'geometry', 'formatted_address', 'name']
                });

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    
                    if (!place.geometry) {
                        mapError.textContent = 'Endereço não encontrado. Por favor, tente novamente.';
                        mapError.classList.remove('d-none');
                        return;
                    }

                    let isInCampoGrande = false;
                    let cidade = '';
                    let estado = '';

                    for (let i = 0; i < place.address_components.length; i++) {
                        const component = place.address_components[i];
                        if (component.types.includes('administrative_area_level_2')) {
                            cidade = component.long_name.toLowerCase();
                        }
                        if (component.types.includes('administrative_area_level_1')) {
                            estado = component.short_name;
                        }
                    }

                    isInCampoGrande = cidade.includes('campo grande') && estado === 'MS';

                    if (!isInCampoGrande) {
                        mapError.textContent = 'Por favor, selecione um endereço em Campo Grande, MS.';
                        mapError.classList.remove('d-none');
                        return;
                    }

                    latitudeInput.value = place.geometry.location.lat();
                    longitudeInput.value = place.geometry.location.lng();
                    enderecoInput.value = place.formatted_address;
                    mapError.classList.add('d-none');
                });
            } catch (error) {
                mapError.textContent = 'Erro ao carregar o mapa. Por favor, recarregue a página.';
                mapError.classList.remove('d-none');
                console.error('Erro ao inicializar o autocomplete:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initGoogleMaps();
            
            // Máscaras de formatação
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
                } else if (value.length > 3) {
                    value = value.replace(/^(\d{3})(\d{3})/, '$1.$2');
                }
                
                e.target.value = value;
            });

            const cnpjInput = document.getElementById('cnpj');
            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 14) value = value.slice(0, 14);
                
                if (value.length > 12) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                } else if (value.length > 8) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})/, '$1.$2.$3/$4');
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{3})/, '$1.$2');
                }
                
                e.target.value = value;
            });

            const telefoneInput = document.getElementById('telefone');
            const telefoneComercialInput = document.getElementById('telefone_comercial');
            
            function formatPhone(value) {
                value = value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 10) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{4})/, '($1) $2');
                } else if (value.length > 0) {
                    value = value.replace(/^(\d{2})/, '($1)');
                }
                
                return value;
            }

            telefoneInput.addEventListener('input', function(e) {
                e.target.value = formatPhone(e.target.value);
            });

            telefoneComercialInput.addEventListener('input', function(e) {
                e.target.value = formatPhone(e.target.value);
            });

            // Funções de validação já definidas no escopo global

            // Adiciona divs de erro para cada campo
            const fields = [
                { id: 'name', message: 'O nome deve ter no mínimo 3 caracteres.' },
                { id: 'email', message: 'Digite um endereço de e-mail válido.' },
                { id: 'cpf', message: 'Digite um CPF válido no formato 000.000.000-00.' },
                { id: 'telefone', message: 'Digite um telefone válido no formato (00) 00000-0000.' },
                { id: 'senha', message: 'A senha deve ter no mínimo 5 caracteres.' },
                { id: 'senha_confirmation', message: 'As senhas não coincidem.' },
                { id: 'nome_estabelecimento', message: 'O nome do estabelecimento é obrigatório.' },
                { id: 'cnpj', message: 'Digite um CNPJ válido no formato 00.000.000/0000-00.' },
                { id: 'telefone_comercial', message: 'Digite um telefone válido no formato (00) 00000-0000.' },
                { id: 'tipo_parceiro', message: 'Selecione um tipo de parceiro.' },
                { id: 'nome_local', message: 'O nome do local é obrigatório.' },
                { id: 'endereco', message: 'O endereço é obrigatório.' }
            ];

            // Cria as divs de erro
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    const errorDiv = document.createElement('div');
                    errorDiv.id = `${field.id}-error`;
                    errorDiv.className = 'feedback-error';
                    errorDiv.textContent = field.message;
                    input.parentNode.insertBefore(errorDiv, input.nextSibling);
                }
            });

            // Validações específicas
            function validateName() {
                const input = document.getElementById('name');
                const errorDiv = document.getElementById('name-error');
                if (input.value.length < 3) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validateEmail() {
                const input = document.getElementById('email');
                const errorDiv = document.getElementById('email-error');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validateCPF() {
                const input = document.getElementById('cpf');
                const errorDiv = document.getElementById('cpf-error');
                const cpf = input.value.replace(/\D/g, '');
                if (cpf.length !== 11) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validateCNPJ() {
                const input = document.getElementById('cnpj');
                const errorDiv = document.getElementById('cnpj-error');
                const cnpj = input.value.replace(/\D/g, '');
                if (cnpj.length !== 14) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validatePhone(inputId) {
                const input = document.getElementById(inputId);
                const errorDiv = document.getElementById(`${inputId}-error`);
                const phone = input.value.replace(/\D/g, '');
                if (phone.length < 10 || phone.length > 11) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validatePassword() {
                const input = document.getElementById('senha');
                const errorDiv = document.getElementById('senha-error');
                if (input.value.length < 5) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validatePasswordConfirmation() {
                const senha = document.getElementById('senha');
                const confirmation = document.getElementById('senha_confirmation');
                const errorDiv = document.getElementById('senha_confirmation-error');
                if (senha.value !== confirmation.value) {
                    showError(confirmation, errorDiv);
                    return false;
                }
                hideError(confirmation, errorDiv);
                return true;
            }

            function validateRequiredField(fieldId) {
                const input = document.getElementById(fieldId);
                const errorDiv = document.getElementById(`${fieldId}-error`);
                if (!input.value.trim()) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            // Validar CPF e CNPJ
            function validateDocument(input, regex, errorDiv) {
                const value = input.value.replace(/\D/g, '');
                const isValidFormat = regex.test(input.value);
                if (!isValidFormat) {
                    showError(input, errorDiv);
                    return false;
                }
                hideError(input, errorDiv);
                return true;
            }

            function validateLogo() {
                const input = document.getElementById('logo');
                const errorDiv = document.getElementById('logo-error');
                if (!input.files || !input.files[0]) {
                    return true; // Logo é opcional
                }
                
                const file = input.files[0];
                const fileType = file.type;
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!validTypes.includes(fileType)) {
                    errorDiv.textContent = 'A imagem deve ser do tipo: jpeg, jpg, png ou gif.';
                    showError(input, errorDiv);
                    return false;
                }
                
                if (file.size > maxSize) {
                    errorDiv.textContent = 'A imagem não pode ser maior que 2MB.';
                    showError(input, errorDiv);
                    return false;
                }
                
                hideError(input, errorDiv);
                return true;
            }

            // Validação do CPF
            function validateCPF() {
                const input = document.getElementById('cpf');
                const errorDiv = document.getElementById('cpf-error');
                const regex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
                return validateDocument(input, regex, errorDiv);
            }

            // Validação do CNPJ
            function validateCNPJ() {
                const input = document.getElementById('cnpj');
                const errorDiv = document.getElementById('cnpj-error');
                const regex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
                return validateDocument(input, regex, errorDiv);
            }

            // Adiciona eventos de validação
            document.getElementById('logo').addEventListener('change', validateLogo);
            document.getElementById('cpf').addEventListener('input', validateCPF);
            document.getElementById('cnpj').addEventListener('input', validateCNPJ);
            document.getElementById('name').addEventListener('input', validateName);
            document.getElementById('email').addEventListener('input', validateEmail);
            document.getElementById('cpf').addEventListener('input', validateCPF);
            document.getElementById('cnpj').addEventListener('input', validateCNPJ);
            document.getElementById('telefone').addEventListener('input', () => validatePhone('telefone'));
            document.getElementById('telefone_comercial').addEventListener('input', () => validatePhone('telefone_comercial'));
            document.getElementById('senha').addEventListener('input', validatePassword);
            document.getElementById('senha_confirmation').addEventListener('input', validatePasswordConfirmation);
            document.getElementById('senha').addEventListener('input', validatePasswordConfirmation);
            document.getElementById('nome_estabelecimento').addEventListener('input', () => validateRequiredField('nome_estabelecimento'));
            document.getElementById('tipo_parceiro').addEventListener('change', () => validateRequiredField('tipo_parceiro'));
            document.getElementById('nome_local').addEventListener('input', () => validateRequiredField('nome_local'));

            // Validação do formulário antes do envio
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Validar todos os campos obrigatórios
                if (!validateName()) isValid = false;
                if (!validateEmail()) isValid = false;
                if (!validateCPF()) isValid = false;
                if (!validatePhone('telefone')) isValid = false;
                if (!validatePassword()) isValid = false;
                if (!validatePasswordConfirmation()) isValid = false;
                if (!validateRequiredField('nome_estabelecimento')) isValid = false;
                if (!validateCNPJ()) isValid = false;
                if (!validatePhone('telefone_comercial')) isValid = false;
                if (!validateRequiredField('tipo_parceiro')) isValid = false;
                if (!validateRequiredField('nome_local')) isValid = false;
                if (!validateRequiredField('endereco')) isValid = false;

                // Validar localização
                const latitude = document.getElementById('latitude');
                const longitude = document.getElementById('longitude');
                if (!latitude.value || !longitude.value) {
                    const mapError = document.getElementById('map-error');
                    mapError.textContent = 'Por favor, selecione um endereço válido no mapa.';
                    mapError.classList.remove('d-none');
                    isValid = false;
                }

                // Novas validações
                if (!validateCPF()) isValid = false;
                if (!validateCNPJ()) isValid = false;
                if (!validateLogo()) isValid = false;

                if (!isValid) {
                    event.preventDefault();
                    // Rola até o primeiro erro
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });

        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const logoError = document.getElementById('logo-error');
                const preview = document.getElementById('logo-preview');
                
                // Verifica o tipo do arquivo
                const fileType = file.type;
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                // Verifica o tamanho (máximo 2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                
                if (!validTypes.includes(fileType)) {
                    showError(input, logoError);
                    return;
                }
                
                if (file.size > maxSize) {
                    logoError.textContent = "A imagem deve ter no máximo 2MB.";
                    showError(input, logoError);
                    return;
                }
                
                hideError(input, logoError);
                
                // Criar um objeto URL para a prévia imediata
                preview.src = URL.createObjectURL(file);
                
                // Também usar FileReader como backup
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>