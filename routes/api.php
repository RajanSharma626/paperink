<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoverTemplateController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeTemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/resume-template/{slug}', [ResumeTemplateController::class, 'show']);
Route::get('/cover-template/{slug}', [CoverTemplateController::class, 'show']);

Route::post('/resumes/store', [ResumeController::class, 'store'])
    ->name('api.resumes.store');
