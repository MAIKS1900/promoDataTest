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
        Schema::create('report_processes', static function (Blueprint $table) {
            $table->id('rp_id');
            $table->integer('rp_pid')->comment('Идентификатор процесса');
            $table->dateTimeTz('rp_start_datetime')->comment('Дата/время начала процесса');
            $table->integer('rp_exec_time')->default(0)->comment('Время выполнения');
            $table->tinyInteger('ps_id')->comment('Статус процесса');
            $table->string('rp_file_save_path')->nullable()->comment('Путь к сохраненному файлу');

            $table->foreign('ps_id')->references('ps_id')->on('process_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_processes');
    }
};
