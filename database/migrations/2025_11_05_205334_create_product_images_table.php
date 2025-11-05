<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id() ;
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('url')->nullable();
            $table->integer('size')->nullable(); // Tamanho em bytes
            $table->string('mime_type')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);

            // Relacionamento
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->timestamps();

            // Índices para otimização
            $table->index('product_id');
            $table->index('is_primary');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
