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

Route::post('/resume/{resumeId}/update-template', [ResumeController::class, 'updateTemp']);

Route::post('/cover/{coverId}/update-template', [CoverLetterController::class, 'updateTemp']);

Route::post('/cover-letters', [CoverLetterController::class, 'store']);
Route::get('/cover-letters/{id}', [CoverLetterController::class, 'show']);

Route::put('/cover-letter/{id}', [CoverLetterController::class, 'update']);

Route::post('/my-resume', [ResumeController::class, 'myResume']);

Route::post('/my-cover', [CoverLetterController::class, 'myCoverLetter']);

Route::put('/resumes/{id}', [ResumeController::class, 'update']);

Route::delete('/resumes/{id}', [ResumeController::class, 'destroy']);
Route::delete('/cover-letter/{id}', [CoverLetterController::class, 'destroy']);

// PDF Generation routes
Route::post('/generate-pdf', [PDFController::class, 'generatePDF']);
Route::post('/cover-letter/generate-pdf', [PDFController::class, 'CoverLettergeneratePDF']);
Route::post('/generate-pdf-url', [PDFController::class, 'generatePDFFromURL']);
Route::post('/generate-pdf-dompdf', [PDFController::class, 'generatePDFWithDomPDF']);

// Alternative route structure if you prefer RESTful approach
Route::prefix('resumes/{id}')->group(function () {
    Route::post('/pdf', [PDFController::class, 'generatePDF']);
    Route::post('/pdf-url', [PDFController::class, 'generatePDFFromURL']);
});
