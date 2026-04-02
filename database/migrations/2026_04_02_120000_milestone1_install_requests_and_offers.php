<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('remember_token');
        });

        Schema::table('expert_profiles', function (Blueprint $table) {
            $table->string('city')->nullable()->after('user_id');
            $table->string('country')->nullable()->after('city');
        });

        Schema::create('install_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->text('hardware_notes')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('accepted_offer_id')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['city', 'country']);
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('install_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expert_user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_free')->default(false);
            $table->decimal('price_amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['install_request_id', 'status']);
        });

        Schema::table('install_requests', function (Blueprint $table) {
            $table->foreign('accepted_offer_id')
                ->references('id')
                ->on('offers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('install_requests', function (Blueprint $table) {
            $table->dropForeign(['accepted_offer_id']);
        });

        Schema::dropIfExists('offers');
        Schema::dropIfExists('install_requests');

        Schema::table('expert_profiles', function (Blueprint $table) {
            $table->dropColumn(['city', 'country']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
