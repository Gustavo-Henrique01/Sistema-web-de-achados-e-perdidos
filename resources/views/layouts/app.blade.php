<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Achados e Perdidos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Maps CSS -->
    <style>
        .map-container {
            width: 100%;
            height: 100%;
            position: relative;
        }
  
    
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Garante que o body ocupe toda a altura da tela */
            margin: 0;
            background-color: #f8f9fa;
        }

        .main-content {
            flex: 1; /* Faz o conteúdo principal ocupar o espaço restante */
        }

        footer {
            background-color: white;
            padding: 1rem 0;
            margin-top: auto; /* Empurra o footer para baixo */
        }
        .notification-btn {
            position: relative;
        }

        .notification-badge {
            position: absolute;        
            top: -5px;        
            right: -5px;                        
            background-color: #e41e3f;        
            color: white;        
            border-radius: 50%;        
            min-width: 18px;        
            height: 18px;        
            display: flex;        
            justify-content: center;        
            align-items: center;
            font-size: 11px;
            font-weight: bold;
            padding: 0 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        .notification-dropdown.show {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .notification-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .notification-actions {
            display: flex;
            gap: 10px;
        }
        
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: flex-start;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #f7f7f7;
        }
        
        .notification-item.unread {
            background-color: #e7f3ff;
        }
        
        .notification-item.unread:hover {
            background-color: #d9ebff;
        }
        
        .notification-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .notification-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .notification-content {
            flex-grow: 1;
        }
        
        .notification-message {
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        
        .notification-time {
            font-size: 12px;
            color: #65676B;
        }
        
        .notification-footer {
            padding: 8px 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        
        .notification-footer a {
            color: #1877F2;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .notification-dot {
            width: 8px;
            height: 8px;
            background-color: #1877F2;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }


        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: 600;
        }
        .nav-link {
            font-weight: 500;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .btn {
            font-weight: 500;
        }
        .badge {
            font-weight: 500;
        }
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('paginaInicial') }}">
                <i class="fas fa-search-location text-primary me-2"></i>
                Achados e Perdidos
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('form.login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('registrar') }}">
                                <i class="fas fa-user-plus me-1"></i>Registrar
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('usuario.home') }}">
                                <i class="fas fa-home me-1"></i>Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('usuario.cadastrar-item') }}">
                                <i class="fas fa-plus-circle me-1"></i>Cadastrar Item
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('perfil-usuario') }}">
                                <i class="fas fa-user me-1"></i>Meu Perfil
                            </a>
                        </li>
                        <li class="nav-item notification-btn">
                            <a class="nav-link" href="#" id="notificationDropdownToggle">
                                <i class="fas fa-bell me-1"></i>
                                <span class="notification-badge"></span>
                            </a>
                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h5 class="notification-title">Notificações</h5>
                                    <div class="notification-actions">
                                        <a href="#" class="mark-all-read" onclick="markAllAsRead()" title="Marcar todas como lidas">
                                            <i class="fas fa-check-double"></i>
                                        </a>
                                        <a href="#" id="closeNotificationDropdown" title="Fechar">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="notification-list" id="notificationsList">
                                    <!-- As notificações serão carregadas via JavaScript -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-footer">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#notificationsModal">Ver todas as notificações</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">
                                    <i class="fas fa-sign-out-alt me-1"></i>Sair
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-4 ">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; 2024 Achados e Perdidos. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3">Termos de Uso</a>
                    <a href="#" class="text-muted">Política de Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Pusher JS -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <!-- Google Maps será carregado nas páginas específicas -->
    <!-- Scripts Adicionais -->
    @stack('scripts')

    @auth
        <x-notifications-modal />
        <script>
            // Inicializa o Pusher
            const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true,
                forceTLS: true,
                authEndpoint: '/pusher/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }
            });

            // Inscreve no canal privado do usuário
            const channel = pusher.subscribe('private-user.{{ auth()->id() }}');

            // Função para atualizar o contador de notificações e carregar notificações recentes
            window.updateNotificationCount = function() {
                fetch('/notificacoes/nao-lidas')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.notification-badge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count > 99 ? '99+' : data.count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                        
                        // Carrega as notificações recentes para o dropdown
                        loadRecentNotifications();
                    });
            };
            
            // Função para carregar notificações recentes no dropdown
            window.loadRecentNotifications = function() {
                fetch('/notificacoes/recentes')
                    .then(response => response.json())
                    .then(data => {
                        const notificationsList = document.getElementById('notificationsList');
                        
                        if (!notificationsList) return;
                        
                        if (data.notifications && data.notifications.length > 0) {
                            let html = '';
                            
                            data.notifications.forEach(notification => {
                                const isUnread = !notification.read_at;
                                const notificationData = notification.data;
                                
                                let avatarHtml = '';
                                if (notificationData.item_image) {
                                    avatarHtml = `<img src="${notificationData.item_image}" alt="Imagem do item" class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;">`;
                                } else if (notificationData.sender_image) {
                                    avatarHtml = `<img src="${notificationData.sender_image}" alt="Imagem do remetente" class="me-3 rounded" style="width: 50px; height: 50px; object-fit: cover;">`;
                                } else {
                                    avatarHtml = `<div class="me-3 rounded d-flex align-items-center justify-content-center bg-light" style="width: 50px; height: 50px;"><i class="fas fa-bell text-primary"></i></div>`;
                                }
                                
                                html += `
                                    <div class="list-group-item notification-item ${isUnread ? 'unread' : ''}" 
                                         data-notification-id="${notification.id}"
                                         onclick="handleNotificationClick(event, '${notification.id}')">
                                        <div class="d-flex align-items-start">
                                            ${avatarHtml}
                                            <div class="flex-grow-1">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">${notificationData.message}</h6>
                                                    <small class="text-muted">${notification.time_ago}</small>
                                                </div>
                                                ${notificationData.item_name ? `<p class="mb-1">Item: ${notificationData.item_name}</p>` : ''}
                                                ${notificationData.item_category ? `<p class="mb-1"><span class="badge bg-primary">${notificationData.item_category}</span></p>` : ''}
                                                ${notificationData.sender_name ? `<p class="mb-1">De: ${notificationData.sender_name}</p>` : ''}
                                                
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <div>
                                                        ${isUnread ? `
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="event.stopPropagation(); markAsRead('${notification.id}')">
                                                                <i class="fas fa-check me-1"></i>Marcar como lida
                                                            </button>` : ''}
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="event.stopPropagation(); deleteNotification('${notification.id}')">
                                                        <i class="fas fa-trash-alt me-1"></i>Excluir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            notificationsList.innerHTML = html;
                        } else {
                            notificationsList.innerHTML = `
                                <div class="list-group-item text-center py-3">
                                    <p class="text-muted mb-0">Nenhuma notificação</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar notificações:', error);
                    });
            };

            // Função para navegar para uma URL (sem usar links normais)
            window.goToUrl = function(url) {
                window.location.href = url;
            };
            
            // Função para lidar com cliques em notificações
            window.handleNotificationClick = function(event, notificationId) {
                // Verifica se o clique foi em um botão de ação
                if (event.target.closest('.notification-action-btn') || event.target.closest('button')) {
                    // Se for um botão de ação, não faz nada (deixa o link funcionar normalmente)
                    return;
                }
                
                // Impede a propagação do evento para não fechar o dropdown
                event.stopPropagation();
                
                // Marca a notificação como lida
                markAsRead(notificationId);
                
                // Redireciona para a página da notificação (se aplicável)
                const notificationElement = event.currentTarget;
                if (notificationElement.dataset.url) {
                    window.location.href = notificationElement.dataset.url;
                }
            };
            
            // Função para excluir uma notificação
            window.deleteNotification = function(notificationId) {
                // Confirma se o usuário realmente deseja excluir a notificação
                if (!confirm('Tem certeza que deseja excluir esta notificação?')) {
                    return;
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                fetch(`/notificacoes/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove a notificação da lista no dropdown
                        const notificationItem = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.remove();
                            
                            // Verifica se ainda existem notificações
                            const notificationItems = document.querySelectorAll('.notification-item');
                            if (notificationItems.length === 0) {
                                const notificationsList = document.getElementById('notificationsList');
                                if (notificationsList) {
                                    notificationsList.innerHTML = `
                                        <div class="text-center py-4">
                                            <p class="text-muted mb-0">Nenhuma notificação</p>
                                        </div>
                                    `;
                                }
                            }
                        }
                        
                        // Também remove do modal se estiver aberto
                        const modalNotificationItem = document.querySelector(`#notificationsModal .notification-item[data-notification-id="${notificationId}"]`);
                        if (modalNotificationItem) {
                            modalNotificationItem.remove();
                        }
                        
                        // Atualiza o contador de notificações
                        updateNotificationCount();
                        
                        // Mostra uma mensagem de sucesso
                        showNotificationToast('Notificação excluída com sucesso');
                    } else {
                        console.error('Erro ao excluir notificação:', data.message);
                        showNotificationToast('Erro ao excluir notificação', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro ao excluir notificação:', error);
                    showNotificationToast('Erro ao excluir notificação', 'error');
                });
            };
            
            // Função para marcar uma notificação como lida
            window.deleteNotification = function(notificationId) {
                if (!confirm('Tem certeza que deseja excluir esta notificação?')) {
                    return;
                }
                
                // Obter o token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                console.log('Excluindo notificação:', notificationId);
                console.log('CSRF Token:', csrfToken);
                
                // Criar um FormData para enviar o token CSRF
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', csrfToken);
                
                fetch(`/notificacoes/${notificationId}`, {
                    method: 'POST', // Usar POST com _method=DELETE para compatibilidade
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove a notificação da interface
                        const notificationElement = document.querySelector(`.notification-item[data-notification-id="${notificationId}"]`);
                        if (notificationElement) {
                            notificationElement.remove();
                        }
                        
                        // Atualiza o contador de notificações
                        updateNotificationCount();
                        
                        // Verifica se não há mais notificações
                        const notificationsList = document.getElementById('notificationsList');
                        if (notificationsList && notificationsList.children.length === 0) {
                            notificationsList.innerHTML = `
                                <div class="list-group-item text-center py-3" id="noNotifications">
                                    <p class="mb-0 text-muted">Nenhuma notificação</p>
                                </div>
                            `;
                        }
                        
                        // Exibe mensagem de sucesso
                        showNotificationToast('Notificação excluída com sucesso', 'success');
                    } else {
                        // Exibe mensagem de erro
                        showNotificationToast('Erro ao excluir notificação', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Erro ao excluir notificação:', error);
                    showNotificationToast('Erro ao excluir notificação', 'danger');
                });
            };
            
            window.markAsRead = function(notificationId) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                fetch(`/notificacoes/${notificationId}/marcar-lida`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove a notificação da lista
                        const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.remove();
                            
                            // Verifica se ainda existem notificações
                            const notificationsList = document.getElementById('notificationsList');
                            const remainingNotifications = notificationsList.querySelectorAll('.notification-item');
                            
                            if (remainingNotifications.length === 0) {
                                // Se não houver mais notificações, mostra a mensagem de "Nenhuma notificação"
                                notificationsList.innerHTML = `
                                    <div class="list-group-item text-center py-3" id="noNotifications">
                                        <p class="mb-0 text-muted">Nenhuma notificação</p>
                                    </div>
                                `;
                            }
                        }
                        updateNotificationCount();
                    }
                })
                .catch(error => {
                    console.error('Erro ao marcar notificação como lida:', error);
                });
            };

            // Função para marcar todas as notificações como lidas
            window.markAllAsRead = function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                fetch('/notificacoes/marcar-todas-lidas', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove todas as notificações da lista
                        const notificationsList = document.getElementById('notificationsList');
                        notificationsList.innerHTML = `
                            <div class="list-group-item text-center py-3" id="noNotifications">
                                <p class="mb-0 text-muted">Nenhuma notificação</p>
                            </div>
                        `;
                        updateNotificationCount();
                    }
                })
                .catch(error => {
                    console.error('Erro ao marcar todas as notificações como lidas:', error);
                });
            };

            // Adiciona event listeners para os botões de marcação como lida
            document.addEventListener('DOMContentLoaded', function() {
                // Event listener para marcar uma notificação como lida
                document.querySelectorAll('.mark-as-read').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const notificationId = this.dataset.notificationId;
                        markAsRead(notificationId);
                    });
                });

                // Event listener para marcar todas as notificações como lidas
                const markAllButton = document.querySelector('.mark-all-read');
                if (markAllButton) {
                    markAllButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        markAllAsRead();
                    });
                }

                // Atualiza o contador inicialmente
                updateNotificationCount();
            });

            // Configuração do dropdown de notificações
            document.addEventListener('DOMContentLoaded', function() {
                const notificationDropdownToggle = document.getElementById('notificationDropdownToggle');
                const notificationDropdown = document.getElementById('notificationDropdown');
                const closeNotificationDropdown = document.getElementById('closeNotificationDropdown');
                
                if (notificationDropdownToggle && notificationDropdown) {
                    // Abre o dropdown ao clicar no ícone de notificação
                    notificationDropdownToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        notificationDropdown.classList.toggle('show');
                        
                        // Carrega as notificações recentes quando o dropdown é aberto
                        if (notificationDropdown.classList.contains('show')) {
                            loadRecentNotifications();
                        }
                    });
                    
                    // Fecha o dropdown ao clicar no botão de fechar
                    if (closeNotificationDropdown) {
                        closeNotificationDropdown.addEventListener('click', function(e) {
                            e.preventDefault();
                            notificationDropdown.classList.remove('show');
                        });
                    }
                    
                    // Fecha o dropdown ao clicar fora dele
                    document.addEventListener('click', function(e) {
                        if (!notificationDropdownToggle.contains(e.target) && 
                            !notificationDropdown.contains(e.target)) {
                            notificationDropdown.classList.remove('show');
                        }
                    });
                }
                
                // Carrega as notificações inicialmente
                updateNotificationCount();
            });
            
            // Escuta eventos de notificação
            channel.bind('ItemRejeitado', function(data) {
                updateNotificationCount();
                showNotificationToast(data.message || 'Um item foi rejeitado');
            });

            channel.bind('ItemDevolvido', function(data) {
                updateNotificationCount();
                showNotificationToast(data.message || 'Um item foi marcado como devolvido');
            });

            channel.bind('ItemParceiroStatusChanged', function(data) {
                updateNotificationCount();
                showNotificationToast(data.message || 'Status de um item foi alterado');
            });

            channel.bind('ChatifyMessageSent', function(data) {
                updateNotificationCount();
                showNotificationToast(data.message || 'Nova mensagem recebida');
            });
            
            // Função para mostrar um toast de notificação
            function showNotificationToast(message, type = 'info') {
                // Verifica se o elemento de toast já existe
                let toastContainer = document.getElementById('notification-toast-container');
                
                // Se não existir, cria o container
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'notification-toast-container';
                    toastContainer.style.position = 'fixed';
                    toastContainer.style.top = '20px';
                    toastContainer.style.right = '20px';
                    toastContainer.style.zIndex = '9999';
                    document.body.appendChild(toastContainer);
                }
                
                // Cria o toast
                const toast = document.createElement('div');
                toast.className = 'toast show';
                toast.style.backgroundColor = 'white';
                toast.style.minWidth = '280px';
                toast.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.15)';
                toast.style.marginBottom = '10px';
                toast.style.borderRadius = '8px';
                toast.style.overflow = 'hidden';
                toast.style.border = 'none';
                
                // Define a cor do cabeçalho com base no tipo
                let headerColor = '#4e73df'; // Azul padrão para info
                let headerIcon = 'fa-bell';
                let headerText = 'Notificação';
                
                if (type === 'success') {
                    headerColor = '#1cc88a'; // Verde para sucesso
                    headerIcon = 'fa-check-circle';
                    headerText = 'Sucesso';
                } else if (type === 'error') {
                    headerColor = '#e74a3b'; // Vermelho para erro
                    headerIcon = 'fa-exclamation-circle';
                    headerText = 'Erro';
                } else if (type === 'warning') {
                    headerColor = '#f6c23e'; // Amarelo para aviso
                    headerIcon = 'fa-exclamation-triangle';
                    headerText = 'Aviso';
                }
                
                // Conteúdo do toast
                toast.innerHTML = `
                    <div class="toast-header" style="background-color: ${headerColor}; color: white;">
                        <strong class="me-auto"><i class="fas ${headerIcon} me-2"></i>${headerText}</strong>
                        <small>Agora</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                `;
                
                // Adiciona o toast ao container
                toastContainer.appendChild(toast);
                
                // Adiciona evento para fechar o toast
                const closeButton = toast.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        toast.remove();
                    });
                }
                
                // Remove o toast após 5 segundos
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }
        </script>
    @endauth
</body>
</html>