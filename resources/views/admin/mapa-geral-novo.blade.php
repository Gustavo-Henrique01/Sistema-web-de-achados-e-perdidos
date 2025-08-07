@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white shadow-sm rounded-3 p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h2 class="fw-bold mb-0"><i class="fas fa-map-marked-alt me-2 text-primary"></i>Mapa Geral</h2>
                        <p class="text-muted mb-0">Visualização geográfica de itens e estabelecimentos parceiros</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.principal') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mapa e Filtros -->
    <div class="row">
        <!-- Coluna do Mapa -->
        <div class="col-md-9 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div id="map" style="width: 100%; height: 600px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Coluna de Filtros -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <!-- Tipo de Item -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Item</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_todos" value="" checked>
                                    <label class="form-check-label" for="tipo_todos">Todos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_achados" value="achado">
                                    <label class="form-check-label" for="tipo_achados">Achados</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo" id="tipo_perdidos" value="perdido">
                                    <label class="form-check-label" for="tipo_perdidos">Perdidos</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_todos" value="" checked>
                                    <label class="form-check-label" for="status_todos">Todos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_aprovado" value="aprovado">
                                    <label class="form-check-label" for="status_aprovado">Aprovados</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_estabelecimento" value="em_estabelecimento">
                                    <label class="form-check-label" for="status_estabelecimento">Em Estabelecimentos</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categoria -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Categoria</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="">Todas as categorias</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Data -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Data</label>
                            <input type="date" class="form-control" id="data" name="data">
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" id="aplicarFiltros">
                                <i class="fas fa-filter me-1"></i>Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="resetarFiltros">
                                <i class="fas fa-undo me-1"></i>Limpar Filtros
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Legenda -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Legenda</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #4CAF50; border-radius: 50%;"></div>
                        <span>Itens Achados</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #FFC107; border-radius: 50%;"></div>
                        <span>Itens Perdidos</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #2196F3; border-radius: 50%;"></div>
                        <span>Estabelecimentos Parceiros</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #8812ac; border-radius: 50%;"></div>
                        <span>Em Estabelecimentos Parceiros</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variáveis globais
    let map;
    let markers = [];
    let infoWindow;
    
    // Dados dos itens e parceiros
    const itens = @json($itens);
    const parceiros = @json($parceiros);
    
   
    
    // Definir URLs base para os links (usando as rotas corretas do sistema)
    const urlBaseItens = '{{ route("admin.ver-detalhes-item", ["id" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', '');
    const urlBaseParceiros = '{{ route("admin.parceiros.show", ["parceiro" => "ID_PLACEHOLDER"]) }}'.replace('ID_PLACEHOLDER', '');
    
    // Logar as URLs base para diagnóstico
    console.log('URL base para itens:', urlBaseItens);
    console.log('URL base para parceiros:', urlBaseParceiros);
    
    // Logar os dados para diagnóstico
    console.log('Itens recebidos:', itens);
    console.log('Parceiros recebidos:', parceiros);
    
    // Inicialização do mapa
    function initMap() {
        console.log('Inicializando mapa...');
        
        // Verificar se temos dados válidos
        if (!itens || !parceiros) {
            console.error('Dados de itens ou parceiros não disponíveis');
            return; // Encerrar a função se os dados não estiverem disponíveis
        }
        
        // Verificar estrutura dos dados
        if (itens.length > 0) {
            console.log('Estrutura do primeiro item:', JSON.stringify(itens[0]));
        }
        
        if (parceiros.length > 0) {
            console.log('Estrutura do primeiro parceiro:', JSON.stringify(parceiros[0]));
        }
        
        // Criar mapa centrado no Brasil
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: -20.4697, lng: -54.6201 }, // Coordenadas de Brasília
            zoom: 12,
            mapTypeControl: true,
            fullscreenControl: true,
            streetViewControl: false,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            }
        });
        
        // Criar janela de informações
        infoWindow = new google.maps.InfoWindow({
            maxWidth: 350
        });
        
        // Carregar marcadores
        carregarMarcadores();
        
        console.log('Mapa inicializado com sucesso!');
    }
    
    // Função para carregar marcadores no mapa
    function carregarMarcadores() {
        console.log('Carregando marcadores...');
        
        // Limpar marcadores existentes
        limparMarcadores();
        
        // Verificar se os elementos do filtro existem
        const tipoInput = document.querySelector('input[name="tipo"]:checked');
        const statusInput = document.querySelector('input[name="status"]:checked');
        const categoriaInput = document.getElementById('categoria');
        const dataInput = document.getElementById('data');
        
        // Filtros ativos (com verificação de existência)
        const tipoFiltro = tipoInput ? tipoInput.value : '';
        const statusFiltro = statusInput ? statusInput.value : '';
        const categoriaFiltro = categoriaInput ? categoriaInput.value : '';
        const dataFiltro = dataInput ? dataInput.value : '';
        
        console.log('Filtros:', { tipoFiltro, statusFiltro, categoriaFiltro, dataFiltro });
                // Adicionar marcadores de itens
        itens.forEach(item => {
            // Verificar filtros
            if (tipoFiltro && item.tipo !== tipoFiltro) return;
            
            // Verificar status - mostrar apenas itens aprovados ou em estabelecimento por padrão
            if (statusFiltro) {
                if (item.status !== statusFiltro) return;
            } else {
                // Se não houver filtro de status, mostrar apenas itens aprovados ou em estabelecimento
                if (item.status !== 'aprovado' && item.status !== 'em_estabelecimento') return;
            }
            
            // Verificar se o item tem localização
            if (!item.localizacao || !item.localizacao.latitude || !item.localizacao.longitude) {
                console.log(`Item ${item.id} (${item.descricao || 'Sem descrição'}) não tem localização válida:`, item.localizacao);
                return;
            }
            
            // Verificar se as coordenadas são números válidos
            const lat = parseFloat(item.localizacao.latitude);
            const lng = parseFloat(item.localizacao.longitude);
            if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
                console.log(`Item ${item.id} (${item.descricao || 'Sem descrição'}) tem coordenadas inválidas: lat=${lat}, lng=${lng}`);
                return;
            }
            
            if (categoriaFiltro && item.id_categoria != categoriaFiltro) return;
            if (dataFiltro) {
                const itemData = new Date(item.created_at).toISOString().split('T')[0];
                if (itemData !== dataFiltro) return;
            }
            
            // Definir cor do marcador baseado no tipo do item e status
            let iconeUrl;
            if (item.status === 'em_estabelecimento') {
                iconeUrl = 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png'; // Cor roxa para itens em estabelecimento
            } else if (item.tipo === 'achado') {
                iconeUrl = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'; // Cor verde para itens achados
            } else {
                iconeUrl = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'; // Cor amarela para itens perdidos
            }
            
            // Criar marcador
            try {
                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(item.localizacao.latitude), 
                        lng: parseFloat(item.localizacao.longitude) 
                    },
                    map: map,
                    title: item.titulo,
                    icon: iconeUrl
                });
                
                // Conteúdo da janela de informações com foto
                let fotoHtml = '';
                try {
                    if (item.fotos && Array.isArray(item.fotos) && item.fotos.length > 0 && item.fotos[0].caminho) {
                        fotoHtml = `<img src="/storage/${item.fotos[0].caminho}" alt="${item.descricao || 'Item'}" class="img-fluid mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">` 
                    }
                } catch (e) {
                    console.error('Erro ao processar foto do item:', e);
                    fotoHtml = '';
                }
                                // Verificar se o item está em um estabelecimento parceiro
                let estabelecimentoInfo = '';
                if (item.status === 'em_estabelecimento' && item.parceiro) {
                    // Verificar se o parceiro existe e tem os dados necessários
                    const nomeEstabelecimento = item.parceiro.nome_estabelecimento || 'Não informado';
                    
                    let endereco = 'Não informado';
                    if (item.parceiro.localizacao) {
                        endereco = item.parceiro.localizacao.endereco || 'Não informado';
                    }
                    
                    const telefone = item.parceiro.telefone_comercial || 'Não informado';
                    const parceiroId = item.parceiro.id || '';
                    
                    estabelecimentoInfo = `
                        <div class="mt-2 mb-2 p-2 bg-light rounded">
                            <h6 class="mb-2 border-bottom pb-2"><i class="fas fa-store me-1 text-primary"></i> Item em Estabelecimento Parceiro</h6>
                            ${item.parceiro.logo ? `<div class="text-center mb-2"><img src="/storage/${item.parceiro.logo}" alt="Logo ${nomeEstabelecimento}" class="img-fluid" style="max-height: 60px; max-width: 100%;"></div>` : ''}
                            <p class="mb-1"><strong>Estabelecimento:</strong> ${nomeEstabelecimento}</p>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1 text-primary"></i> <strong>Endereço:</strong> ${endereco}</p>
                            <p class="mb-1"><i class="fas fa-phone me-1 text-primary"></i> <strong>Telefone:</strong> ${telefone}</p>
                            <p class="mb-0"><i class="fas fa-clock me-1 text-primary"></i> <strong>Horário:</strong> ${item.parceiro.horario_funcionamento || 'Não informado'}</p>
                            <div class="mt-2">
                                <a href="${urlBaseParceiros}${parceiroId}" class="btn btn-sm btn-outline-primary w-100"><i class="fas fa-info-circle me-1"></i> Ver Parceiro</a>
                            </div>
                        </div>
                    `;
                }
                
                // Garantir que temos um ID válido para o item
                const itemId = item.id || '';
                console.log('Processando item:', { id: itemId, descricao: item.descricao, tipo: item.tipo, status: item.status });
                
                // Preparar dados do item para evitar undefined
                const itemTitulo = item.descricao || 'Item sem descrição';
                const itemTipo = item.tipo === 'achado' ? 'Item Achado' : 'Item Perdido';
                const itemCategoria = item.categoria && item.categoria.nome_categoria ? item.categoria.nome_categoria : 'Categoria não informada';
                const itemStatus = formatarStatus(item.status || 'pendente');
                const itemLocal = item.localizacao && item.localizacao.endereco ? item.localizacao.endereco : 'Não informado';
                const itemData = formatarData(item.created_at || new Date());
                
                const infoContent = `
                    <div class="info-window" style=" width: 200px; max-width: 300px; height: 250px;">
                        ${fotoHtml}
                        <h5 class="mb-2">${itemTitulo}</h5>
                        <p class="mb-1"><strong>Tipo:</strong> ${itemTipo}</p>
                        <p class="mb-1"><strong>Categoria:</strong> ${itemCategoria}</p>
                        <p class="mb-1"><strong>Status:</strong> ${itemStatus}</p>
                        <p class="mb-1"><strong>Local:</strong> ${itemLocal}</p>
                        <p class="mb-2"><strong>Data:</strong> ${itemData}</p>
                        ${estabelecimentoInfo}
                        <a href="${urlBaseItens}${itemId}?from_map=1" class="btn btn-sm btn-primary w-100">Ver Detalhes</a>
                    </div>
                `;
                
                // Adicionar evento de clique
                marker.addListener('click', () => {
                    infoWindow.setContent(infoContent);
                    infoWindow.open(map, marker);
                });
                
                // Adicionar evento de mouseover para mostrar informações ao passar o mouse
                const tooltipContent = `
                    <div style="padding: 5px; max-width: 200px;">
                        ${item.fotos && item.fotos.length > 0 && item.fotos[0].caminho ? 
                            `<div class="mb-1"><img src="/storage/${item.fotos[0].caminho}" alt="${item.descricao || 'Item'}" style="width: 100%; height: 60px; object-fit: cover; border-radius: 4px;"></div>` : 
                            ''}
                        <strong>${item.descricao || 'Item sem descrição'}</strong><br>
                        ${item.categoria && item.categoria.nome_categoria ? item.categoria.nome_categoria : 'Categoria não informada'}<br>
                        ${formatarStatus(item.status || 'pendente')}
                    </div>
                `;
                
                const tooltip = new google.maps.InfoWindow({
                    content: tooltipContent,
                    disableAutoPan: true,
                    pixelOffset: new google.maps.Size(0, -10)
                });
                
                marker.addListener('mouseover', () => {
                    tooltip.open(map, marker);
                });
                
                marker.addListener('mouseout', () => {
                    tooltip.close();
                });
                
                // Adicionar à lista de marcadores
                markers.push(marker);
                console.log(`Marcador adicionado para item ${item.id} em lat=${lat}, lng=${lng}`);
            } catch (error) {
                console.error(`Erro ao criar marcador para item ${item.id}:`, error);
            }
        });
        
        // Adicionar marcadores de parceiros
        if (!statusFiltro || statusFiltro === 'em_estabelecimento') {
            parceiros.forEach(parceiro => {
                try {
                    if (!parceiro.localizacao || !parceiro.localizacao.latitude || !parceiro.localizacao.longitude) {
                        console.log(`Parceiro ${parceiro.id} (${parceiro.nome_estabelecimento || parceiro.nome_fantasia || 'Sem nome'}) não tem localização válida:`, parceiro.localizacao);
                        return;
                    }
                    
                    // Verificar se as coordenadas são válidas
                    const lat = parseFloat(parceiro.localizacao.latitude);
                    const lng = parseFloat(parceiro.localizacao.longitude);
                    if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) {
                        console.log(`Parceiro ${parceiro.id} (${parceiro.nome_estabelecimento || parceiro.nome_fantasia || 'Sem nome'}) tem coordenadas inválidas: lat=${lat}, lng=${lng}`);
                        return;
                    }
                    
                    const marker = new google.maps.Marker({
                        position: { 
                            lat: parseFloat(parceiro.localizacao.latitude), 
                            lng: parseFloat(parceiro.localizacao.longitude) 
                        },
                        map: map,
                        title: parceiro.nome_estabelecimento || 'Parceiro',
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    });
                    
                    // Conteúdo da janela de informações com logo ou imagem padrão
                    let logoHtml = '';
                    try {
                        if (parceiro.logo) {
                            logoHtml = `<div class="text-center mb-2"><img src="/storage/${parceiro.logo}" alt="Logo ${parceiro.nome_estabelecimento || 'Parceiro'}" class="img-fluid" style="max-height: 60px; max-width: 100%;"></div>`;
                        }
                    } catch (e) {
                        console.error('Erro ao processar logo do parceiro:', e);
                        logoHtml = '';
                    }
                    
                    // Verificar se o parceiro existe e tem os dados necessários
                    const parceiroNome = parceiro.nome_estabelecimento || 'Estabelecimento Parceiro';
                    const parceiroId = parceiro.id || '';
                    console.log('Processando parceiro:', { id: parceiroId, nome: parceiroNome });
                    
                    let endereco = 'Não informado';
                    let bairro = 'Não informado';
                    let cidade = 'Não informada';
                    let estado = '';
                    
                    if (parceiro.localizacao) {
                        endereco = parceiro.localizacao.logradouro || 'Não informado';
                        if (parceiro.localizacao.numero) endereco += ', ' + parceiro.localizacao.numero;
                        bairro = parceiro.localizacao.bairro || 'Não informado';
                        cidade = parceiro.localizacao.cidade || 'Não informada';
                        estado = parceiro.localizacao.estado || '';
                    }
                    
                    const telefone = parceiro.telefone || 'Não informado';
                    
                    const infoContent = `
                        <div class="info-window" style=" max-width: 300px;">
                            ${logoHtml}
                            <h5 class="mb-2">${parceiroNome}</h5>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1 text-primary"></i> <strong>Endereço:</strong> ${endereco}</p>
                            <p class="mb-1"><i class="fas fa-phone me-1 text-primary"></i> <strong>Telefone:</strong> ${telefone}</p>
                            <p class="mb-2"><i class="fas fa-clock me-1 text-primary"></i> <strong>Horário:</strong> ${parceiro.horario_funcionamento || 'Não informado'}</p>
                            <a href="${urlBaseParceiros}${parceiro.id}?from_map=1" class="btn btn-sm btn-primary w-100"><i class="fas fa-info-circle me-1"></i> Ver Detalhes</a>
                        </div>
                    `;
                    
                    // Adicionar evento de clique
                    marker.addListener('click', () => {
                        infoWindow.setContent(infoContent);
                        infoWindow.open(map, marker);
                    });
                    
                    // Adicionar evento de mouseover para mostrar informações ao passar o mouse
                    const tooltipContent = `
                        <div style="padding: 5px; max-width: 200px;">
                            ${parceiro.logo ? `<div class="mb-1"><img src="/storage/${parceiro.logo}" alt="${parceiro.nome_estabelecimento || 'Parceiro'}" style="width: 100%; height: 40px; object-fit: contain; border-radius: 4px;"></div>` : ''}
                            <strong>${parceiro.nome_estabelecimento || 'Parceiro'}</strong><br>
                            Estabelecimento Parceiro
                        </div>
                    `;
                    
                    const tooltip = new google.maps.InfoWindow({
                        content: tooltipContent,
                        disableAutoPan: true,
                        pixelOffset: new google.maps.Size(0, -10)
                    });
                    
                    marker.addListener('mouseover', () => {
                        tooltip.open(map, marker);
                    });
                    
                    marker.addListener('mouseout', () => {
                        tooltip.close();
                    });
                    
                    // Adicionar à lista de marcadores
                    markers.push(marker);
                    console.log(`Marcador adicionado para parceiro ${parceiro.id} em lat=${lat}, lng=${lng}`);
                } catch (error) {
                    console.error(`Erro ao criar marcador para parceiro ${parceiro.id}:`, error);
                }
            });
        }
        
        console.log(`${markers.length} marcadores adicionados ao mapa`);
    }
    
    // Função para limpar todos os marcadores do mapa
    function limparMarcadores() {
        console.log(`Limpando ${markers.length} marcadores...`);
        markers.forEach(marker => marker.setMap(null));
        markers = [];
    }
    
    // Função para formatar status
    function formatarStatus(status) {
        const statusMap = {
            'pendente': 'Pendente',
            'aprovado': 'Aprovado',
            'rejeitado': 'Rejeitado',
            'em_transferencia': 'Em Transferência',
            'em_estabelecimento': 'Em Estabelecimento Parceiro',
            'devolvido': 'Devolvido'
        };
        return statusMap[status] || status;
    }
    
    // Função para formatar data
    function formatarData(dataString) {
        const data = new Date(dataString);
        return data.toLocaleDateString('pt-BR');
    }
    
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Carregar o script do Google Maps
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
        
        // Aplicar filtros
        document.getElementById('aplicarFiltros').addEventListener('click', function(e) {
            e.preventDefault();
            carregarMarcadores();
        });
        
        // Resetar filtros
        document.getElementById('resetarFiltros').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filter-form').reset();
            document.getElementById('tipo_todos').checked = true;
            document.getElementById('status_todos').checked = true;
            carregarMarcadores();
        });
    });
</script>
@endpush
