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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');                     // Название события
            $table->text('description')->nullable();    // Описание события
            $table->dateTime('start_at');                // Время начала
            $table->dateTime('end_at')->nullable();      // Время окончания (не обязательно)
            $table->string('location')->nullable();      // Место проведения (например, кабинет)
            $table->boolean('all_day')->default(false);  // Целый день событие или нет
            $table->boolean('repeat_yearly')->default(false); // Повторять ежегодно (например, день рождения)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
