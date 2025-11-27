<?php

use Tealband\Survey\Models\Survey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->integer('milestone');

            $table->morphs('department');
            $table->morphs('employee');
            $table->foreignIdFor(Survey::class);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_sessions');
    }
};
