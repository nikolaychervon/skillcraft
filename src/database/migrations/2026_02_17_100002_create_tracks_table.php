<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->foreignId('specialization_id')->constrained('specializations')->cascadeOnDelete();
            $table->foreignId('programming_language_id')->constrained('programming_languages')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['specialization_id', 'programming_language_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
