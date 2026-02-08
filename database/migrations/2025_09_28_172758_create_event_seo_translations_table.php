<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_seo_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_seo_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        
            $table->unique(['event_seo_id', 'language_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_seo_translations');
    }
};