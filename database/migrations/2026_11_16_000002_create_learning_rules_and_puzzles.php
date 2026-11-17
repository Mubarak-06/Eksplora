<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_rules', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->unsignedInteger('value');
            $t->string('description');
        });
        Schema::create('quiz_puzzles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ecosystem_id')->unique()->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('size')->default(3);
            $t->string('name');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_puzzles');
        Schema::dropIfExists('learning_rules');
    }
};
