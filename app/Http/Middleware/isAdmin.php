<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Usuario;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            if(auth()->check() && auth()->user()->isAdmin()){

                return $next($request);
            } 

            abort(403, 'Acesso negado. Apenas privilegiados comuns podem acessar esta área.');
    } 
    
}
