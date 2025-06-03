<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Resumes table
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // if you have user authentication

            $table->string('name');
            $table->string('last_name');
            $table->string('job_title')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->text('summary')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('resume_templates')->onDelete('set null'); // if you have a resume templates table
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Experiences table
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('job_title');
            $table->string('company');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        // Education table
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('school');
            $table->string('degree');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete for education records

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

        // Skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('skill_name');
            $table->enum('level', ['Beginner', 'Intermediate', 'Expert'])->nullable();
            $table->timestamps();

            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('skills');
        Schema::dropIfExists('education');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('resumes');
    }
};
