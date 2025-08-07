<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationsModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->take(10)->get();
        Log::info('Notificações carregadas:', ['count' => $notifications->count(), 'notifications' => $notifications->toArray()]);
        return view('components.notifications-modal', [
            'notifications' => $notifications
        ]);
    }
} 