<div class="favorite-list-item">
    @if($usuario)
        <div data-id="{{ $usuario->id }}" data-action="0" class="avatar av-m"
            style="background-image: url('{{ Chatify::getUserWithAvatar($usuario)->avatar }}');">
        </div>
        <p>{{ strlen($usuario->nome) > 5 ? substr($usuario->nome,0,6).'..' : $usuario->nome}}</p>
    @endif
</div>
