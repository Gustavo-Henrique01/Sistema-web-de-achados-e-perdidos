<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckItemsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-items-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica a estrutura da tabela itens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando a estrutura da tabela itens...');
        
        // Verificar se a tabela existe
        if (!Schema::hasTable('itens')) {
            $this->error('A tabela itens não existe no banco de dados!');
            return 1;
        }
        
        // Listar todas as colunas da tabela
        $columns = Schema::getColumnListing('itens');
        $this->info('Colunas da tabela itens:');
        $this->line(implode(', ', $columns));
        
        // Verificar se o campo parceiro_id existe
        if (!in_array('parceiro_id', $columns)) {
            $this->error('O campo parceiro_id não existe na tabela itens!');
            return 1;
        }
        
        // Obter informações detalhadas sobre a coluna parceiro_id
        $columnInfo = DB::select("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = 'itens' AND column_name = 'parceiro_id'
        ");
        
        if (count($columnInfo) > 0) {
            $this->info('Informações sobre a coluna parceiro_id:');
            $this->table(
                ['Nome', 'Tipo', 'Pode ser nulo', 'Valor padrão'],
                [
                    [
                        $columnInfo[0]->column_name,
                        $columnInfo[0]->data_type,
                        $columnInfo[0]->is_nullable,
                        $columnInfo[0]->column_default
                    ]
                ]
            );
        }
        
        // Verificar todas as chaves estrangeiras
        $foreignKeys = DB::select("
            SELECT
                tc.table_schema, 
                tc.constraint_name, 
                tc.table_name, 
                kcu.column_name, 
                ccu.table_schema AS foreign_table_schema,
                ccu.table_name AS foreign_table_name,
                ccu.column_name AS foreign_column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                    ON tc.constraint_name = kcu.constraint_name
                    AND tc.table_schema = kcu.table_schema
                JOIN information_schema.constraint_column_usage AS ccu
                    ON ccu.constraint_name = tc.constraint_name
                    AND ccu.table_schema = tc.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name='itens'
        ");
        
        if (count($foreignKeys) > 0) {
            $this->info('Chaves estrangeiras da tabela itens:');
            $this->table(
                ['Constraint', 'Coluna', 'Referência'],
                array_map(function($fk) {
                    return [
                        $fk->constraint_name,
                        $fk->column_name,
                        $fk->foreign_table_name . '.' . $fk->foreign_column_name
                    ];
                }, $foreignKeys)
            );
        } else {
            $this->warn('Nenhuma chave estrangeira encontrada para a tabela itens.');
        }
        
        // Verificar o tipo da coluna status
        $statusInfo = DB::select("
            SELECT column_name, data_type, is_nullable, column_default, udt_name
            FROM information_schema.columns
            WHERE table_name = 'itens' AND column_name = 'status'
        ");
        
        if (count($statusInfo) > 0) {
            $this->info('Informações sobre a coluna status:');
            $this->table(
                ['Nome', 'Tipo', 'UDT', 'Pode ser nulo', 'Valor padrão'],
                [
                    [
                        $statusInfo[0]->column_name,
                        $statusInfo[0]->data_type,
                        $statusInfo[0]->udt_name,
                        $statusInfo[0]->is_nullable,
                        $statusInfo[0]->column_default
                    ]
                ]
            );
            
            // Se for um ENUM, tentar obter os valores possíveis
            if ($statusInfo[0]->data_type === 'USER-DEFINED' || $statusInfo[0]->data_type === 'character varying') {
                $this->info('Tentando obter valores possíveis para o status...');
                try {
                    // Para PostgreSQL
                    $enumValues = DB::select("
                        SELECT e.enumlabel
                        FROM pg_type t JOIN pg_enum e ON t.oid = e.enumtypid
                        WHERE t.typname = ?
                    ", [$statusInfo[0]->udt_name]);
                    
                    if (!empty($enumValues)) {
                        $this->info('Valores possíveis para o status:');
                        $this->line(implode(', ', array_map(function($e) { return $e->enumlabel; }, $enumValues)));
                    }
                } catch (\Exception $e) {
                    $this->warn('Não foi possível obter os valores do enum: ' . $e->getMessage());
                }
            }
        }
        
        return 0;
    }
}
