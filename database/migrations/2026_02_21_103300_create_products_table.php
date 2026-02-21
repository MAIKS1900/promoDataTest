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
        Schema::create('products', static function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name')->comment('Наименование товара');
            $table->integer('category_id')->index()->comment('Id категории товара');
            $table->integer('manufacturer_id')->index()->comment('Id производителя');

            $table->index(['category_id', 'product_id']);

            $table->foreign('manufacturer_id')
                ->references('manufacturer_id')
                ->on('manufacturers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
