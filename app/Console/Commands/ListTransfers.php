<?php

namespace App\Console\Commands;

use App\Models\ItemTransferencia;
use Illuminate\Console\Command;

class ListTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todas as transferências de itens para parceiros';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar a estrutura da tabela
        $this->info('Estrutura da tabela item_transferencias:');
        $colunas = \Schema::getColumnListing('item_transferencias');
        $this->info(implode(', ', $colunas));
        
        $transferencias = ItemTransferencia::with(['item', 'parceiro', 'usuario'])->get();
        
        if ($transferencias->isEmpty()) {
            $this->error('Não há transferências cadastradas no sistema.');
            
            // Verificar se a tabela existe
            $this->info('Verificando se a tabela existe...');
            $tableExists = \Schema::hasTable('item_transferencias');
            $this->info('A tabela item_transferencias ' . ($tableExists ? 'existe' : 'não existe') . ' no banco de dados.');
            
            // Testar criar uma transferência manualmente
            $this->info('Tentando criar uma transferência manualmente...');
            try {
                $item = \App\Models\Item::where('status', 'aprovado')->first();
                $parceiro = \App\Models\Parceiro::first();
                $usuario = \App\Models\User::first();
                
                if (!$item) {
                    $this->error('Não há itens aprovados para teste.');
                    return 1;
                }
                
                if (!$parceiro) {
                    $this->error('Não há parceiros cadastrados para teste.');
                    return 1;
                }
                
                if (!$usuario) {
                    $this->error('Não há usuários cadastrados para teste.');
                    return 1;
                }
                
                $transferencia = new ItemTransferencia();
                $transferencia->item_id = $item->id;
                $transferencia->parceiro_id = $parceiro->id;
                $transferencia->usuario_id = $usuario->id;
                $transferencia->observacoes = 'Teste via console';
                $transferencia->status = 'pendente';
                $transferencia->save();
                
                $this->info('Transferência criada com sucesso! ID: ' . $transferencia->id);
                
                // Verificar novamente se existem transferências
                $transferenciasApos = ItemTransferencia::count();
                $this->info('Total de transferências após o teste: ' . $transferenciasApos);
                
            } catch (\Exception $e) {
                $this->error('Erro ao criar transferência: ' . $e->getMessage());
            }
            
            return 1;
        }
        
        $this->info('Transferências cadastradas no sistema:');
        $this->table(
            ['ID', 'Item ID', 'Parceiro', 'Usuário', 'Status', 'Data'],
            $transferencias->map(function ($transferencia) {
                return [
                    'id' => $transferencia->id,
                    'item_id' => $transferencia->item_id,
                    'parceiro' => $transferencia->parceiro->nome_estabelecimento ?? 'Parceiro não encontrado',
                    'usuario' => $transferencia->usuario->name ?? 'Usuário não encontrado',
                    'status' => $transferencia->status,
                    'data' => $transferencia->created_at->format('d/m/Y H:i:s'),
                ];
            })
        );
        
        return 0;
    }
}
