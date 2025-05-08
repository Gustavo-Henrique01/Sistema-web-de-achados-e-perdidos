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

    /**
     * Exibe os detalhes de um item a partir do mapa
     * Usa uma view específica com layout de parceiro para evitar problemas de permissão
     */
    public function mostrarItem($id)
    {
        // Buscar item com todos os relacionamentos necessários
        $item = Item::with(['categoria', 'localizacao', 'fotos', 'parceiro.localizacao', 'usuario'])
            ->whereIn('status', ['aprovado', 'em_estabelecimento'])
            ->findOrFail($id);
        
        return view('Map.detalhes-item', [
            'item' => $item
        ]);
    }

    /**
     * Exibe os detalhes de um parceiro a partir do mapa
     * Usa uma view específica com layout de parceiro para evitar problemas de permissão
     */
    public function mostrarParceiro($id)
    {
        // Buscar parceiro com todos os relacionamentos necessários
        $parceiro = Parceiro::with(['localizacao', 'usuario'])
            ->where('status', 'aprovado')
            ->where('ativo', true)
            ->findOrFail($id);
        
        // Buscar itens que estão neste estabelecimento
        $itensEmEstabelecimento = Item::with(['categoria', 'fotos'])
            ->where('status', 'em_estabelecimento')
            ->where('parceiro_id', $parceiro->id)
            ->get();
        
        return view('Map.detalhes-parceiro', [
            'parceiro' => $parceiro,
            'itensEmEstabelecimento' => $itensEmEstabelecimento
        ]);
    }
}
