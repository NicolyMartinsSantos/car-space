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
    Schema::create('veiculos', function (Blueprint $table) {

        $table->id();

        $table->string('placa');

        $table->string('modelo');

        $table->string('cor');

        $table->integer('ano');

        $table->string('categoria');

       // $table->foreignId('user_id')
         //     ->constrained()
           //   ->onDelete('cascade');

        $table->timestamps();

    });
}
};
