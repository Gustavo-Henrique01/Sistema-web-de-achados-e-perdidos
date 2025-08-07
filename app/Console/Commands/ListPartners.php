<?php

namespace App\Console\Commands;

use App\Models\Parceiro;
use Illuminate\Console\Command;

class ListPartners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-partners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todos os parceiros cadastrados no sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $parceiros = Parceiro::with(['usuario', 'localizacao'])->get();
        
        if ($parceiros->isEmpty()) {
            $this->error('Não há parceiros cadastrados no sistema.');
            return 1;
        }
        
        $this->info('Parceiros cadastrados no sistema:');
        $this->table(
            ['ID', 'Nome do estabelecimento', 'Endereço', 'Email do Usuário', 'Tipo', 'Ativo'],
            $parceiros->map(function ($parceiro) {
                return [
                    'id' => $parceiro->id,
                    'nome' => $parceiro->nome_estabelecimento,
                    'endereco' => $parceiro->localizacao->endereco ?? 'Endereço não definido',
                    'email' => $parceiro->usuario->email ?? 'Email não associado',
                    'tipo' => $parceiro->tipo_parceiro,
                    'ativo' => $parceiro->ativo ? 'Sim' : 'Não',
                ];
            })
        );
        
        return 0;
    }
}
