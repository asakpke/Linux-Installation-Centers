<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('install_request_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('install_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['install_request_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('install_request_messages');
    }
};
