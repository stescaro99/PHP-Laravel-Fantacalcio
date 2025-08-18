<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('position');
            $table->string('mantra_position')->nullable();
            $table->string('name');
            $table->string('team');
            $table->integer('quotation');
            $table->integer('initial_quotation');
            $table->integer('difference');
            $table->integer('mantra_quotation')->nullable();
            $table->integer('initial_mantra_quotation')->nullable();
            $table->integer('mantra_difference')->nullable();
            $table->integer('value');
            $table->integer('mantra_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
