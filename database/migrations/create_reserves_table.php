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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->morphs('reservable');
            $table->string('customer_type')->nullable();
            $table->integer('customer_id')->nullable();
            $table->json('metadata')->nullable();
            $table->date('reserved_date');
            $table->time('reserved_time')->default('00:00:00');
            $table->date('end_reserve_date')->nullable();
            $table->time('end_reserve_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
