<?php

use Tealband\Survey\Models\Answer;
use Tealband\Survey\Models\Question;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tealband\Survey\Models\EmployeeSession;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_session_answer', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(EmployeeSession::class);
            $table->foreignIdFor(Answer::class);
            $table->foreignIdFor(Question::class);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_session_answer');
    }
};
