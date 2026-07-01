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
    Schema::create('reservas', function (Blueprint $table) {

        $table->id();

        $table->date('data');

        $table->time('horario');

        $table->string('status');

        $table->integer('veiculo_id');

        $table->integer('anuncio_id');

        $table->timestamps();

    });
}
};