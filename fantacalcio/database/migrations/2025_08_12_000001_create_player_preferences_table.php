<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('player_id');
            $table->boolean('is_target')->default(false);
            $table->integer('value')->default(0);
            $table->integer('integrity')->default(0);
            $table->integer('quality')->default(0);
            $table->text('notes')->nullable();
            $table->integer('rank')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'player_id']);
            $table->index('player_id');
            $table->foreign('player_id')->references('id')->on('players')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_preferences');
    }
};
