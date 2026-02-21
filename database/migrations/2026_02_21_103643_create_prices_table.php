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
        Schema::create('prices', static function (Blueprint $table) {
            $table->id('price_id');
            $table->integer('product_id')->index()->comment('Id товара');
            $table->decimal('price', 18, 2)->comment('Цена товара');
            $table->date('price_date')->comment('Дата цены');
            $table->timestamps();


            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

        });
        Schema::table('prices', static function () {
            DB::statement('CREATE UNIQUE INDEX idx_prices_product_date ON prices (product_id ASC, price_date DESC)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
