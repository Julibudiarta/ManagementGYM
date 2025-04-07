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
        Schema::create('plan_pts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration')->default(1);
            $table->integer('max_session')->default(1);
            $table->decimal('price', 10, 2);
            $table->integer('duration_per_minute')->default(60);
            $table->string('category')->default('default');
            $table->text('description')->nullable();
            $table->integer('loyalty_poin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_pts');
    }
};
