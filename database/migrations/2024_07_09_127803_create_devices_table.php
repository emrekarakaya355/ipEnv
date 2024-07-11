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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('device_type_id')->constrained('device_types')->onDelete('cascade');
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('desc')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->string('ip_address')->unique();;
            $table->string('status');
            $table->string('block')->nullable();
            $table->string('floor')->nullable();
            $table->string('room_number')->nullable();
            $table->unsignedBigInteger('parent_switch_id')->nullable();
            $table->foreign('parent_switch_id')->references('id')->on('devices')->onDelete('cascade');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
