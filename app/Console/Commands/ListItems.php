<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class ListItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-items {status? : Filtrar por status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todos os itens cadastrados no sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $status = $this->argument('status');
        
        $query = Item::with(['categoria', 'usuario', 'parceiro', 'localizacao']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $itens = $query->get();
        
        if ($itens->isEmpty()) {
            $this->error('Não há itens cadastrados' . ($status ? " com status '$status'" : '') . ' no sistema.');
            return 1;
        }
        
        $this->info('Itens cadastrados no sistema' . ($status ? " com status '$status'" : '') . ':');
        $this->table(
            ['ID', 'Descrição', 'Categoria', 'Usuário', 'Status', 'Parceiro', 'Data'],
            $itens->map(function ($item) {
                return [
                    'id' => $item->id,
                    'descricao' => substr($item->descricao, 0, 30) . (strlen($item->descricao) > 30 ? '...' : ''),
                    'categoria' => $item->categoria->nome_categoria ?? 'Sem categoria',
                    'usuario' => $item->usuario->name ?? 'Usuário não encontrado',
                    'status' => $item->status,
                    'parceiro' => $item->parceiro ? $item->parceiro->nome_estabelecimento : 'Sem parceiro',
                    'data' => $item->created_at->format('d/m/Y H:i:s'),
                ];
            })
        );
        
        // Mostra estatísticas por status
        $this->info('Estatísticas por status:');
        $stats = Item::selectRaw('status, count(*) as count')
                    ->groupBy('status')
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();
                    
        foreach ($stats as $status => $count) {
            $this->line("- $status: $count item(s)");
        }
        
        return 0;
    }
}
