<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Localizacao;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function mostrarMapa()
    {
        if (auth()->check()) {
            $itens = Item::whereHas('localizacao', function ($query) {
                $query->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            })
            ->where('status', 'aprovado')
            ->with('localizacao') // Carrega os dados da localização
            ->get(); 
            
        return view('Map.mapa', compact('itens'));
        } 
     else {
            return redirect()->route('form.login');
        }
    }
}
