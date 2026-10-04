<?php

use App\Enums\Categorias;
use App\Enums\Status;
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
        Schema::create('solicitacoes', function (Blueprint $table) {
           $table->uuid('id')->primary(); 
            $table->string('titulo', 255)->nullable(); 
            $table->string('descricao',5000)->nullable(); 
            $table->enum('categoria', array_column(Categorias::cases(), 'value')); 
            $table->enum('status', array_column(Status::cases(), 'value'))->default('Aberto');
            
            
           
            $table->uuid('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
