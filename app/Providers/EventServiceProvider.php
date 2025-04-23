<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ChatifyMessageSent;
use App\Listeners\SendChatifyMessageNotification;
use App\Events\ItemRejeitado;
use App\Listeners\SendItemRejeitadoNotification;
use App\Events\ItemParceiroStatusChanged;
use App\Listeners\SendItemParceiroStatusNotification;
use App\Events\ItemDevolvido;
use App\Listeners\SendItemDevolvidoNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // ... existing listeners ...
        
        ChatifyMessageSent::class => [
            SendChatifyMessageNotification::class,
        ],
        
        ItemRejeitado::class => [
            SendItemRejeitadoNotification::class,
        ],
        
        ItemParceiroStatusChanged::class => [
            SendItemParceiroStatusNotification::class,
        ],
        
        ItemDevolvido::class => [
            SendItemDevolvidoNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
} 