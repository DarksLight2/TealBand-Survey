<?php

use Tealband\Survey\Models\Survey;
use Tealband\Survey\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('clarifying')->nullable();
            $table->string('comment');
            $table->integer('weight');

            $table->foreignIdFor(Question::class);
            $table->foreignIdFor(Survey::class);
            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
