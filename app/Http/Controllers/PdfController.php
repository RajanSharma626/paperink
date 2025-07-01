<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfController extends Controller
{
    public function generatePDFWithChromedp(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'resume_data' => 'required|array',
            'template' => 'required|string',
            'filename' => 'sometimes|string'
        ]);

        try {
            $resumeData = $validated['resume_data'];
            $template = $validated['template'];
            $filename = $validated['filename'] ?? 'resume.pdf';

            // Ensure filename is safe
            $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
            if (!str_ends_with($filename, '.pdf')) {
                $filename .= '.pdf';
            }

            // Generate HTML content using Blade view
            $html = view('resume.pdf-template', [
                'resume' => $resumeData,
                'template' => $template
            ])->render();

            // Create temporary file paths
            $tempDir = storage_path('app/temp');
            $tempHtmlFile = $tempDir . '/' . Str::uuid() . '.html';
            $tempPdfFile = $tempDir . '/' . Str::uuid() . '.pdf';
            
            // Ensure temp directory exists
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Add CSS and make HTML complete
            $completeHtml = $this->wrapHtmlWithAssets($html);
            
            // Write HTML to temporary file
            if (file_put_contents($tempHtmlFile, $completeHtml) === false) {
                throw new \Exception('Failed to write temporary HTML file');
            }

            // Get path to Go binary
            $binaryPath = base_path('bin/pdf-generator');
            if (!file_exists($binaryPath)) {
                throw new \Exception('PDF generator binary not found. Please build and install the Go binary.');
            }

            // Execute ChromeDP PDF generator
            $process = Process::run([
                $binaryPath,
                '-input', $tempHtmlFile,
                '-output', $tempPdfFile,
                '-format', 'A4',
                '-margin', '0.5in'
            ]);

            if (!$process->successful()) {
                Log::error('PDF generation failed', [
                    'error' => $process->errorOutput(),
                    'output' => $process->output()
                ]);
                throw new \Exception('PDF generation process failed: ' . $process->errorOutput());
            }

            // Verify PDF was created
            if (!file_exists($tempPdfFile)) {
                throw new \Exception('PDF file was not created successfully');
            }

            // Read PDF content
            $pdfContent = file_get_contents($tempPdfFile);
            if ($pdfContent === false) {
                throw new \Exception('Failed to read generated PDF file');
            }

            // Clean up temporary files
            @unlink($tempHtmlFile);
            @unlink($tempPdfFile);

            // Return PDF as download
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            // Clean up temporary files if they exist
            if (isset($tempHtmlFile) && file_exists($tempHtmlFile)) {
                @unlink($tempHtmlFile);
            }
            if (isset($tempPdfFile) && file_exists($tempPdfFile)) {
                @unlink($tempPdfFile);
            }

            Log::error('PDF generation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'PDF generation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Wrap HTML with necessary CSS and assets for PDF generation
     */
    private function wrapHtmlWithAssets(string $html): string
    {
        // You can add custom CSS here or load from external files
        $css = '
        <style>
            /* Additional PDF-specific styles */
            @page {
                size: A4;
                margin: 0.5in;
            }
            
            body {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Ensure proper font rendering */
            * {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
        </style>';

        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Resume PDF</title>
            ' . $css . '
        </head>
        <body>
            ' . $html . '
        </body>
        </html>';
    }

    /**
     * Get available resume templates (for your existing functionality)
     */
    public function getResumeTemplate(Request $request, string $template)
    {
        // Your existing template loading logic
        try {
            $templatePath = resource_path("js/components/templates/{$template}.vue");
            
            if (!file_exists($templatePath)) {
                return response()->json(['error' => 'Template not found'], 404);
            }

            return response()->json([
                'component' => $template,
                'path' => $templatePath
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load template'], 500);
        }
    }
}