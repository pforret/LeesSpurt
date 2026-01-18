<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string('word', 50);
            $table->unsignedTinyInteger('length');
            $table->unsignedTinyInteger('minimum_age')->default(5);
            $table->timestamps();

            $table->unique(['language_id', 'word']);
            $table->index(['language_id', 'length']);
            $table->index(['language_id', 'length', 'minimum_age']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
