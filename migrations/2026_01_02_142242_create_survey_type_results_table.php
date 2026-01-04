<?php

use Tealband\Survey\Models\Survey;
use Tealband\Survey\Models\Milestone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_type_results', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');
            $table->foreignIdFor(Survey::class);
            $table->foreignIdFor(Milestone::class);
            $table->ulidMorphs('subject');

            $table->unsignedTinyInteger('type');
            $table->unsignedInteger('value');
            $table->string('summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_type_results');
    }
};
