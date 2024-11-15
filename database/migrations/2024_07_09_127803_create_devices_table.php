<?php

use App\Enums\DeviceStatus;
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
            $table->foreignId('device_type_id')->constrained('device_types');
            $table->string('type');
            $table->string('device_name')->nullable();
            $table->string('serial_number')->unique();
            $table->string('registry_number')->unique()->nullable();
            $table->string('mac_address')->unique();
            $table->enum('status', array_keys(DeviceStatus::toArray()))->default(DeviceStatus::STORAGE->name);
            $table->unsignedBigInteger('parent_device_id')->nullable();
            $table->foreign('parent_device_id')->references('id')->on('devices');
            $table->unsignedInteger('parent_device_port')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->string('isDeleted')->default('0');
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
