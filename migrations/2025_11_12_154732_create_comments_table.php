<?php

use Tealband\Survey\Models\Answer;
use Tealband\Survey\Models\EmployeeSession;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(EmployeeSession::class);
            $table->foreignIdFor(Answer::class);
            $table->text('question_text')->default('');
            $table->text('answer_text')->default('');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
