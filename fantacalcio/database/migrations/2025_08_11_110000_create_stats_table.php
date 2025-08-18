<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id');
            $table->string('position');
            $table->string('mantra_position')->nullable();
            $table->string('name');
            $table->string('team');
            $table->integer('n_votes');
            $table->float('average_vote');
            $table->float('average_fantavote');
            $table->integer('goals');
            $table->integer('goals_conceded');
            $table->integer('catched_penalties');
            $table->integer('taken_penalties');
            $table->integer('scored_penalties');
            $table->integer('missed_penalties');
            $table->integer('assists');
            $table->integer('yellow_cards');
            $table->integer('red_cards');
            $table->integer('own_goals');
            $table->string('season');
            $table->timestamps();

            $table->unique(['player_id', 'season']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};