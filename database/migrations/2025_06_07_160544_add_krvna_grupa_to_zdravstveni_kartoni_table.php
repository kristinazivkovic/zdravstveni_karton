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
        Schema::table('zdravstveni_kartoni', function (Blueprint $table) {
            $table->enum('krvna_grupa', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', '0+', '0-'])
                ->nullable()
                ->default(null)
                ->after('user_id')
                ->comment('Dozvoljene vrednosti: A+, A-, B+, B-, AB+, AB-, O+, O-');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zdravstveni_kartoni', function (Blueprint $table) {
            $table->dropColumn('krvna_grupa');
        });
    }
};
