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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('building')->nullable(false);
            $table->string('unit')->nullable(false);
            $table->timestamps();
        });

        // Varsayılan veri ekleme
        \Illuminate\Support\Facades\DB::table('locations')->insert([
            'building' => 'Rektörlük',
            'unit' => 'network',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
