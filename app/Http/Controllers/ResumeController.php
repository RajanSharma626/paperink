<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Resume;
use App\Models\ResumeTemplate;
use App\Models\Skill;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Illuminate\Support\Str;

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

            $previewImagePath = null;

            if ($request->filled('previewImage')) {
                try {
                    $base64Image = $request->previewImage;
                    $imageData = str_replace('data:image/png;base64,', '', $base64Image);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageBinary = base64_decode($imageData);

                    $imageName = 'preview_' . Str::random(10) . '.png';
                    $directory = public_path('resume_previews');

                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    // Load image into GD from string
                    $image = imagecreatefromstring($imageBinary);

                    if (!$image) {
                        throw new \Exception('Invalid image data.');
                    }

                    // Convert to grayscale
                    imagefilter($image, IMG_FILTER_GRAYSCALE);

                    // Save grayscale image
                    $fullPath = $directory . '/' . $imageName;
                    imagepng($image, $fullPath);

                    // Clean up
                    imagedestroy($image);

                    // Set relative path for DB
                    $previewImagePath = '/resume_previews/' . $imageName;
                } catch (\Exception $e) {
                    Log::error('Failed to process grayscale preview image: ' . $e->getMessage());
                }
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
                'template_id' => $template->id,
                'preview_image' => $previewImagePath
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
                    if (isset($skill['skill']) && !empty(trim($skill['skill']))) {
                        Skill::create([
                            'resume_id' => $resume->id,
                            'skill' => $skill['skill'], // Map 'skill' to 'skill_name'
                            'level' => $skill['level'] ?? 'Beginner', // Default to 'Beginner' if empty
                        ]);
                    }
                }
                Log::info('Skills processed');
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

    public function viewResume($id)
    {
        try {
            // Fetch resume with template relationship
            $resume = Resume::with(['experiences', 'education', 'skills', 'template'])->findOrFail($id);

            // Log::info('Resume fetched successfully', ['id' => $id, 'resume' => $resume->toArray()]);

            // Build data structure to return
            $data = $resume->toArray();

            // Rename keys
            $data['lastName'] = $data['last_name'];
            unset($data['last_name']);

            // You can add more key renames here
            $data['jobTitle'] = $data['job_title'];
            unset($data['job_title']);

            $data['postalCode'] = $data['postal_code'];
            unset($data['postal_code']);

            // Rename experiences to employmentHistory and adjust date keys
            $data['employmentHistory'] = array_map(function ($exp) {
                // Rename start_date and end_date to startDate and endDate
                $exp['startDate'] = $exp['start_date'];
                $exp['endDate'] = $exp['end_date'];
                $exp['jobTitle'] = $exp['job_title'];
                unset($exp['start_date'], $exp['end_date'], $exp['job_title']);
                // Format dates as "Y-m" (month and year) if not null
                if (!empty($exp['startDate'])) {
                    $exp['startDate'] = date('Y-m', strtotime($exp['startDate']));
                }
                if (!empty($exp['endDate'])) {
                    $exp['endDate'] = date('Y-m', strtotime($exp['endDate']));
                }
                return $exp;
            }, $data['experiences']);
            unset($data['experiences']);

            $data['education'] = array_map(function ($edu) {
                // Rename start_date and end_date to startDate and endDate
                $edu['startDate'] = $edu['start_date'];
                $edu['endDate'] = $edu['end_date'];
                unset($edu['start_date'], $edu['end_date']);
                // Format dates as "Y-m" (month and year) if not null
                if (!empty($edu['startDate'])) {
                    $edu['startDate'] = date('Y-m', strtotime($edu['startDate']));
                }
                if (!empty($edu['endDate'])) {
                    $edu['endDate'] = date('Y-m', strtotime($edu['endDate']));
                }
                return $edu;
            }, $data['education']);


            // Add template component name safely
            if ($resume->template) {
                $data['template'] = $resume->template->component;
                // Log::info('Template component found', ['component' => $resume->template->component]);
            } else {
                $data['template'] = null;
                // Log::warning('No template found for resume', ['resume_id' => $id]);
            }

            // Log::info('Resume data prepared for response', ['data_keys' => array_keys($data)]);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Resume not found', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Resume not found.',
                'error' => 'The requested resume does not exist.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching resume', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the resume.',
                'error' => $e->getMessage(),
            ], 500);
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

            // Log::info('Data ' . $resume);

            // Only change required key names/column names like in viewResume()
            $transformedResumes = $resume->map(function ($item) {
                $data = $item->toArray();

                // Change last_name to lastName if present
                if (isset($data['last_name'])) {
                    $data['lastName'] = $data['last_name'];
                    unset($data['last_name']);
                }

                // Change job_title to jobTitle if present
                if (isset($data['job_title'])) {
                    $data['jobTitle'] = $data['job_title'];
                    unset($data['job_title']);
                }

                // Change postal_code to postalCode if present
                if (isset($data['postal_code'])) {
                    $data['postalCode'] = $data['postal_code'];
                    unset($data['postal_code']);
                }

                // Rename experiences to employmentHistory and adjust date keys
                if (isset($data['experiences'])) {
                    $data['employmentHistory'] = array_map(function ($exp) {
                        if (isset($exp['start_date'])) {
                            $exp['startDate'] = $exp['start_date'];
                            unset($exp['start_date']);
                        }
                        if (isset($exp['end_date'])) {
                            $exp['endDate'] = $exp['end_date'];
                            unset($exp['end_date']);
                        }
                        if (isset($exp['job_title'])) {
                            $exp['jobTitle'] = $exp['job_title'];
                            unset($exp['job_title']);
                        }
                        // Format dates as "Y-m" (month and year) if not null
                        if (!empty($exp['startDate'])) {
                            $exp['startDate'] = date('Y-m', strtotime($exp['startDate']));
                        }
                        if (!empty($exp['endDate'])) {
                            $exp['endDate'] = date('Y-m', strtotime($exp['endDate']));
                        }
                        return $exp;
                    }, $data['experiences']);
                    unset($data['experiences']);
                }

                // Adjust education date keys
                if (isset($data['education'])) {
                    $data['education'] = array_map(function ($edu) {
                        if (isset($edu['start_date'])) {
                            $edu['startDate'] = $edu['start_date'];
                            unset($edu['start_date']);
                        }
                        if (isset($edu['end_date'])) {
                            $edu['endDate'] = $edu['end_date'];
                            unset($edu['end_date']);
                        }
                        // Format dates as "Y-m" (month and year) if not null
                        if (!empty($edu['startDate'])) {
                            $edu['startDate'] = date('Y-m', strtotime($edu['startDate']));
                        }
                        if (!empty($edu['endDate'])) {
                            $edu['endDate'] = date('Y-m', strtotime($edu['endDate']));
                        }
                        return $edu;
                    }, $data['education']);
                }


                return $data;
            });
            $resume = $transformedResumes;

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


    public function pdfPreview($id)
    {
        $resume = Resume::findOrFail($id); // contains user data + template slug

        return view('pdf.resume', compact('resume'));
    }


    public function updateTemp(Request $request, $resumeId)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:resume_templates,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $resume = Resume::findOrFail($resumeId);

            // Optionally, check if the authenticated user owns the resume
            // if (auth()->id() !== $resume->user_id) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized'
            //     ], 403);
            // }

            $resume->template_id = $request->input('template_id');
            $resume->save();

            // Optionally, return the updated resume or just success
            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
                'data' => $resume
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resume not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing resume and its related data.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $resume = Resume::findOrFail($id);

            // Update main resume fields
            $resume->update([
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
                'template_id' => $request->template_id ?? $resume->template_id,
            ]);

            // Update preview image if provided
            if ($request->filled('previewImage')) {
                try {
                    $base64Image = $request->previewImage;
                    $imageData = str_replace('data:image/png;base64,', '', $base64Image);
                    $imageData = str_replace(' ', '+', $imageData);
                    $imageBinary = base64_decode($imageData);
                    $imageName = 'preview_' . Str::random(10) . '.png';
                    $directory = public_path('resume_previews');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    $image = imagecreatefromstring($imageBinary);
                    if ($image) {
                        imagefilter($image, IMG_FILTER_GRAYSCALE);
                        $fullPath = $directory . '/' . $imageName;
                        imagepng($image, $fullPath);
                        imagedestroy($image);
                        $resume->preview_image = '/resume_previews/' . $imageName;
                        $resume->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to process preview image: ' . $e->getMessage());
                }
            }

            // Replace related experiences
            Experience::where('resume_id', $resume->id)->delete();
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
            }

            // Replace related education
            Education::where('resume_id', $resume->id)->delete();
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
            }

            // Replace related skills
            Skill::where('resume_id', $resume->id)->delete();
            if ($request->filled('skills') && is_array($request->skills)) {
                foreach ($request->skills as $skill) {
                    if (isset($skill['skill']) && !empty(trim($skill['skill']))) {
                        Skill::create([
                            'resume_id' => $resume->id,
                            'skill' => $skill['skill'],
                            'level' => $skill['level'] ?? 'Beginner',
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Resume updated successfully',
                'data' => [
                    'resume_id' => $resume->id,
                    'resume' => $resume
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resume not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating resume: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update resume',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
