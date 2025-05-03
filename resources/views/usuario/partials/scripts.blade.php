<!-- Scripts para a galeria de fotos e funcionalidades do perfil -->
<script>
    // Array para armazenar as fotos de cada item
    const itemGalleries = {};
    
    // Inicializa os arrays de fotos para cada item que tem múltiplas fotos
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($user->itens as $item)
            @if($item->fotos && $item->fotos->count() > 1)
                itemGalleries[{{ $item->id }}] = [
                    @foreach($item->fotos as $foto)
                        "{{ asset('storage/' . $foto->caminho) }}",
                    @endforeach
                ];
            @endif
        @endforeach
        
        // Inicializa os filtros
        initStatusFilter();
    });
    
    // Função para filtrar itens por status
    function initStatusFilter() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const itemContainers = document.querySelectorAll('.item-container');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove a classe active de todos os botões
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adiciona a classe active ao botão clicado
                this.classList.add('active');
                
                const selectedStatus = this.getAttribute('data-status');
                
                // Filtra os itens com base no status selecionado
                itemContainers.forEach(container => {
                    if (selectedStatus === 'todos' || container.getAttribute('data-status') === selectedStatus) {
                        container.style.display = '';
                    } else {
                        container.style.display = 'none';
                    }
                });
                
                // Verifica se há itens visíveis para o status atual
                checkNoVisibleItems(selectedStatus);
            });
        });
    }
    
    // Verifica se há itens visíveis para o status atual e exibe mensagem se não houver
    function checkNoVisibleItems(status) {
        const visibleItems = document.querySelectorAll(`.item-container[style="display: "]`);
        const noItemsContainer = document.querySelector('.no-visible-items');
        
        if (visibleItems.length === 0) {
            // Se não existir, cria o container para a mensagem
            if (!noItemsContainer) {
                const itemsRow = document.querySelector('.row');
                const noVisibleItemsDiv = document.createElement('div');
                noVisibleItemsDiv.className = 'no-visible-items no-items w-100';
                noVisibleItemsDiv.innerHTML = `
                    <i class="fas fa-filter d-block"></i>
                    <p>Nenhum item ${status !== 'todos' ? `com status "${status}"` : ''} encontrado.</p>
                `;
                itemsRow.appendChild(noVisibleItemsDiv);
            } else {
                noItemsContainer.style.display = '';
                const statusText = status !== 'todos' ? `com status "${status}"` : '';
                noItemsContainer.querySelector('p').innerText = `Nenhum item ${statusText} encontrado.`;
            }
        } else if (noItemsContainer) {
            noItemsContainer.style.display = 'none';
        }
    }
    
    // Função para trocar a foto principal ao clicar em uma miniatura
    function changeMainPhoto(itemId, photoUrl, thumbnailElement) {
        // Atualiza a foto principal
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (mainPhotoImg) {
            mainPhotoImg.src = photoUrl;
        }
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = thumbnailElement.parentElement.querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnailElement.classList.add('active');
    }
    
    // Função para navegar para a foto anterior
    function prevPhoto(itemId) {
        if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
        
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (!mainPhotoImg) return;
        
        // Encontra o índice atual da foto
        const currentPhotoUrl = mainPhotoImg.src;
        const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
        
        // Calcula o índice anterior (com loop circular)
        const prevIndex = (currentIndex - 1 + itemGalleries[itemId].length) % itemGalleries[itemId].length;
        
        // Atualiza a foto principal
        mainPhotoImg.src = itemGalleries[itemId][prevIndex];
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnails[prevIndex].classList.add('active');
    }
    
    // Função para navegar para a próxima foto
    function nextPhoto(itemId) {
        if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
        
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (!mainPhotoImg) return;
        
        // Encontra o índice atual da foto
        const currentPhotoUrl = mainPhotoImg.src;
        const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
        
        // Calcula o próximo índice (com loop circular)
        const nextIndex = (currentIndex + 1) % itemGalleries[itemId].length;
        
        // Atualiza a foto principal
        mainPhotoImg.src = itemGalleries[itemId][nextIndex];
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnails[nextIndex].classList.add('active');
    }
</script>

<!-- Scripts para mapas e autocomplete -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os mapas para os modais de parceiros
    @foreach($user->itens as $item)
        @if($item->status === 'aprovado' && !$item->parceiro_id)
            // Inicializa o mapa para cada item
            const map{{ $item->id }} = new google.maps.Map(document.getElementById('map-{{ $item->id }}'), {
                center: { lat: -20.4697, lng: -54.6201 },
                zoom: 13
            });

            const markers{{ $item->id }} = {};
            
            // Adiciona marcadores para cada parceiro
            @foreach($parceiros as $parceiro)
                markers{{ $item->id }}[{{ $parceiro->id }}] = new google.maps.Marker({
                    position: { 
                        lat: {{ $parceiro->localizacao->latitude }}, 
                        lng: {{ $parceiro->localizacao->longitude }}
                    },
                    map: map{{ $item->id }},
                    title: '{{ $parceiro->nome_estabelecimento }}'
                });

                // Adiciona info window para cada marcador
                const infoWindow{{ $item->id }}{{ $parceiro->id }} = new google.maps.InfoWindow({
                    content: `
                        <strong>{{ $parceiro->nome_estabelecimento }}</strong><br>
                        {{ $parceiro->localizacao->endereco }}<br>
                        <small>{{ $parceiro->horario_funcionamento }}</small>
                    `
                });

                markers{{ $item->id }}[{{ $parceiro->id }}].addListener('click', () => {
                    infoWindow{{ $item->id }}{{ $parceiro->id }}.open(map{{ $item->id }}, markers{{ $item->id }}[{{ $parceiro->id }}]);
                });
            @endforeach

            // Atualiza o mapa quando um parceiro é selecionado
            document.getElementById('parceiro_id-{{ $item->id }}').addEventListener('change', function(e) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                if (selectedOption.value) {
                    const lat = parseFloat(selectedOption.dataset.lat);
                    const lng = parseFloat(selectedOption.dataset.lng);
                    map{{ $item->id }}.setCenter({ lat, lng });
                    map{{ $item->id }}.setZoom(15);
                    markers{{ $item->id }}[selectedOption.value].setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => {
                        markers{{ $item->id }}[selectedOption.value].setAnimation(null);
                    }, 1500);
                }
            });
        @endif
    @endforeach
    
    // Inicializa o autocomplete para os campos de email
    $('.email-autocomplete').each(function() {
        // Configura o autocomplete
        var autocompleteWidget = $(this).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('usuarios.search') }}",
                    method: 'GET',
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        if (data.length === 0) {
                            // Se não houver resultados, mostra uma mensagem
                            response([{ label: 'Nenhum usuário encontrado', value: '' }]);
                        } else {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name + ' (' + item.email + ')',
                                    value: item.email
                                };
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Autocomplete error:', status, error);
                        response([]);
                    }
                });
            },
            minLength: 2,
            delay: 300,
            position: { my: "left top", at: "left bottom", collision: "flip" },
            appendTo: $(this).closest('.modal-content'),
            open: function(event, ui) {
                // Ajusta o z-index do dropdown para garantir que ele fique acima do modal
                $('.ui-autocomplete').css('z-index', 9999);
            },
            select: function(event, ui) {
                if (ui.item.value) { // Verifica se não é o item "Nenhum usuário encontrado"
                    $(this).val(ui.item.value);
                }
                return false;
            }
        }).on('focus', function() {
            // Força a exibição do menu se houver pelo menos 2 caracteres
            if ($(this).val().length >= 2) {
                $(this).autocomplete('search');
            }
        });
        
        // Força o widget a usar o contêiner do modal como referência para posicionamento
        autocompleteWidget.autocomplete('widget').css('z-index', 10000);
    });
    
    // Inicializa os controles para os modais de devolução
    @foreach($user->itens as $item)
        @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
            // Controle de exibição dos campos baseado no método de devolução
            const metodoContatoDireto{{ $item->id }} = document.getElementById('metodo-contato-direto-{{ $item->id }}');
            const metodoEncontrado{{ $item->id }} = document.getElementById('metodo-encontrado-{{ $item->id }}');
            const metodoParceiro{{ $item->id }} = document.getElementById('metodo-parceiro-{{ $item->id }}');
            
            const parceiroCampos{{ $item->id }} = document.getElementById('parceiro-campos-{{ $item->id }}');
            const parceiroId{{ $item->id }} = document.getElementById('parceiro_id-{{ $item->id }}');
            
            // Formulário de devolução
            const formDevolvido{{ $item->id }} = document.getElementById('form-devolvido-{{ $item->id }}');
            const btnConfirmarDevolucao{{ $item->id }} = document.getElementById('btn-confirmar-devolucao-{{ $item->id }}');
            
            if (metodoContatoDireto{{ $item->id }} && metodoEncontrado{{ $item->id }} && metodoParceiro{{ $item->id }}) {
                // Evento para método contato direto
                metodoContatoDireto{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        parceiroCampos{{ $item->id }}.classList.add('d-none');
                        parceiroId{{ $item->id }}.removeAttribute('required');
                    }
                });
                
                // Evento para método item encontrado
                metodoEncontrado{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        parceiroCampos{{ $item->id }}.classList.add('d-none');
                        parceiroId{{ $item->id }}.removeAttribute('required');
                    }
                });
                
                // Evento para método parceiro
                metodoParceiro{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        parceiroCampos{{ $item->id }}.classList.remove('d-none');
                        parceiroId{{ $item->id }}.setAttribute('required', 'required');
                    }
                });
                
                // Adiciona evento de submissão ao formulário
                if (formDevolvido{{ $item->id }}) {
                    formDevolvido{{ $item->id }}.addEventListener('submit', function(e) {
                        // Desabilita o botão para evitar múltiplos envios
                        if (btnConfirmarDevolucao{{ $item->id }}) {
                            btnConfirmarDevolucao{{ $item->id }}.disabled = true;
                            btnConfirmarDevolucao{{ $item->id }}.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processando...';
                        }
                    });
                }
                
                // Configurar autocomplete para busca de usuários
                const usuarioEmail{{ $item->id }} = document.getElementById('usuario_email');
                const usuarioId{{ $item->id }} = document.getElementById('usuario_id');
                const usuarioSugestoes{{ $item->id }} = document.getElementById('usuarioSugestoes');
                
                if (usuarioEmail{{ $item->id }}) {
                    usuarioEmail{{ $item->id }}.addEventListener('input', function() {
                        const query = this.value.trim();
                        if (query.length < 3) {
                            usuarioSugestoes{{ $item->id }}.classList.add('d-none');
                            return;
                        }
                        
                        // Usar a URL correta com o prefixo da aplicau00e7u00e3o
                        const baseUrl = window.location.origin;
                        fetch(`${baseUrl}/api/usuarios/buscar?q=${query}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                usuarioSugestoes{{ $item->id }}.innerHTML = '';
                                if (data.length > 0) {
                                    usuarioSugestoes{{ $item->id }}.classList.remove('d-none');
                                    data.forEach(usuario => {
                                        const item = document.createElement('a');
                                        item.href = '#';
                                        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                                        
                                        // Criando elementos para nome e email com estilo melhorado
                                        const userInfo = document.createElement('div');
                                        const userName = document.createElement('strong');
                                        userName.textContent = usuario.name;
                                        const userEmail = document.createElement('small');
                                        userEmail.className = 'text-muted ms-2';
                                        userEmail.textContent = usuario.email;
                                        
                                        userInfo.appendChild(userName);
                                        userInfo.appendChild(document.createTextNode(' '));
                                        userInfo.appendChild(userEmail);
                                        
                                        item.appendChild(userInfo);
                                        
                                        item.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            usuarioEmail{{ $item->id }}.value = usuario.email;
                                            usuarioId{{ $item->id }}.value = usuario.id;
                                            usuarioSugestoes{{ $item->id }}.classList.add('d-none');
                                        });
                                        usuarioSugestoes{{ $item->id }}.appendChild(item);
                                    });
                                } else {
                                    usuarioSugestoes{{ $item->id }}.classList.add('d-none');
                                }
                            })
                            .catch(error => {
                                console.error('Erro ao buscar usuários:', error);
                            });
                    });
                    
                    // Fechar sugestões ao clicar fora
                    document.addEventListener('click', function(e) {
                        if (e.target !== usuarioEmail{{ $item->id }} && e.target !== usuarioSugestoes{{ $item->id }}) {
                            usuarioSugestoes{{ $item->id }}.classList.add('d-none');
                        }
                    });
                }
            }
        @endif
    @endforeach
});
</script>
