<?php

namespace App\Http\Controllers;

use App\Models\ResumeTemplate;
use Illuminate\Http\Request;

class ResumeTemplateController extends Controller
{
    public function index()
    {
        return response()->json(ResumeTemplate::all());
    }

    // app/Http/Controllers/ResumeTemplateController.php
    public function show($slug)
    {
        $template = ResumeTemplate::where('slug', $slug)->firstOrFail();
        return response()->json($template);
    }
}
