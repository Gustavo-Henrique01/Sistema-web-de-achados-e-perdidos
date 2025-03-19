<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class isUser
{
    public function handle(Request $request, Closure $next): Response
    {
            if(auth()->check() && auth()->user()->isUser()){

                return $next($request);
            } 

            abort(403, 'Acesso negado. Apenas privilegiados comuns podem acessar esta área.');
    } 
    
}
