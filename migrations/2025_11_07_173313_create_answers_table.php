<?php

use Tealband\Survey\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('init_followup_text');
            $table->boolean('trigger_followup');
            $table->longText('gpt_prompt');
            $table->integer('weight');

            $table->foreignIdFor(Question::class);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
