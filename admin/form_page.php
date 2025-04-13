<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-up Form</title>
    <style>
        /* Ensure the body and html take full height */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Full-screen PDF viewer */
        #pdfViewer {
            display: none;
            width: 100%;
            height: 100vh; /* Full height of the viewport */
        }

        /* Style for iframe to make it fullscreen */
        #pdfViewer iframe {
            width: 100%;
            height: 100%;
            border: none; /* Remove the border around the iframe */
        }
    </style>
</head>
<body>
    <!-- PDF Viewer -->
    <div id="pdfViewer">
        <iframe src="CHECK UP FORM.pdf" id="pdfFrame"></iframe>
    </div>

    <script>
        window.onload = function() {
            // Example of user role, which could come from a login system
            let userRole = "doctor"; // This would be dynamically set based on your authentication system
            
            // Check if user role is doctor
            if (userRole === "doctor") {
                document.getElementById('pdfViewer').style.display = 'block';
            } else {
                alert("Only doctors are allowed to view and fill this form.");
                // Optionally, redirect or hide the page content
                window.location.href = "access-denied.html"; // Redirect to an access denied page
            }
        };
    </script>
</body>
</html>