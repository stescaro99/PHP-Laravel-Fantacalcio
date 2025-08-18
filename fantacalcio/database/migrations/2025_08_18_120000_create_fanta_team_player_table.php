<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fanta_team_player', function (Blueprint $table) {
            $table->unsignedBigInteger('fanta_team_id');
            $table->unsignedBigInteger('player_id');
            $table->timestamps();

            $table->primary(['fanta_team_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fanta_team_player');
    }
};
