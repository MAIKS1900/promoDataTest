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
        Schema::create('process_statuses', static function (Blueprint $table) {
            $table->tinyIncrements('ps_id');
            $table->string('ps_name')->comment('Наименование статуса');
        });

        DB::table('process_statuses')->insert(['ps_id' => 1, 'ps_name' => 'Запуск']);
        DB::table('process_statuses')->insert(['ps_id' => 2, 'ps_name' => 'Завершен']);
        DB::table('process_statuses')->insert(['ps_id' => 3, 'ps_name' => 'Ошибка']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_statuses');
    }
};
