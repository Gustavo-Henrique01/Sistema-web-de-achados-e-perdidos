<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $table = 'maps';
    protected $fillable = [
        'endereco_id',
        'latitude',
        'longitude',
    ];
    public function endereco()  
    {
        return $this->belongsTo(Endereco::class, 'endereco_id');
    }
}
