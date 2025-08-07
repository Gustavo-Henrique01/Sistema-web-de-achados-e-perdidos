
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 14)->change(); // 14 caracteres para XXX.XXX.XXX-XX
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 11)->change(); // Reverte para 11 caracteres sem pontuação
        });
    }
};