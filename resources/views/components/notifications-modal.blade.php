<!-- Modal de Notificações -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="notificationsModalLabel">Notificações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush" id="notificationsList">
                    @forelse($notifications as $notification)
                        <div class="list-group-item list-group-item-action notification-item {{ $notification->read_at ? 'read' : '' }}"
                             data-notification-id="{{ $notification->id }}">
                            <div class="d-flex align-items-start">
                                @if(isset($notification->data['item_image']))
                                    <img src="{{ $notification->data['item_image'] }}" 
                                         alt="Imagem do item" 
                                         class="me-3 rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @elseif(isset($notification->data['sender_image']))
                                    <img src="{{ $notification->data['sender_image'] }}" 
                                         alt="Imagem do remetente" 
                                         class="me-3 rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <div class="flex-grow-1">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $notification->data['message'] }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if(isset($notification->data['item_name']))
                                        <p class="mb-1">Item: {{ $notification->data['item_name'] }}</p>
                                    @endif
                                    @if(isset($notification->data['item_category']))
                                        <p class="mb-1">
                                            <span class="badge bg-primary">{{ $notification->data['item_category'] }}</span>
                                        </p>
                                    @endif
                                    @if(isset($notification->data['sender_name']))
                                        <p class="mb-1">De: {{ $notification->data['sender_name'] }}</p>
                                    @endif
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm btn-link mark-as-read" 
                                                data-notification-id="{{ $notification->id }}"
                                                onclick="markAsRead({{ $notification->id }})">
                                            Marcar como lida
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center py-3" id="noNotifications">
                            <p class="mb-0 text-muted">Nenhuma notificação</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer border-0">
                @if($notifications->count() > 0)
                    <button class="btn btn-link mark-all-read" onclick="markAllAsRead()">
                        Marcar todas como lidas
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .notification-item {
        transition: background-color 0.2s ease;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-item.read {
        background-color: #f8f9fa;
        opacity: 0.7;
    }
    .notification-item.read h6 {
        font-weight: normal;
    }
    .badge {
        font-size: 0.8em;
    }
</style>

<script>
    function markAllAsRead() {
        fetch('/notificacoes/marcar-todas-lidas', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
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
                // Atualiza o contador de notificações
                updateNotificationCount();
            }
        });
    }
</script> 
</script> 