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
        Schema::create('work_time_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date')->comment('Дата исключения');
            $table->time('start_time')->nullable()->comment('Время начала (если не полный день)');
            $table->time('end_time')->nullable()->comment('Время окончания (если не полный день)');
            $table->boolean('is_full_day')->default(false)->comment('Полный день или нет');
            $table->boolean('repeat_annually')->default(false)->comment('Повторять ежегодно');
            $table->string('reason')->nullable()->comment('Причина закрытия');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_time_exceptions');
    }
};
