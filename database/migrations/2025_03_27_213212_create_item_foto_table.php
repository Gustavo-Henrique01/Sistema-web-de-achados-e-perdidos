<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('itens')->onDelete('cascade');
            $table->string('caminho');
            $table->integer('ordem')->default(0);
            $table->timestamps();
            
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_fotos');
    }
};