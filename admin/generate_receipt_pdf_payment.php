<?php
// Include database connection
include('../connection.php');

// Check if TCPDF is already available through composer autoload
if (file_exists('../vendor/autoload.php')) {
    require_once('../vendor/autoload.php');
} else {
    // Include TCPDF library - change path if needed
    require_once('tcpdf/tcpdf.php');
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get payment ID from POST
    $paymentId = isset($_POST['paymentId']) ? intval($_POST['paymentId']) : 0;
    
    if ($paymentId <= 0) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['status' => 'error', 'message' => 'Invalid payment ID']);
        exit;
    }
    
    // Fetch payment data from database
    $payment_query = "SELECT p.*, pt.pname FROM payments p 
                      INNER JOIN patient pt ON p.pid = pt.pid 
                      WHERE p.payment_id = $paymentId";
    $payment_result = $database->query($payment_query);
    
    if ($payment_result->num_rows === 0) {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['status' => 'error', 'message' => 'Payment record not found']);
        exit;
    }
    
    $payment = $payment_result->fetch_assoc();
    
    // Fetch appointment details
    $appo_query = "SELECT a.*, s.title, d.docname FROM appointment a 
                   INNER JOIN schedule s ON a.scheduleid = s.scheduleid 
                   INNER JOIN doctor d ON s.docid = d.docid 
                   WHERE a.appoid = " . $payment['appoid'];
    $appo_result = $database->query($appo_query);
    $appointment = $appo_result->num_rows > 0 ? $appo_result->fetch_assoc() : null;
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('eDoc System');
    $pdf->SetAuthor('Healthserv Los Baños Medical Center');
    $pdf->SetTitle('Payment Receipt');
    $pdf->SetSubject('Payment Receipt');
    
    // Remove header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    // Set margins
    $pdf->SetMargins(15, 15, 15);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);
    
    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 10);
    
    // Define the HTML content for the receipt
    $html = '
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .receipt-header h1 {
            font-size: 18px;
            margin: 5px 0;
        }
        .receipt-header p {
            font-size: 12px;
            margin: 5px 0;
        }
        .receipt-info {
            margin: 20px 0;
        }
        .receipt-info p {
            margin: 8px 0;
        }
        .payment-details {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .remarks {
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .admin-signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }
    </style>
    
    <div class="receipt-header">
        <h1>Healthserv Los Baños Medical Center</h1>
        <p>Address: 3817 National Highway, Los Baños, Laguna</p>
        <p>Phone: [Phone Number]</p>
        <p><strong>OFFICIAL RECEIPT</strong></p>
    </div>
    
    <div class="receipt-info">
        <p><strong>Receipt #:</strong> REC-' . sprintf('%06d', $payment['payment_id']) . '</p>
        <p><strong>Patient Name:</strong> ' . htmlspecialchars($payment['pname']) . '</p>';
    
    if ($appointment) {
        $html .= '<p><strong>Doctor:</strong> ' . htmlspecialchars($appointment['docname']) . '</p>
        <p><strong>Service:</strong> ' . htmlspecialchars($appointment['title']) . '</p>';
    }
    
    $html .= '<p><strong>Date:</strong> ' . date('Y-m-d H:i', strtotime($payment['payment_date'])) . '</p>
    </div>
    
    <div class="payment-details">
        <p><strong>Payment Type:</strong> ' . htmlspecialchars($payment['payment_type']) . '</p>
        <p><strong>Amount:</strong> ₱' . number_format($payment['amount'], 2) . '</p>
    </div>';
    
    if (!empty($payment['doctor_remarks'])) {
        $html .= '
        <div class="remarks">
            <p><strong>Doctor\'s Remarks:</strong></p>
            <p>' . nl2br(htmlspecialchars($payment['doctor_remarks'])) . '</p>
        </div>';
    }
    
    $html .= '
    <div class="admin-signature">
        <div class="signature-line"></div>
        <p>Administrator Signature</p>
    </div>
    
    <div class="footer">
        <p>This is a computer-generated receipt. No signature is required.</p>
        <p>Thank you for your payment!</p>
    </div>';
    
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Close and output PDF document
    $pdf->Output('Receipt_' . preg_replace('/\s+/', '_', $payment['pname']) . '_' . date('Y-m-d') . '.pdf', 'D');
    exit;
} else {
    // If not a POST request, return an error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
} 