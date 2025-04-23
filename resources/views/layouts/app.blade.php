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

        .notification-count {
            position: absolute;        
            top: -5px;        
            right: -5px;                        
            background-color: red;        
            color: white;        
            border-radius: 50%;        
            width: 18px;        
            height: 18px;        
            display: flex;        
            justify-content: center;        
            align-items: center;    
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
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                                <i class="fas fa-bell me-1"></i>
                                <span class="notification-badge"></span>
                            </a>
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

            // Função para atualizar o contador de notificações
            window.updateNotificationCount = function() {
                fetch('/notificacoes/nao-lidas')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.notification-badge');
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count;
                                badge.style.display = 'block';
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                    });
            };

            // Função para marcar uma notificação como lida
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

            // Escuta eventos de notificação
            channel.bind('ItemRejeitado', function(data) {
                updateNotificationCount();
            });

            channel.bind('ItemDevolvido', function(data) {
                updateNotificationCount();
            });

            channel.bind('ItemParceiroStatusChanged', function(data) {
                updateNotificationCount();
            });

            channel.bind('ChatifyMessageSent', function(data) {
                updateNotificationCount();
            });
        </script>
    @endauth
</body>
</html>