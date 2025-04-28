<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = auth()->user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Obtém as notificações recentes para o dropdown
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentNotifications()
    {
        // Busca as 5 notificações mais recentes
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'read_at' => $notification->read_at,
                    'time_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
                    'data' => $notification->data
                ];
            });
        
        return response()->json([
            'notifications' => $notifications
        ]);
    }
    
    /**
     * Exclui uma notificação específica
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $id ID da notificação
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(Request $request, $id)
    {
        try {
            // Log para debug
            \Log::info('Tentando excluir notificação', ['id' => $id, 'user_id' => auth()->id()]);
            
            // Busca a notificação do usuário atual
            $notification = auth()->user()->notifications()->where('id', $id)->first();
            
            if (!$notification) {
                \Log::warning('Notificação não encontrada', ['id' => $id, 'user_id' => auth()->id()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Notificação não encontrada'
                ], 404);
            }
            
            // Exclui a notificação
            $notification->delete();
            \Log::info('Notificação excluída com sucesso', ['id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notificação excluída com sucesso'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir notificação', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir notificação: ' . $e->getMessage()
            ], 500);
        }
    }
}