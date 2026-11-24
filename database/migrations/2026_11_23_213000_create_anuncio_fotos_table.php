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
        Schema::create('anuncio_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anuncio_id')
                  ->constrained('anuncios')
                  ->onDelete('cascade');
            $table->string('caminho');
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();

            $table->index(['anuncio_id', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anuncio_fotos');
    }
};
