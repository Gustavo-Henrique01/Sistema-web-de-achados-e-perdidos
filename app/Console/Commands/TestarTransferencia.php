<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Parceiro;
use App\Models\User;
use App\Models\ItemTransferencia;
use Illuminate\Support\Facades\DB;

class TestarTransferencia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:testar-transferencia {item_id? : ID do item} {parceiro_id? : ID do parceiro} {user_id? : ID do usuário}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a funcionalidade de transferência de um item para um parceiro';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TESTE DE TRANSFERÊNCIA DE ITEM PARA PARCEIRO');
        
        // Pegar os parâmetros ou pedir ao usuário
        $itemId = $this->argument('item_id');
        if (!$itemId) {
            $items = Item::where('status', 'aprovado')->get();
            if ($items->isEmpty()) {
                $this->error('Não existem itens aprovados para teste.');
                return 1;
            }
            
            $choices = $items->pluck('descricao', 'id')->toArray();
            $itemId = $this->choice('Selecione um item:', $choices);
        }
        
        $parceiroId = $this->argument('parceiro_id');
        if (!$parceiroId) {
            $parceiros = Parceiro::all();
            if ($parceiros->isEmpty()) {
                $this->error('Não existem parceiros cadastrados para teste.');
                return 1;
            }
            
            $choices = $parceiros->pluck('nome_estabelecimento', 'id')->toArray();
            $parceiroId = $this->choice('Selecione um parceiro:', $choices);
        }
        
        $userId = $this->argument('user_id');
        if (!$userId) {
            $users = User::all();
            if ($users->isEmpty()) {
                $this->error('Não existem usuários cadastrados para teste.');
                return 1;
            }
            
            $choices = $users->pluck('name', 'id')->toArray();
            $userId = $this->choice('Selecione um usuário:', $choices);
        }
        
        // Buscar modelos
        $item = Item::find($itemId);
        $parceiro = Parceiro::find($parceiroId);
        $user = User::find($userId);
        
        if (!$item) {
            $this->error('Item não encontrado.');
            return 1;
        }
        
        if (!$parceiro) {
            $this->error('Parceiro não encontrado.');
            return 1;
        }
        
        if (!$user) {
            $this->error('Usuário não encontrado.');
            return 1;
        }
        
        // Mostrar informações dos modelos selecionados
        $this->info("\n📋 INFORMAÇÕES DOS MODELOS SELECIONADOS");
        
        $this->line("\nITEM:");
        $this->table(
            ['ID', 'Descrição', 'Status', 'Categoria', 'Tipo'],
            [[
                $item->id,
                $item->descricao,
                $item->status,
                $item->categoria->nome_categoria ?? 'N/A',
                $item->tipo
            ]]
        );
        
        $this->line("\nPARCEIRO:");
        $this->table(
            ['ID', 'Nome', 'Endereço', 'Ativo'],
            [[
                $parceiro->id,
                $parceiro->nome_estabelecimento,
                $parceiro->localizacao->endereco ?? 'N/A',
                $parceiro->ativo ? 'Sim' : 'Não'
            ]]
        );
        
        $this->line("\nUSUÁRIO:");
        $this->table(
            ['ID', 'Nome', 'Email', 'Papel'],
            [[
                $user->id,
                $user->name,
                $user->email,
                $user->role->value ?? 'N/A'
            ]]
        );
        
        // Verificar se já existe uma transferência
        $transferenciasExistentes = ItemTransferencia::where('item_id', $item->id)
            ->where('parceiro_id', $parceiro->id)
            ->get();
        
        if ($transferenciasExistentes->isNotEmpty()) {
            $this->warn("\n⚠️ Já existem transferências para este item e parceiro:");
            $this->table(
                ['ID', 'Status', 'Criado em'],
                $transferenciasExistentes->map(function ($t) {
                    return [
                        $t->id,
                        $t->status,
                        $t->created_at->format('d/m/Y H:i:s')
                    ];
                })
            );
            
            if (!$this->confirm('Deseja criar uma nova transferência mesmo assim?')) {
                return 0;
            }
        }
        
        // Mostrar informações da operação
        $this->info("\n🔄 TESTE DE TRANSFERÊNCIA");
        
        // Confirmação final
        if (!$this->confirm("Confirma a criação de uma transferência do item '{$item->descricao}' para o parceiro '{$parceiro->nome_estabelecimento}'?")) {
            $this->info('Operação cancelada pelo usuário.');
            return 0;
        }
        
        // Executar a transferência
        $this->info("\n⏳ Executando transferência...");
        
        try {
            DB::beginTransaction();
            
            // Criar a transferência
            $transferencia = new ItemTransferencia();
            $transferencia->item_id = $item->id;
            $transferencia->parceiro_id = $parceiro->id;
            $transferencia->usuario_id = $user->id;
            $transferencia->observacoes = 'Teste via console - ' . now()->format('d/m/Y H:i:s');
            $transferencia->status = 'pendente';
            $transferencia->save();
            
            $this->info("✅ Transferência criada com sucesso! ID: {$transferencia->id}");
            
            // Atualizar o status do item
            $statusAnterior = $item->status;
            $parceiroAnterior = $item->parceiro_id;
            
            $item->status = 'em_transferencia';
            $item->parceiro_id = $parceiro->id;
            $item->save();
            
            $this->info("✅ Item atualizado com sucesso!");
            $this->line("   Status alterado: {$statusAnterior} -> {$item->status}");
            $this->line("   Parceiro alterado: {$parceiroAnterior} -> {$item->parceiro_id}");
            
            DB::commit();
            $this->info("\n✨ Teste concluído com sucesso!");
            
            // Mostrar detalhes da transferência criada
            $this->info("\n📊 DETALHES DA TRANSFERÊNCIA CRIADA");
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID', $transferencia->id],
                    ['Item ID', $transferencia->item_id],
                    ['Parceiro ID', $transferencia->parceiro_id],
                    ['Usuário ID', $transferencia->usuario_id],
                    ['Status', $transferencia->status],
                    ['Observações', $transferencia->observacoes],
                    ['Criado em', $transferencia->created_at->format('d/m/Y H:i:s')],
                ]
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erro durante a transferência: ' . $e->getMessage());
            $this->line("\nStack trace:");
            $this->line($e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
