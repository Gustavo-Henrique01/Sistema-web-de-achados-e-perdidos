<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_action_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('item_id')->constrained('itens');
            $table->string('acao'); // 'aprovacao', 'reprovacao', 'exclusao'
            $table->text('justificativa')->nullable();
            $table->string('status_anterior');
            $table->string('status_novo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_action_logs');
    }
}; 