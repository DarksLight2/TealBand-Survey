<?php

use Tealband\Survey\Models\Survey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(config('tealband-survey.models.org'), 'org_id');
            $table->foreignIdFor(Survey::class);
            $table->integer('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
