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
        Schema::create('pregledi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karton_id')->constrained('zdravstveni_kartoni')->onDelete('cascade');
            $table->foreignId('lekar_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('datum');
            $table->string('tip_pregleda'); 
            $table->text('opis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregledi');
    }
};
