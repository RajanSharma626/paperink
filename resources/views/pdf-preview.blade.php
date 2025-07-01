<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cover Letter Preview</title>

    <!-- Include your CSS/JS assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PDF-specific styles -->
    <style>
        /* PDF optimizations */
        @page {
            margin: 0;
            size: A4;
        }

        @media print {

            body,
            .page {
                margin: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .main .top {
                border-radius: 0 !important;
            }
        }

        /* Hide scrollbars and ensure full viewport */
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        #app {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* Remove any margins/padding from preview wrapper */
        .pdf-preview-container {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        /* Ensure template fits perfectly */
        .preview-scale {
            transform: scale(1) !important;
            transform-origin: top left !important;
        }

        /* Hide loading states */
        .spinner-border,
        .loading {
            display: none !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <pdf-preview template-id="{{ $template }}" :cover-data="{{ json_encode($data) }}">
        </pdf-preview>
    </div>

    <script>
        // Signal to Puppeteer when Vue has finished rendering
        window.addEventListener('load', () => {
            setTimeout(() => {
                // Remove any remaining loading elements
                document.querySelectorAll('.spinner-border, .loading').forEach(el => {
                    el.style.display = 'none';
                });

                // Signal that PDF is ready for capture
                window.pdfReady = true;

                // Add a data attribute for Puppeteer to detect
                document.body.setAttribute('data-pdf-ready', 'true');
            }, 2000); // Wait 2 seconds for Vue to fully render
        });

        // Handle any Vue errors gracefully
        window.addEventListener('error', (e) => {
            console.error('PDF Preview Error:', e);
            document.body.setAttribute('data-pdf-error', 'true');
        });
    </script>
</body>

</html>
