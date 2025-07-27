<?php

namespace App\Http\Controllers;

use App\Models\CoverLetter;
use App\Models\CoverTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class CoverLetterController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'jobTitle' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'hiringManager' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'letter' => 'required|string',
            'template_id' => 'nullable|exists:coverletter_templates,slug',
        ]);

        // Map frontend keys to DB keys
        $data = [
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'job_title' => $validated['jobTitle'],
            'company_name' => $validated['company'],
            'hiring_manager_name' => $validated['hiringManager'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'content' => $validated['letter'],
        ];

        // Handle template_id (convert slug to id)
        if (isset($validated['template_id'])) {
            $template = CoverTemplate::where('slug', $validated['template_id'])->first();
            if ($template) {
                $data['template_id'] = $template->id;
            } else {
                return response()->json(['success' => false, 'message' => 'Template not found.'], 404);
            }
        }

        try {
            $cover = CoverLetter::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Cover letter saved successfully.',
                'data' => [
                    'cover_letter_id' => $cover->id,
                    'cover_letter' => $cover
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Cover letter save failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save cover letter.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myCoverLetter(Request $request)
    {
        $userId = $request->user_id;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            $CoverLetter = CoverLetter::with(['template'])
                ->where('user_id', $userId)
                ->get();

            Log::info('Data ' . $CoverLetter);
            return response()->json([
                'success' => true,
                'data' => $CoverLetter
            ]);
        } catch (\Exception $e) {
            Log::error('CoverLetter fetch failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'CoverLetter not found',
                'error' => $e->getMessage() // optional: return in dev only
            ], 500);
        }
    }

    /**
     * Display a single cover letter with template info
     */
    public function show($id)
    {
        try {
            $cover = CoverLetter::with('template')->findOrFail($id);
            $data = $cover->toArray();
            // Add template component name if available
            if ($cover->template) {
                $data['template'] = $cover->template;
            } else {
                $data['template'] = null;
            }

            $data['jobTitle'] = $data['job_title'];
            unset($data['job_title']);

            $data['company'] = $data['company_name'];
            unset($data['company_name']);

            $data['hiringManager'] = $data['hiring_manager_name'];
            unset($data['hiring_manager_name']);

            $data['letter'] = $data['content'];
            unset($data['content']);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cover letter not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }


    public function generatePDF(Request $request, $templateId)
    {
        $coverData = $request->validate([
            'name' => 'nullable|string',
            'jobTitle' => 'nullable|string',
            'company' => 'nullable|string',
            'hiringManager' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'letter' => 'nullable|string',
        ]);

        try {
            // Create preview URL with encoded data
            $encodedData = base64_encode(json_encode($coverData));
            $previewUrl = route('cover.preview', [
                'template' => $templateId,
                'data' => $encodedData
            ]);

            // Generate PDF using Puppeteer
            $pdf = Browsershot::url($previewUrl)
                ->format('A4')
                ->margins(0, 0, 0, 0) // No margins since your template handles spacing
                ->showBackground() // Important for background colors
                ->waitUntilNetworkIdle() // Wait for Vue to fully render
                ->dismissDialogs() // Dismiss any JS alerts/confirms
                ->ignoreHttpsErrors()
                ->timeout(60) // 60 seconds timeout
                ->setOption('args', ['--no-sandbox', '--disable-dev-shm-usage']) // For server environments
                ->pdf();

            $filename = ($coverData['name'] ?? 'cover-letter') . '-' . date('Y-m-d') . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'template_id' => $templateId,
                'cover_data' => $coverData
            ]);

            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => config('app.debug') ? $e->getMessage() : 'Please try again later.'
            ], 500);
        }
    }

    public function showPreview($template)
    {
        try {
            $data = json_decode(base64_decode(request('data')), true);

            if (!$data) {
                abort(400, 'Invalid data provided');
            }

            return view('pdf-preview', compact('template', 'data'));
        } catch (\Exception $e) {
            \Log::error('PDF Preview Error: ' . $e->getMessage());
            abort(500, 'Failed to load preview');
        }
    }
}
