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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_access_token')->nullable();
            $table->string('google_refresh_token')->nullable()->after('google_access_token');
            $table->string('google_email')->nullable()->after('google_refresh_token');
            $table->string('google_name')->nullable()->after('google_email');
            $table->string('google_avatar')->nullable()->after('google_name');
            $table->boolean('is_google_connected')->default(false)->after('google_avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_access_token');
            $table->dropColumn('google_refresh_token');
            $table->dropColumn('google_email');
            $table->dropColumn('google_name');
            $table->dropColumn('google_avatar');
            $table->dropColumn('is_google_connected');
        });
    }
};
