<?php

use Tealband\Survey\Models\Survey;
use Tealband\Survey\Models\Answer;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Models\Milestone;
use Tealband\Survey\Models\EmployeeSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');
            $table->foreignIdFor(config('tealband-survey.models.user'), 'user_id');
            $table->foreignIdFor(Survey::class);
            $table->foreignIdFor(Milestone::class);
            $table->foreignIdFor(Question::class);
            $table->foreignIdFor(Answer::class);
            $table->foreignIdFor(EmployeeSession::class);
            $table->longText('comment')->default('');
            $table->longText('ai_clarifying')->default('');
            $table->longText('response')->default('');
            $table->longText('summary')->default('');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
