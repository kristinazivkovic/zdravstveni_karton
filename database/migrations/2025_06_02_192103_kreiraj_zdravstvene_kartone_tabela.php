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
        Schema::create('zdravstveni_kartoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pacijent_id')->constrained('pacijenti')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->float('visina');
            $table->float('tezina');
            $table->string('krvni_pritisak');
            $table->text('dijagnoza');
            $table->text('tretman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zdravstveni_kartoni');
    }
};
