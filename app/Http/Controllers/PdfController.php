<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PdfController extends Controller
{
    public function generatePDF(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'html' => 'required|string',
                'styles' => 'nullable|string',
                'resume_id' => 'required',
                'resume_name' => 'nullable|string',
                'options' => 'nullable|array'
            ]);

            // Prepare the HTML content with better structure
            $html = $this->prepareHTMLContentForPDF(
                $validated['html'],
                $validated['styles'] ?? '',
                $validated['options'] ?? []
            );

            // Generate PDF using Browsershot with optimized settings
            $pdf = Browsershot::html($html)
                ->format('A4')
                ->margins(0, 0, 0, 0) // Reduced margins for better space utilization
                ->showBackground()
                ->showBrowserHeaderAndFooter(false)
                ->waitUntilNetworkIdle()
                ->timeout(60)
                ->emulateMedia('print') // Important for print-specific CSS
                ->dismissDialogs()
                ->ignoreHttpsErrors()
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-gpu',
                    '--no-first-run',
                    '--disable-default-apps',
                    '--disable-extensions'
                ])
                ->pdf();

            // Prepare filename
            $filename = $this->generateFilename($validated['resume_name'] ?? 'Resume');

            // Return PDF as download
            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => strlen($pdf),
            ]);
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    private function prepareHTMLContentForPDF($html, $styles = '', $options = [])
    {
        // Clean and prepare the HTML
        $cleanHtml = $this->cleanHTMLForPDF($html);

        // Get Bootstrap Icons CSS
        $bootstrapIconsCSS = $this->getBootstrapIconsCSS();

        // Create complete HTML document
        $fullHtml = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume PDF</title>
    <style>
        ' . $bootstrapIconsCSS . '
        ' . $styles . '
        
        /* Additional styles for better PDF rendering */
        @media print {
            .bi {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    ' . $cleanHtml . '
</body>
</html>';

        return $fullHtml;
    }

    // private function getBootstrapIconsCSS()
    // {
    //     return file_get_contents('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
    // }

    private function getBootstrapIconsCSS()
    {
        // Use local Bootstrap Icons font
        $fontPath = public_path('fonts/bootstrap-icons.woff2');
        $fontBase64 = '';

        if (file_exists($fontPath)) {
            $fontBase64 = base64_encode(file_get_contents($fontPath));
        } else {
            // Fallback: try to download the font
            try {
                $fontUrl = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2';
                $fontContent = file_get_contents($fontUrl);
                $fontBase64 = base64_encode($fontContent);

                // Save it locally for future use
                if (!file_exists(public_path('fonts'))) {
                    mkdir(public_path('fonts'), 0755, true);
                }
                file_put_contents($fontPath, $fontContent);
            } catch (\Exception $e) {
                Log::warning('Failed to download Bootstrap Icons font: ' . $e->getMessage());
            }
        }

        return "
    @font-face {
        font-family: 'bootstrap-icons';
        src: url('data:application/font-woff2;charset=utf-8;base64,{$fontBase64}') format('woff2');
        font-weight: normal;
        font-style: normal;
        font-display: block;
    }
    
    .bi, .icons {
        font-family: 'bootstrap-icons' !important;
        speak: never;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    /* Bootstrap Icons Unicode mappings */
    .bi-envelope-fill::before { content: '\\f32f'; }
    .bi-phone-fill::before { content: '\\f4f4'; }
    .bi-geo-alt-fill::before { content: '\\f349'; }
    .bi-linkedin::before { content: '\\f472'; }
    .bi-github::before { content: '\\f3ed'; }
    .bi-globe::before { content: '\\f3f5'; }
    .bi-calendar::before { content: '\\f1ec'; }
    .bi-briefcase::before { content: '\\f1e6'; }
    .bi-mortarboard::before { content: '\\f4a4'; }
    .bi-person::before { content: '\\f4da'; }
    .bi-skype::before { content: '\\f4f2'; }
    ";
    }

    private function cleanHTMLForPDF($html)
    {
        // Remove problematic elements and fix common issues
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<noscript[^>]*>.*?<\/noscript>/is', '', $html);

        // Convert external images to base64 if needed
        $html = $this->convertImagesToBase64($html);

        // Remove the icon font replacement since we're now supporting Bootstrap Icons
        // $html = $this->replaceIconFonts($html); // Comment out this line

        return $html;
    }

    private function convertImagesToBase64($html)
    {
        // Find all img tags and convert external images to base64
        return preg_replace_callback(
            '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i',
            function ($matches) {
                $src = $matches[1];

                // Skip if already base64
                if (strpos($src, 'data:') === 0) {
                    return $matches[0];
                }

                // Skip if external URL (for security)
                if (strpos($src, 'http') === 0) {
                    return $matches[0];
                }

                // Convert local images to base64
                try {
                    $imagePath = public_path($src);
                    if (file_exists($imagePath)) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $mimeType = mime_content_type($imagePath);
                        $base64Src = 'data:' . $mimeType . ';base64,' . $imageData;
                        return str_replace($src, $base64Src, $matches[0]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to convert image to base64: ' . $src);
                }

                return $matches[0];
            },
            $html
        );
    }

    // Keep this method as fallback, but don't use it by default
    private function replaceIconFonts($html)
    {
        // Replace common icon fonts with Unicode equivalents
        $iconReplacements = [
            'bi-envelope-fill' => '✉',
            'bi-phone-fill' => '📞',
            'bi-geo-alt-fill' => '📍',
            'fa-linkedin' => '💼',
            'fa-github' => '🔗',
            'fa-globe' => '🌐',
            'fa-calendar' => '📅',
            'fa-briefcase' => '💼',
            'fa-graduation-cap' => '🎓',
            'fa-user' => '👤',
        ];

        foreach ($iconReplacements as $iconClass => $unicode) {
            $html = preg_replace(
                '/<i[^>]*class="[^"]*' . preg_quote($iconClass) . '[^"]*"[^>]*><\/i>/',
                '<span class="icon">' . $unicode . '</span>',
                $html
            );
        }

        return $html;
    }

    private function generateFilename($resumeName)
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $resumeName);
        return $cleanName . '_' . date('Y-m-d_H-i-s') . '.pdf';
    }
}
