<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('public_profile_enabled')->default(false)->after('is_active');
            $table->string('public_slug', 80)->nullable()->unique()->after('public_profile_enabled');
            $table->text('public_bio')->nullable()->after('public_slug');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['public_profile_enabled', 'public_slug', 'public_bio']);
        });
    }
};
