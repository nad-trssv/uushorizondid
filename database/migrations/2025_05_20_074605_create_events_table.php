<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published', 'archived', 'cancelled'])->default('draft');
            $table->string('image')->nullable();
            $table->unsignedInteger('max_participants')->default(10);
            $table->unsignedInteger('current_participants')->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamp('registration_deadline')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};