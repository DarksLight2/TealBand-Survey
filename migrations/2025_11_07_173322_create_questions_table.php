<?php

use Tealband\Survey\Models\Survey;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');

            $table->foreignIdFor(Survey::class);
            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');

            $table->integer('type');
            $table->json('keywords');
            $table->string('intent');
            $table->text('instruction');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
