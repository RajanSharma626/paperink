<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Resume;
use App\Models\ResumeTemplate;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

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

            $template = ResumeTemplate::where('slug', $request->template_id)
                ->first();

            // Create basic resume first
            $resume = Resume::create([
                'user_id' => $request->user_id, // Authenticated user ID
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
                'template_id' => $template->id
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

    public function myResume(Request $request)
    {
        $userId = $request->user_id;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            $resume = Resume::with(['experiences', 'education', 'skills', 'template'])
                ->where('user_id', $userId)
                ->get();

            Log::info('Data ' . $resume);
            return response()->json([
                'success' => true,
                'data' => $resume
            ]);
        } catch (\Exception $e) {
            Log::error('Resume fetch failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Resume not found',
                'error' => $e->getMessage() // optional: return in dev only
            ], 500);
        }
    }


    public function generatePDF(Request $request)
    {
        $resume = json_decode(json_encode($request->all())); // Convert stdClass for Blade access

        $html = View::make('resume.pdf', ['resume' => $resume])->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        $mpdf->SetTitle("{$resume->name} {$resume->lastName} - Resume");
        $mpdf->SetAuthor("{$resume->name} {$resume->lastName}");
        $mpdf->SetSubject('Professional Resume');
        $mpdf->SetKeywords('resume, cv, ' . $resume->jobTitle);
        $mpdf->SetCreator('Resume Builder');

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="resume.pdf"');
    }
}
