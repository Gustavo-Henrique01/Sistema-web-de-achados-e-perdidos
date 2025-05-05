<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Localizacao;
use App\Models\Categoria;
use App\Models\ItemFoto;
use Illuminate\Support\Carbon;
use App\Models\Parceiro;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function mostrarMapa()
    {   
        // Buscar parceiros com todos os dados necessários para o mapa
        $parceiros = Parceiro::with(['localizacao', 'usuario'])
            ->where('status', 'aprovado')
            ->where('ativo', true)
            ->get();
        
        // Buscar itens aprovados e em estabelecimento
        $itens = Item::with(['categoria', 'localizacao', 'fotos', 'parceiro.localizacao'])
            ->whereIn('status', ['aprovado', 'em_estabelecimento'])
            ->whereHas('usuario', function($query) {
                $query->where('ativo', true);
            })
            ->get();
            
        $categorias = Categoria::all();
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        
        return view('Map.mapa', [
            'itens' => $itens,
            'categorias' => $categorias,
            'parceiros' => $parceiros,
            'googleMapsApiKey' => $googleMapsApiKey
        ]);
    }

}
