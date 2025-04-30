<?php

namespace App\Http\Controllers;

use App\Models\CoverTemplate;
use Illuminate\Http\Request;

class CoverTemplateController extends Controller
{
    public function index()
    {
        return response()->json(CoverTemplate::all());
    }
}
