<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoverLetterController;
use App\Http\Controllers\CoverTemplateController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ResumeTemplateController;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/resume-template/{slug}', [ResumeTemplateController::class, 'show']);
Route::get('/cover-template/{slug}', [CoverTemplateController::class, 'show']);

Route::get('/resumes/{id}', [ResumeController::class, 'viewResume']);

Route::post('/resumes/store', [ResumeController::class, 'store'])
    ->name('api.resumes.store');

Route::post('/cover-letters', [CoverLetterController::class, 'store']);

// PDF Generation routes
Route::post('/generate-pdf', [PDFController::class, 'generatePDF']);
Route::post('/generate-pdf-url', [PDFController::class, 'generatePDFFromURL']);
Route::post('/generate-pdf-dompdf', [PDFController::class, 'generatePDFWithDomPDF']);

// Alternative route structure if you prefer RESTful approach
Route::prefix('resumes/{id}')->group(function () {
    Route::post('/pdf', [PDFController::class, 'generatePDF']);
    Route::post('/pdf-url', [PDFController::class, 'generatePDFFromURL']);
});
