<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('time_slot');
            $table->string('in_out')->nullable();
            $table->string('name')->nullable();
            $table->integer('cage_count')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->string('pickup_at')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receptions');
    }
};
