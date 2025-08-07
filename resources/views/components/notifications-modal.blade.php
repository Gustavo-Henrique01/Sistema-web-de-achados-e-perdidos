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
                        <div class="list-group-item notification-item {{ $notification->read_at ? 'read' : '' }}"
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
                                @else
                                    <div class="me-3 rounded d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-bell text-primary"></i>
                                    </div>
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
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div>
                                            @if(isset($notification->data['requires_confirmation']) && $notification->data['requires_confirmation'])
                                                <div class="d-flex gap-2">
                                                    @if(isset($notification->data['item_id']))
                                                        <!-- Botões com JavaScript puro -->
                                                        <button type="button" class="btn btn-sm btn-primary" 
                                                                onclick="goToUrl('/item/{{ $notification->data['item_id'] }}/confirmar-devolucao'); $('#notificationsModal').modal('hide');">
                                                            <i class="fas fa-eye me-1"></i>Ver Detalhes
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                onclick="goToUrl('/item/{{ $notification->data['item_id'] }}/confirmar-devolucao/confirm'); $('#notificationsModal').modal('hide');">
                                                            <i class="fas fa-check me-1"></i>Confirmar
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="goToUrl('/item/{{ $notification->data['item_id'] }}/recusar-devolucao/reject'); $('#notificationsModal').modal('hide');">
                                                            <i class="fas fa-times me-1"></i>Recusar
                                                        </button>
                                                    @endif
                                                </div>
                                            @elseif(!$notification->read_at)
                                                <button class="btn btn-sm btn-outline-primary mark-as-read" 
                                                        data-notification-id="{{ $notification->id }}"
                                                        onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check me-1"></i>Marcar como lida
                                                </button>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-notification" 
                                                data-notification-id="{{ $notification->id }}"
                                                onclick="event.stopPropagation(); deleteNotification('{{ $notification->id }}')">
                                            <i class="fas fa-trash-alt"></i> Excluir
                                        </button>
                                    </div>
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