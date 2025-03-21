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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pai_id')->unsigned()->nullable()->default(null);
            $table->string('nome', 180);
            $table->text('descricao');
            $table->timestamps();

            $table->foreign('pai_id')
            ->references('id') 
            ->on('categorias')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
