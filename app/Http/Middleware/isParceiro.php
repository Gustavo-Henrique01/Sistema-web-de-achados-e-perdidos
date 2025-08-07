<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Parceiro;

class isParceiro
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->isParceiro()){
            // Verifica se o parceiro existe e está aprovado
            $parceiro = auth()->user()->parceiro;
            
            if (!$parceiro || $parceiro->status !== Parceiro::STATUS_APROVADO) {
                return redirect()->route('parceiro.aguardando-aprovacao');
            }

            // Verifica se o parceiro está ativo
            if (!$parceiro->ativo) {
                return redirect()->route('parceiro.inativo');
            }
            
            return $next($request);
        } 

        abort(403, 'Acesso negado. Apenas parceiros podem acessar esta área.');
    }
}