<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Item;
use App\Models\Parceiro;
use App\Models\ItemTransferencia;
use Illuminate\Support\Facades\Route;

class VerificarConfiguracao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-configuracao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica a configuração geral do projeto e exibe informações úteis para depuração';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE CONFIGURAÇÃO DO PROJETO ===');
        
        // Verificar versão do Laravel e PHP
        $this->info("\n📋 INFORMAÇÕES GERAIS");
        $this->line('Laravel: ' . app()->version());
        $this->line('PHP: ' . phpversion());
        $this->line('Ambiente: ' . app()->environment());
        
        // Verificar conexão com o banco de dados
        $this->info("\n📊 BANCO DE DADOS");
        try {
            DB::connection()->getPdo();
            $this->line('Conexão: <fg=green>✓ Conectado</>');
            $this->line('Tipo: ' . DB::connection()->getDriverName());
            $this->line('Nome do banco: ' . DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
        
        // Listar as tabelas principais e seus counts
        $this->info("\n📈 ESTATÍSTICAS DE TABELAS");
        
        $tabelas = [
            'users' => User::class,
            'itens' => Item::class,
            'parceiros' => Parceiro::class,
            'item_transferencias' => ItemTransferencia::class,
        ];
        
        $tabelasHeaders = ['Tabela', 'Existe?', 'Registros', 'Model'];
        $tabelasRows = [];
        
        foreach ($tabelas as $tabela => $model) {
            $existe = Schema::hasTable($tabela);
            $count = $existe ? DB::table($tabela)->count() : 'N/A';
            $modelOK = class_exists($model);
            
            $tabelasRows[] = [
                $tabela,
                $existe ? '<fg=green>✓</>' : '<fg=red>✗</>',
                $count,
                $modelOK ? '<fg=green>✓</>' : '<fg=red>✗</>'
            ];
        }
        
        $this->table($tabelasHeaders, $tabelasRows);
        
        // Verificar estrutura da tabela item_transferencias
        $this->info("\n🔍 ESTRUTURA DA TABELA ITEM_TRANSFERENCIAS");
        
        if (Schema::hasTable('item_transferencias')) {
            $columns = Schema::getColumnListing('item_transferencias');
            $columnsRows = [];
            
            foreach ($columns as $column) {
                $columnType = DB::select("
                    SELECT data_type, is_nullable, column_default
                    FROM information_schema.columns
                    WHERE table_name = 'item_transferencias' AND column_name = ?
                ", [$column]);
                
                if (!empty($columnType)) {
                    $columnsRows[] = [
                        $column,
                        $columnType[0]->data_type,
                        $columnType[0]->is_nullable === 'YES' ? 'Sim' : 'Não',
                        $columnType[0]->column_default,
                    ];
                }
            }
            
            $this->table(['Coluna', 'Tipo', 'Pode ser nulo', 'Valor padrão'], $columnsRows);
            
            // Verificar chaves estrangeiras
            $this->info("\n🔗 CHAVES ESTRANGEIRAS DA TABELA ITEM_TRANSFERENCIAS");
            
            $foreignKeys = DB::select("
                SELECT
                    tc.constraint_name,
                    kcu.column_name,
                    ccu.table_name AS foreign_table_name,
                    ccu.column_name AS foreign_column_name
                FROM
                    information_schema.table_constraints AS tc
                    JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                    JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
                WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name = 'item_transferencias'
            ");
            
            if (!empty($foreignKeys)) {
                $fkRows = [];
                
                foreach ($foreignKeys as $fk) {
                    $fkRows[] = [
                        $fk->constraint_name,
                        $fk->column_name,
                        $fk->foreign_table_name . '.' . $fk->foreign_column_name
                    ];
                }
                
                $this->table(['Constraint', 'Coluna', 'Referência'], $fkRows);
            } else {
                $this->warn('Nenhuma chave estrangeira definida');
            }
        } else {
            $this->error('A tabela item_transferencias não existe!');
        }
        
        // Verificar relacionamentos nos models
        $this->info("\n👥 RELACIONAMENTOS NOS MODELS");
        
        $relInfo = [
            'Item' => [
                'Método parceiro()' => method_exists(Item::class, 'parceiro'),
                'Método transferencias()' => method_exists(Item::class, 'transferencias'),
            ],
            'Parceiro' => [
                'Método itens()' => method_exists(Parceiro::class, 'itens'),
                'Método transferencias()' => method_exists(Parceiro::class, 'transferencias'),
            ],
            'ItemTransferencia' => [
                'Método item()' => method_exists(ItemTransferencia::class, 'item'),
                'Método parceiro()' => method_exists(ItemTransferencia::class, 'parceiro'),
                'Método usuario()' => method_exists(ItemTransferencia::class, 'usuario'),
            ]
        ];
        
        foreach ($relInfo as $model => $metodos) {
            $relRows = [];
            
            foreach ($metodos as $metodo => $existe) {
                $relRows[] = [
                    $metodo,
                    $existe ? '<fg=green>✓</>' : '<fg=red>✗</>'
                ];
            }
            
            $this->info("Model $model:");
            $this->table(['Método', 'Existe?'], $relRows);
        }
        
        // Verificar as rotas relacionadas
        $this->info("\n🛤️  ROTAS RELACIONADAS");
        
        $routes = [
            'item.enviar-para-parceiro',
            'parceiro.confirmar-recebimento',
            'debug.formulario-transferencia',
            'debug.processamento'
        ];
        
        $routesRows = [];
        
        foreach ($routes as $routeName) {
            try {
                $route = route($routeName, ['item' => 1], false);
                $routesRows[] = [
                    $routeName,
                    '<fg=green>✓</>',
                    $route
                ];
            } catch (\Exception $e) {
                $routesRows[] = [
                    $routeName,
                    '<fg=red>✗</>',
                    $e->getMessage()
                ];
            }
        }
        
        $this->table(['Nome da Rota', 'Existe?', 'URL'], $routesRows);
        
        // Sugestões e próximos passos
        $this->info("\n⚠️  VERIFICAÇÕES EXTRAS E RECOMENDAÇÕES");
        
        // Verificar se existe a coluna parceiro_id na tabela itens
        if (Schema::hasTable('itens') && Schema::hasColumn('itens', 'parceiro_id')) {
            $this->line('✅ A coluna parceiro_id existe na tabela itens');
            
            // Verificar se há chave estrangeira ligando itens.parceiro_id a parceiros.id
            $hasForeignKey = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                WHERE tc.constraint_type = 'FOREIGN KEY' 
                AND tc.table_name = 'itens'
                AND kcu.column_name = 'parceiro_id'
            ")[0]->count > 0;
            
            if ($hasForeignKey) {
                $this->line('✅ Existe uma chave estrangeira para parceiro_id na tabela itens');
            } else {
                $this->warn('⚠️  Não existe chave estrangeira para parceiro_id na tabela itens. Sugestão: execute a migração add_foreign_key_to_parceiro_id');
            }
        } else {
            $this->error('❌ A coluna parceiro_id não existe na tabela itens!');
        }
        
        // Verificar se existe status "em_transferencia" para itens
        if (Schema::hasTable('itens') && Schema::hasColumn('itens', 'status')) {
            $statusCount = DB::table('itens')->where('status', 'em_transferencia')->count();
            $this->line("ℹ️  Existem {$statusCount} itens com status 'em_transferencia'");
        }
        
        return 0;
    }
} 