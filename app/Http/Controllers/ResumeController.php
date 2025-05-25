<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Resume;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'lastName' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'nullable|string|max:20',
        //     'address' => 'nullable|string|max:500',
        //     'city' => 'nullable|string|max:255',
        //     'postalCode' => 'nullable|string|max:20',
        //     'country' => 'nullable|string|max:255',
        //     'jobTitle' => 'nullable|string|max:255',
        //     'summary' => 'nullable|string',
        //     'employmentHistory' => 'nullable|array',
        //     'employmentHistory.*.jobTitle' => 'required|string|max:255',
        //     'employmentHistory.*.company' => 'required|string|max:255',
        //     'employmentHistory.*.startDate' => 'nullable|date',
        //     'employmentHistory.*.endDate' => 'nullable|date',
        //     'employmentHistory.*.city' => 'nullable|string|max:255',
        //     'employmentHistory.*.description' => 'nullable|string',
        //     'education' => 'nullable|array',
        //     'education.*.school' => 'required|string|max:255',
        //     'education.*.degree' => 'required|string|max:255',
        //     'education.*.startDate' => 'nullable|date',
        //     'education.*.endDate' => 'nullable|date',
        //     'education.*.city' => 'nullable|string|max:255',
        //     'education.*.description' => 'nullable|string',
        //     'skills' => 'nullable|array',
        //     'skills.*.skill' => 'required|string|max:255',
        //     'skills.*.level' => 'required|in:Beginner,Intermediate,Expert',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        try {
            DB::beginTransaction();

            // Create or update resume
            $resume = Resume::create([
                'user_id' => Auth::id(), // assuming you have authentication
                'name' => $request->name,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postalCode,
                'country' => $request->country,
                'job_title' => $request->jobTitle,
                'summary' => $request->summary,
            ]);

            // Save employment history
            if ($request->has('employmentHistory') && is_array($request->employmentHistory)) {
                foreach ($request->employmentHistory as $employment) {
                    Experience::create([
                        'resume_id' => $resume->id,
                        'job_title' => $employment['jobTitle'],
                        'company' => $employment['company'],
                        'start_date' => $employment['startDate'] ? date('Y-m-d', strtotime($employment['startDate'] . '-01')) : null,
                        'end_date' => $employment['endDate'] ? date('Y-m-d', strtotime($employment['endDate'] . '-01')) : null,
                        'city' => $employment['city'] ?? null,
                        'description' => $employment['description'] ?? null,
                    ]);
                }
            }

            // Save education
            if ($request->has('education') && is_array($request->education)) {
                foreach ($request->education as $edu) {
                    Education::create([
                        'resume_id' => $resume->id,
                        'school' => $edu['school'],
                        'degree' => $edu['degree'],
                        'start_date' => $edu['startDate'] ? date('Y-m-d', strtotime($edu['startDate'] . '-01')) : null,
                        'end_date' => $edu['endDate'] ? date('Y-m-d', strtotime($edu['endDate'] . '-01')) : null,
                        'city' => $edu['city'] ?? null,
                        'description' => $edu['description'] ?? null,
                    ]);
                }
            }

            // Save skills
            if ($request->has('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skill) {
                    Skill::create([
                        'resume_id' => $resume->id,
                        'skill_name' => $skill['skill'],
                        'level' => $skill['level'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resume saved successfully',
                'data' => [
                    'resume_id' => $resume->id,
                    'resume' => $resume->load(['experiences', 'education', 'skills'])
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $resume = Resume::with(['experiences', 'education', 'skills'])
                ->where('user_id', auth()->id())
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $resume
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resume not found'
            ], 404);
        }
    }
}
