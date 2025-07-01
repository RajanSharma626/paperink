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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'job_title' => 'required|string',
            'company_name' => 'required|string',
            'hiring_manager_name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'content' => 'required|string',
            'template_id' => 'nullable|exists:coverletter_templates,slug',
        ]);

        if (isset($validated['template_id'])) {
            $template = CoverTemplate::where('slug', $validated['template_id'])->first();
            if ($template) {
                $validated['template_id'] = $template->id;
            } else {
                return response()->json(['message' => 'Template not found.'], 404);
            }
        }

        $cover = CoverLetter::create($validated);

        return response()->json([
            'message' => 'Cover letter saved successfully.',
            'data' => $cover
        ], 201);
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
