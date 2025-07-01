<!DOCTYPE html>
<html>

<head>
    <title>Resume PDF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/js/pdf.js']) <!-- Only the resume template viewer -->
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
        }
    </style>
</head>

<body>
    <div id="app">
        <ResumePdfViewer :resume="{{ json_encode($resume) }}"></ResumePdfViewer>
    </div>
</body>

</html>
