<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $model = \App\Models\DeviceType::class;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['switch', 'access_point']);
            $table->string('brand');
            $table->string('model');
            $table->timestamps();

            $table->softDeletes();
            $table->unique(['brand', 'model']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_types');
    }
};
