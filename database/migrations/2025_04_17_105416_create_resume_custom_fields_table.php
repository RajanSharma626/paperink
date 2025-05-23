<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resume_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('label');
            $table->string('value');
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_custom_fields');
    }
};
