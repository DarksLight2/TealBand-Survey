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
        Schema::create('employee_sessions', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->integer('status');
            $table->string('comment', 512)->nullable();
            $table->string('summary', 512)->nullable();

            $table->foreignIdFor(Survey::class);
            $table->foreignIdFor(Milestone::class);
            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');
            $table->foreignIdFor(config('tealband-survey.models.user'), 'user_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_sessions');
    }
};
