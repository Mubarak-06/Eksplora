<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecosystems', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
            $t->text('summary');
            $t->string('image');
            $t->json('content');
            $t->timestamps();
        });
        Schema::create('organisms', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
            $t->string('scientific_name');
            $t->string('category');
            $t->text('summary');
            $t->string('image');
            $t->json('content');
            $t->timestamps();
        });
        Schema::create('ecosystem_organism', function (Blueprint $t) {
            $t->foreignId('ecosystem_id')->constrained()->cascadeOnDelete();
            $t->foreignId('organism_id')->constrained()->cascadeOnDelete();
            $t->primary(['ecosystem_id', 'organism_id']);
        });
        Schema::create('quiz_questions', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('type');
            $t->text('question');
            $t->json('options');
            $t->string('answer');
            $t->text('explanation');
            $t->string('image')->nullable();
            $t->timestamps();
        });
        Schema::create('quiz_attempts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->uuid('token')->unique();
            $t->string('type');
            $t->unsignedInteger('score');
            $t->json('answers');
            $t->timestamps();
        });
        Schema::create('user_learning_progress', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('activity');
            $t->string('subject');
            $t->timestamps();
            $t->unique(['user_id', 'activity', 'subject'], 'progress_unique');
        });
        Schema::create('badges', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
            $t->text('requirement');
            $t->string('icon');
            $t->timestamps();
        });
        Schema::create('user_badges', function (Blueprint $t) {
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->primary(['user_id', 'badge_id']);
        });
        Schema::create('point_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('activity');
            $t->string('source');
            $t->unsignedInteger('points');
            $t->timestamps();
            $t->unique(['user_id', 'source']);
        });
        Schema::create('user_awards', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('slug');
            $t->string('name');
            $t->timestamps();
            $t->unique(['user_id', 'slug']);
        });
    }

    public function down(): void
    {
        foreach (['user_awards', 'point_transactions', 'user_badges', 'badges', 'user_learning_progress', 'quiz_attempts', 'quiz_questions', 'ecosystem_organism', 'organisms', 'ecosystems'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
