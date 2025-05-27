<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Resume;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        // Log::info('Resume store request received', $request->all());

        try {
            // Simple validation first
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Create basic resume first
            $resume = Resume::create([
                'user_id' => Auth::id(), // Authenticated user ID
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

            // Log::info('Resume created with ID: ' . $resume->id);

            // Only process arrays if they exist and are not empty
            if ($request->filled('employmentHistory') && is_array($request->employmentHistory)) {
                foreach ($request->employmentHistory as $employment) {
                    if (isset($employment['jobTitle']) && isset($employment['company'])) {
                        Experience::create([
                            'resume_id' => $resume->id,
                            'job_title' => $employment['jobTitle'],
                            'company' => $employment['company'],
                            'start_date' => $this->parseDate($employment['startDate'] ?? null),
                            'end_date' => $this->parseDate($employment['endDate'] ?? null),
                            'city' => $employment['city'] ?? null,
                            'description' => $employment['description'] ?? null,
                        ]);
                    }
                }
                // Log::info('Employment history processed');
            }

            if ($request->filled('education') && is_array($request->education)) {
                foreach ($request->education as $edu) {
                    if (isset($edu['school']) && isset($edu['degree'])) {
                        Education::create([
                            'resume_id' => $resume->id,
                            'school' => $edu['school'],
                            'degree' => $edu['degree'],
                            'start_date' => $this->parseDate($edu['startDate'] ?? null),
                            'end_date' => $this->parseDate($edu['endDate'] ?? null),
                            'location' => $edu['city'] ?? null,
                            'description' => $edu['description'] ?? null,
                        ]);
                    }
                }
                // Log::info('Education processed');
            }

            if ($request->filled('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skill) {
                    if (isset($skill['skill']) && isset($skill['level'])) {
                        Skill::create([
                            'resume_id' => $resume->id,
                            'skill_name' => $skill['skill'],
                            'level' => $skill['level'],
                        ]);
                    }
                }
                // Log::info('Skills processed');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resume saved successfully',
                'data' => [
                    'resume_id' => $resume->id,
                    'resume' => $resume
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving resume: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            // Handle different date formats
            if (preg_match('/^\d{4}-\d{2}$/', $dateString)) {
                // Format: 2024-01
                return date('Y-m-d', strtotime($dateString . '-01'));
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                // Format: 2024-01-15
                return $dateString;
            } else {
                // Try to parse other formats
                return date('Y-m-d', strtotime($dateString));
            }
        } catch (\Exception $e) {
            Log::warning('Could not parse date: ' . $dateString);
            return null;
        }
    }

    public function show($id)
    {
        try {
            $resume = Resume::with(['experiences', 'education', 'skills'])->findOrFail($id);

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
