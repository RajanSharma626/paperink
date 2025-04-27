<?php

use App\Http\Controllers\CoverTemplateController;
use App\Http\Controllers\ResumeEditorController;
use App\Http\Controllers\ResumeTemplateController;
use Illuminate\Support\Facades\Route;


Route::get('/resume-templates', [ResumeTemplateController::class, 'index']);
Route::get('/cover-templates', [CoverTemplateController::class, 'index']);
Route::get('/editor/{template}', [ResumeEditorController::class, 'show'])->name('editor.show');


Route::get('{any}', function () {
    return view('app'); // This is catching everything currently
})->where('any', '.*');
