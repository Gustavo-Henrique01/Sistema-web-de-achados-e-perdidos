<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Categoria;
use App\Models\Parceiro;
use Illuminate\Support\Facades\Auth;

class ParceiroMapaController extends Controller
{
    public function mostrarMapa()
    {
        // Verificar se o usuário está autenticado e é um parceiro
        if (!Auth::check() || !Auth::user()->parceiro) {
            return redirect()->route('parceiro.login');
        }
        
        // Obter todos os parceiros
        $parceiros = Parceiro::with('localizacao')->get();
        
        // Obter itens aprovados de usuários ativos
        $itens = Item::with(['categoria', 'localizacao', 'fotos', 'usuario'])
            ->where('status', 'aprovado')
            ->whereHas('usuario', function($query) {
                $query->where('ativo', true);
            })
            ->get();
            
        // Obter todas as categorias para o filtro
        $categorias = Categoria::all();
        
        // Obter a chave da API do Google Maps
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        
        // Retornar a view com os dados
        return view('parceiro.mapa', [
            'itens' => $itens,
            'categorias' => $categorias,
            'parceiros' => $parceiros,
            'googleMapsApiKey' => $googleMapsApiKey
        ]);
    }
}
