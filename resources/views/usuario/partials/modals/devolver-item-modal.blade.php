@if(isset($item))
<div class="modal fade" id="marcarDevolvidoModal-{{ $item->id }}" tabindex="-1" aria-labelledby="marcarDevolvidoModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="marcarDevolvidoModalLabel-{{ $item->id }}">Marcar Item como Devolvido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="formDevolverItem-{{ $item->id }}" method="POST" action="{{ route('item.marcar-como-devolvido', $item->id) }}">
                    @csrf
                    <input type="hidden" name="tipo_item" value="{{ $item->tipo }}">
                    
                    @if($item->tipo === 'achado')
                    <!-- Opções para item ACHADO -->
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Item Achado:</strong> Informe como você devolveu este item ao seu dono.
                    </div>
                    
                    <div class="mb-3">
                        <label for="metodo_devolucao" class="form-label">Como o item foi devolvido?</label>
                        <select class="form-select" id="metodo_devolucao" name="metodo_devolucao" required>
                            <option value="" selected disabled>Selecione uma opção</option>
                            <option value="usuario">Devolvi diretamente para o dono</option>
                        </select>
                    </div>
                    
                    <!-- Campo para informar o dono do item (obrigatório para itens achados) -->
                    <div class="mb-3">
                        <label for="usuario_email" class="form-label">Email do dono do item que recebeu a devolução</label>
                        <input type="text" class="form-control" id="usuario_email" name="usuario_email" placeholder="Digite o email do dono (recomendado)">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        <div id="usuarioSugestoes" class="list-group mt-1 d-none"></div>
                        <small class="text-muted">Comece a digitar para buscar usuários cadastrados</small>
                    </div>
                    @else
                    <!-- Opções para item PERDIDO -->
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Item Perdido:</strong> Informe como você recuperou este item.
                    </div>
                    
                    <div class="mb-3">
                        <label for="metodo_devolucao" class="form-label">Como o item foi recuperado?</label>
                        <select class="form-select" id="metodo_devolucao" name="metodo_devolucao" required>
                            <option value="" selected disabled>Selecione uma opção</option>
                            <option value="usuario">Um usuário do sistema encontrou e me devolveu pessoalmente</option>
                            <option value="parceiro">Recuperei em um ponto de coleta parceiro</option>
                            <option value="proprio">Eu mesmo encontrei ou alguém me devolveu (fora do sistema)</option>
                        </select>
                    </div>
                    
                    <!-- Campos condicionais para contato direto (quando alguém devolveu) -->
                    <div class="mb-3 campo-contato-direto d-none">
                        <label for="usuario_email" class="form-label">Email da pessoa que devolveu o item</label>
                        <input type="text" class="form-control" id="usuario_email" name="usuario_email" placeholder="Digite o email da pessoa (recomendado)">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        <div id="usuarioSugestoes" class="list-group mt-1 d-none"></div>
                        <small class="text-muted">Comece a digitar para buscar usuários cadastrados</small>
                    </div>
                    @endif
                    
                    <!-- Campos condicionais para parceiro -->
                    <div class="mb-3 campo-parceiro d-none">
                        <label for="parceiro_id" class="form-label">Selecione o parceiro onde recuperou o item</label>
                        
                        <!-- Mapa para seleção de parceiros -->
                        <div id="mapa-parceiros-{{ $item->id }}" class="mb-3" style="height: 300px; width: 100%; border-radius: 8px; position: relative; overflow: hidden; display: block;">
                            <!-- Mensagem de carregamento -->
                            <div class="d-flex justify-content-center align-items-center" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255,255,255,0.7); z-index: 1;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando mapa...</span>
                                </div>
                                <span class="ms-2">Carregando mapa...</span>
                            </div>
                        </div>
                        
                        <!-- Nota: O carregamento da API do Google Maps foi movido para o final do arquivo -->
                        
                        <select class="form-select mt-3" id="parceiro_id" name="parceiro_id">
                            <option value="" selected disabled>Selecione um parceiro</option>
                            @foreach(\App\Models\Parceiro::where('ativo', true)->get() as $parceiro)
                                <option value="{{ $parceiro->id }}" 
                                    data-lat="{{ $parceiro->localizacao->latitude }}" 
                                    data-lng="{{ $parceiro->localizacao->longitude }}"
                                    data-logo="{{ $parceiro->logo ? asset('storage/' . $parceiro->logo) : asset('images/placeholder-logo.png') }}"
                                    data-horario="{{ $parceiro->horario_funcionamento ?: 'Horu00e1rio nu00e3o informado' }}"
                                    data-telefone="{{ $parceiro->telefone_comercial ?: 'Telefone nu00e3o informado' }}"
                                    data-tipo="{{ $parceiro->tipo_parceiro }}"
                                >
                                    {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if($item->tipo === 'achado')
                    <div class="mb-3">
                        <label for="detalhes" class="form-label">Detalhes da devolução</label>
                        <textarea class="form-control" id="detalhes" name="detalhes" rows="3" required placeholder="Descreva como, quando e onde você devolveu o item para o dono"></textarea>
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="detalhes" class="form-label">Detalhes da recuperação</label>
                        <textarea class="form-control" id="detalhes" name="detalhes" rows="3" required placeholder="Descreva como, quando e onde você recuperou seu item"></textarea>
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                @if($item->tipo === 'achado')
                <button type="button" class="btn btn-success" id="btnConfirmarDevolucao-{{ $item->id }}">
                    <i class="fas fa-check-circle me-2"></i>Confirmar Devolução
                </button>
                @else
                <button type="button" class="btn btn-success" id="btnConfirmarDevolucao-{{ $item->id }}">
                    <i class="fas fa-check-circle me-2"></i>Confirmar Recuperação
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Identificadores específicos para este item
        const itemId = '{{ $item->id }}';
        const metodoSelectId = 'metodo_devolucao';
        const formId = `formDevolverItem-${itemId}`;
        const btnConfirmarId = `btnConfirmarDevolucao-${itemId}`;
        
        // Mostrar/esconder campos condicionais baseado no método de devolução
        const metodoSelect = document.querySelector(`#marcarDevolvidoModal-${itemId} #metodo_devolucao`);
        console.log('Seletor do metodo:', `#marcarDevolvidoModal-${itemId} #metodo_devolucao`);
        console.log('Elemento encontrado:', metodoSelect);
        
        if (metodoSelect) {
            const camposContatoDireto = document.querySelectorAll(`#marcarDevolvidoModal-${itemId} .campo-contato-direto`);
            const camposParceiro = document.querySelectorAll(`#marcarDevolvidoModal-${itemId} .campo-parceiro`);
            
            console.log('Campos contato direto:', camposContatoDireto);
            console.log('Campos parceiro:', camposParceiro);
            
            // Inicializar campos baseado no valor inicial do select
            if (metodoSelect.value === 'usuario') {
                camposContatoDireto.forEach(campo => campo.classList.remove('d-none'));
            } else if (metodoSelect.value === 'parceiro') {
                camposParceiro.forEach(campo => campo.classList.remove('d-none'));
                // Tentar inicializar o mapa se o campo parceiro estiver visível
                if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                    setTimeout(() => {
                        initParceiroMap();
                    }, 500);
                }
            }
            
            metodoSelect.addEventListener('change', function() {
                console.log('Método selecionado:', this.value);
                
                // Esconde todos os campos condicionais primeiro
                camposContatoDireto.forEach(campo => campo.classList.add('d-none'));
                camposParceiro.forEach(campo => campo.classList.add('d-none'));
                
                // Mostra os campos relevantes baseado na seleção
                if (this.value === 'usuario') {
                    camposContatoDireto.forEach(campo => campo.classList.remove('d-none'));
                } else if (this.value === 'parceiro') {
                    camposParceiro.forEach(campo => campo.classList.remove('d-none'));
                    
                    // Tentar inicializar o mapa quando a opção parceiro for selecionada
                    console.log('Opção parceiro selecionada, tentando inicializar mapa');
                    
                    // Dar um tempo para a div se tornar visível antes de inicializar o mapa
                    setTimeout(() => {
                        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                            console.log('Google Maps API disponível, inicializando mapa');
                            initParceiroMap();
                            
                            // Forçar o redimensionamento do mapa após inicialização
                            if (window.parceiroMap) {
                                setTimeout(() => {
                                    console.log('Forçando redimensionamento do mapa');
                                    google.maps.event.trigger(window.parceiroMap, 'resize');
                                    
                                    // Centralizar o mapa novamente após o redimensionamento
                                    window.parceiroMap.setCenter({ lat: -7.2290, lng: -35.8811 });
                                }, 300);
                            }
                        } else {
                            console.log('Google Maps API não disponível, carregando script');
                            // Carregar a API do Google Maps se não estiver disponível
                            const script = document.createElement('script');
                            script.src = `https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initParceiroMap`;
                            script.async = true;
                            script.defer = true;
                            document.head.appendChild(script);
                        }
                    }, 500);
                }
            });
        }
        
        // Autocomplete para busca de usuários por email
        const usuarioEmailInput = document.querySelector(`#marcarDevolvidoModal-${itemId} #usuario_email`);
        const usuarioIdInput = document.querySelector(`#marcarDevolvidoModal-${itemId} #usuario_id`);
        const usuarioSugestoes = document.querySelector(`#marcarDevolvidoModal-${itemId} #usuarioSugestoes`);
        
        if (usuarioEmailInput && usuarioIdInput && usuarioSugestoes) {
            usuarioEmailInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                if (query.length < 3) {
                    usuarioSugestoes.classList.add('d-none');
                    usuarioSugestoes.innerHTML = '';
                    return;
                }
                
                // Busca usuários por email
                const baseUrl = window.location.origin;
                fetch(`${baseUrl}/api/usuarios/buscar?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro HTTP! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        usuarioSugestoes.innerHTML = '';
                        
                        if (data.length > 0) {
                            usuarioSugestoes.classList.remove('d-none');
                            
                            data.forEach(user => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                                
                                // Criando elementos para nome e email com estilo melhorado
                                const userInfo = document.createElement('div');
                                const userName = document.createElement('strong');
                                userName.textContent = user.name;
                                const userEmail = document.createElement('small');
                                userEmail.className = 'text-muted ms-2';
                                userEmail.textContent = user.email;
                                
                                userInfo.appendChild(userName);
                                userInfo.appendChild(document.createTextNode(' '));
                                userInfo.appendChild(userEmail);
                                
                                item.appendChild(userInfo);
                                
                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    usuarioEmailInput.value = user.email;
                                    usuarioIdInput.value = user.id;
                                    usuarioSugestoes.classList.add('d-none');
                                });
                                
                                usuarioSugestoes.appendChild(item);
                            });
                        } else {
                            usuarioSugestoes.classList.add('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar usuários:', error);
                    });
            });
        }
        
        // Enviar formulário ao clicar no botão de confirmar
        const btnConfirmar = document.querySelector(`#marcarDevolvidoModal-${itemId} #btnConfirmarDevolucao-${itemId}`);
        console.log('Botão de confirmar:', btnConfirmar);
        const form = document.getElementById(formId);
        console.log('Formulário:', form);
        
        if (btnConfirmar && form) {
            btnConfirmar.addEventListener('click', function() {
                // Validação básica
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                form.submit();
            });
        }
    });
    // Inicializau00e7u00e3o do mapa para seleu00e7u00e3o de parceiros
    // Usamos window para evitar declarau00e7u00f5es duplicadas
    window.parceiroMap = window.parceiroMap || null;
    window.parceiroMarkers = window.parceiroMarkers || [];
    window.parceiroInfoWindows = window.parceiroInfoWindows || [];
    
    function initParceiroMap() {
        console.log('Inicializando mapa de parceiros...');
        const itemId = '{{ $item->id }}';
        const mapDiv = document.getElementById(`mapa-parceiros-${itemId}`);
        const parceiroSelect = document.querySelector(`#marcarDevolvidoModal-${itemId} #parceiro_id`);
        
        if (!mapDiv) {
            console.error('Elemento do mapa não encontrado:', `mapa-parceiros-${itemId}`);
            return;
        }
        
        if (!parceiroSelect) {
            console.error('Select de parceiros não encontrado');
            return;
        }
        
        console.log('Elementos encontrados, criando mapa...');
        
        // Garantir que o div do mapa esteja visível
        mapDiv.style.display = 'block';
        
        // Inicializar o mapa com o centro em Campina Grande
        try {
            window.parceiroMap = new google.maps.Map(mapDiv, {
                center: { lat: -7.2290, lng: -35.8811 },  // Coordenadas de Campina Grande
                zoom: 13,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true
            });
            
            console.log('Mapa criado com sucesso');
            
            // Remover a mensagem de carregamento
            const loadingDiv = mapDiv.querySelector('.d-flex.justify-content-center');
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
        } catch (error) {
            console.error('Erro ao criar o mapa:', error);
        }
        
        // Adicionar marcadores para cada parceiro
        const options = parceiroSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === '') continue; // Pular a opu00e7u00e3o 'Selecione um parceiro'
            
            const lat = parseFloat(options[i].dataset.lat);
            const lng = parseFloat(options[i].dataset.lng);
            const nome = options[i].text;
            const id = options[i].value;
            
            if (isNaN(lat) || isNaN(lng)) continue;
            
            const marker = new google.maps.Marker({
                position: { lat, lng },
                map: parceiroMap,
                title: nome,
                animation: google.maps.Animation.DROP,
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                }
            });
            
            // Criar conteudo mais detalhado para a janela de informau00e7u00e3o
            const infoWindowContent = document.createElement('div');
            infoWindowContent.className = 'parceiro-info-window';
            infoWindowContent.style.padding = '10px';
            infoWindowContent.style.maxWidth = '300px';
            
            // Verificar se o parceiro tem logo
            const logoUrl = options[i].dataset.logo || 'https://via.placeholder.com/80x80?text=Logo';
            
            // Obter horario de funcionamento
            const horario = options[i].dataset.horario || 'Horu00e1rio nu00e3o informado';
            
            // Obter telefone
            const telefone = options[i].dataset.telefone || 'Telefone nu00e3o informado';
            
            // Obter tipo de parceiro
            const tipoParceiro = options[i].dataset.tipo || '';
            const tipoParceiroTexto = tipoParceiro === 'ponto_coleta' ? 'Ponto de Coleta' : 
                                    tipoParceiro === 'evento' ? 'Local de Evento' : 
                                    tipoParceiro === 'ambos' ? 'Ponto de Coleta e Local de Evento' : '';
            
            // Montar HTML da janela de informau00e7u00e3o
            infoWindowContent.innerHTML = `
                <div class="d-flex align-items-start mb-2">
                    <div class="me-3">
                        <img src="${logoUrl}" alt="Logo ${nome}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                    </div>
                    <div>
                        <h5 class="mb-1">${nome}</h5>
                        <div class="badge bg-primary mb-2">${tipoParceiroTexto}</div>
                    </div>
                </div>
                <div class="mb-2">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                    <small>${options[i].text.split(' - ')[1] || 'Endereço não disponível'}</small>
                </div>
                <div class="mb-2">
                    <i class="fas fa-clock text-secondary me-2"></i>
                    <small>${horario}</small>
                </div>
                <div class="mb-2">
                    <i class="fas fa-phone text-success me-2"></i>
                    <small>${telefone}</small>
                </div>
                <button class="btn btn-sm btn-primary mt-2 w-100" onclick="document.getElementById('parceiro_id').value = '${id}'; document.getElementById('parceiro_id').dispatchEvent(new Event('change', {bubbles: false}));">
                    <i class="fas fa-check-circle me-1"></i> Selecionar este parceiro
                </button>
            `;
            
            const infoWindow = new google.maps.InfoWindow({
                content: infoWindowContent
            });
            
            marker.addListener('click', () => {
                try {
                    console.log('Marcador clicado:', nome);
                    
                    // Fechar todas as janelas de informau00e7u00e3o abertas
                    if (window.parceiroInfoWindows && window.parceiroInfoWindows.length) {
                        window.parceiroInfoWindows.forEach(info => {
                            if (info && typeof info.close === 'function') {
                                info.close();
                            }
                        });
                    }
                    
                    // Abrir a janela de informau00e7u00e3o deste marcador
                    infoWindow.open(window.parceiroMap, marker);
                    
                    // Selecionar o parceiro no select de forma segura
                    if (parceiroSelect && parceiroSelect.options) {
                        // Verificar se o valor existe nas opu00e7u00f5es antes de definir
                        let optionExists = false;
                        for (let i = 0; i < parceiroSelect.options.length; i++) {
                            if (parceiroSelect.options[i].value === id) {
                                optionExists = true;
                                break;
                            }
                        }
                        
                        if (optionExists) {
                            parceiroSelect.value = id;
                            console.log('Parceiro selecionado no dropdown:', id);
                            
                            // Usar um evento mais simples e evitar loops infinitos
                            // Apenas notificar a mudanu00e7a sem acionar outros eventos
                            parceiroSelect.dispatchEvent(new Event('change', { bubbles: false }));
                        } else {
                            console.error('Valor do parceiro nu00e3o encontrado nas opu00e7u00f5es:', id);
                        }
                    }
                } catch (error) {
                    console.error('Erro ao processar clique no marcador:', error);
                }
            });
            
            window.parceiroMarkers.push(marker);
            window.parceiroInfoWindows.push(infoWindow);
        }
        
        // Quando o usuu00e1rio selecionar um parceiro no dropdown, centralizar o mapa nele
        // Usamos uma varu00edu00e1vel para evitar loops infinitos
        let isSelectChangeFromMarker = false;
        
        parceiroSelect.addEventListener('change', function(event) {
            try {
                console.log('Dropdown parceiro alterado');
                
                // Se a mudanu00e7a foi causada por um clique no marcador, nu00e3o fazer nada para evitar loops
                if (event.bubbles === false || isSelectChangeFromMarker) {
                    console.log('Mudanu00e7a ignorada para evitar loops');
                    return;
                }
                
                isSelectChangeFromMarker = true; // Marcar que estamos processando uma mudanu00e7a
                
                const selectedOption = this.options[this.selectedIndex];
                if (!selectedOption || selectedOption.value === '') {
                    isSelectChangeFromMarker = false;
                    return;
                }
                
                const lat = parseFloat(selectedOption.dataset.lat);
                const lng = parseFloat(selectedOption.dataset.lng);
                
                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Coordenadas invu00e1lidas para o parceiro:', selectedOption.value);
                    isSelectChangeFromMarker = false;
                    return;
                }
                
                console.log('Centralizando mapa em:', lat, lng);
                
                // Centralizar o mapa na posiu00e7u00e3o do parceiro selecionado
                if (window.parceiroMap) {
                    window.parceiroMap.setCenter({ lat, lng });
                    window.parceiroMap.setZoom(15);
                }
                
                // Encontrar o marcador correspondente sem clicar nele (para evitar loops)
                const markerIndex = Array.from(options).findIndex(opt => opt.value === selectedOption.value) - 1;
                if (markerIndex >= 0 && markerIndex < window.parceiroMarkers.length) {
                    // Apenas abrir a janela de informau00e7u00e3o sem disparar o evento de clique
                    const marker = window.parceiroMarkers[markerIndex];
                    const infoWindow = window.parceiroInfoWindows[markerIndex];
                    
                    if (infoWindow) {
                        // Fechar todas as outras janelas de informau00e7u00e3o
                        window.parceiroInfoWindows.forEach((info, i) => {
                            if (i !== markerIndex && info && typeof info.close === 'function') {
                                info.close();
                            }
                        });
                        
                        // Abrir a janela de informau00e7u00e3o deste marcador
                        infoWindow.open(window.parceiroMap, marker);
                    }
                }
                
                isSelectChangeFromMarker = false; // Resetar a flag
            } catch (error) {
                console.error('Erro ao processar mudanu00e7a no dropdown:', error);
                isSelectChangeFromMarker = false;
            }
        });
    }
    
    // Carregar o mapa quando o modal for aberto e a opu00e7u00e3o 'parceiro' for selecionada
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM carregado, configurando eventos para o mapa de parceiros');
        const itemId = '{{ $item->id }}';
        const modal = document.getElementById(`marcarDevolvidoModal-${itemId}`);
        const metodoSelect = document.querySelector(`#marcarDevolvidoModal-${itemId} #metodo_devolucao`);
        
        if (!modal) {
            console.error('Modal não encontrado:', `marcarDevolvidoModal-${itemId}`);
            return;
        }
        
        if (!metodoSelect) {
            console.error('Select de método não encontrado');
            return;
        }
        
        console.log('Modal e select encontrados, configurando eventos');
        
        // Função para inicializar o mapa com retry
        function initMapWithRetry(attempts = 0) {
            console.log(`Tentativa ${attempts + 1} de inicializar o mapa`);
            
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                console.log('API do Google Maps já carregada, inicializando mapa');
                setTimeout(() => {
                    initParceiroMap();
                }, 500); // Atraso para garantir que o modal esteja completamente visível
            } else if (attempts < 3) { // Limite de tentativas
                console.log('API do Google Maps não disponível, tentando novamente em 1 segundo');
                setTimeout(() => {
                    initMapWithRetry(attempts + 1);
                }, 1000);
            } else {
                console.error('Falha ao carregar a API do Google Maps após várias tentativas');
                // Tentar carregar a API novamente
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initParceiroMap`;
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);
            }
        }
        
        // Quando o modal for aberto
        modal.addEventListener('shown.bs.modal', function() {
            console.log('Modal aberto, verificando método selecionado:', metodoSelect.value);
            if (metodoSelect.value === 'parceiro') {
                console.log('Método parceiro selecionado, iniciando mapa');
                initMapWithRetry();
            }
        });
        
        // Quando o método de devoluu00e7u00e3o mudar para 'parceiro'
        metodoSelect.addEventListener('change', function() {
            console.log('Método alterado para:', this.value);
            if (this.value === 'parceiro' && modal.classList.contains('show')) {
                console.log('Método parceiro selecionado e modal visível, iniciando mapa');
                initMapWithRetry();
            }
        });
        
        // Forçar um redimensionamento do mapa quando a div se tornar visível
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class' && !mutation.target.classList.contains('d-none') && window.parceiroMap) {
                    console.log('Div do mapa se tornou visível, redimensionando mapa');
                    google.maps.event.trigger(window.parceiroMap, 'resize');
                }
            });
        });
        
        const camposParceiro = document.querySelectorAll(`#marcarDevolvidoModal-${itemId} .campo-parceiro`);
        camposParceiro.forEach(campo => {
            observer.observe(campo, { attributes: true });
        });
    });
</script>

<!-- Nota: O script do Google Maps deve ser carregado apenas uma vez na pu00e1gina principal -->
@if(!isset($googleMapsLoaded))
    @php $googleMapsLoaded = true; @endphp
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
@endif
@endif