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
        Schema::create('script_device_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('script_id');
            $table->unsignedBigInteger('device_type_id');
            $table->foreign('script_id')->references('id')->on('scripts')->onDelete('cascade');
            $table->foreign('device_type_id')->references('id')->on('device_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('script_device_type');
    }
};
