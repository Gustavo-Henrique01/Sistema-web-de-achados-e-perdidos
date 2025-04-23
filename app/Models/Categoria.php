<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    

        protected $table = 'categorias';
    
        public $timestamps = false; // Desativa a verificação de timestamps
        protected $fillable = [
            'nome_categoria'
        ];
    
 
        public function itens()
        {
            return $this->hasMany(Item::class, 'id_categoria');
        }
        
}
