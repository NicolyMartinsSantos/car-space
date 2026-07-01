<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anuncios', function (Blueprint $table) {
        $table->id();

        $table->string('titulo');

        $table->text('descricao');

        $table->string('endereco');

        $table->decimal('preco', 8, 2);

        $table->string('telefone');

        $table->integer('quantidade_vagas');

        $table->string('categoria_vaga');

        $table->time('horario_inicio');

        $table->time('horario_fim');

        $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('anuncios');
    }
};
