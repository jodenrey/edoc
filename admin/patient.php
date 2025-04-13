<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Patients</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .payment-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 500;
            font-size: 0.9em;
        }

        .payment-status.paid {
            background-color: #4CAF50;
            color: white;
        }

        .payment-status.unpaid {
            background-color: #f44336;
            color: white;
        }

        .payment-status.pending {
            background-color: #FFC107;
            color: black;
        }
    </style>
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");

    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@edoc.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient  menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%">

                    <a href="patient.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                        
                    </td>
                    <td>
                        
                        <form action="" method="post" class="header-search">

                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query("select  pname,pemail from patient;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                            echo ' </datalist>';
?>
                            
                       
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        
                        </form>
                        
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                        date_default_timezone_set('Asia/Kolkata');

                        $date = date('Y-m-d');
                        echo $date;
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Patients (<?php echo $list11->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <?php
                    if($_POST){
                        $keyword=$_POST["search"];
                        
                        $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
                    }else{
                        $sqlmain= "select * from patient order by pid desc";

                    }



                ?>
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown"  style="border-spacing:0;">
                        <thead>
                        <tr>
                                <th class="table-headin">
                                    
                                
                                Name
                                
                                </th>
                               
                                <th class="table-headin">
                                
                            
                                Telephone
                                
                                </th>
                                <th class="table-headin">
                                    Email
                                </th>
                                <th class="table-headin">
                                    
                                    Date of Birth
                                    
                                </th>
                                <th class="table-headin">
                                    Payment Status
                                </th>
                                <th class="table-headin">
                                    
                                    Events
                                    
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php

                                
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="6">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                    <a class="non-style-link" href="patient.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Patients &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $pid=$row["pid"];
                                    $name=$row["pname"];
                                    $email=$row["pemail"];
                               
                                    $dob=$row["pdob"];
                                    $tel=$row["ptel"];
                                    $payment_status = isset($row["payment_status"]) ? $row["payment_status"] : "Unpaid";
                                    
                                    echo '<tr>
                                        <td> &nbsp;'.
                                        substr($name,0,35)
                                        .'</td>
                                      
                                        <td>
                                            '.substr($tel,0,10).'
                                        </td>
                                        <td>
                                        '.substr($email,0,20).'
                                         </td>
                                        <td>
                                        '.substr($dob,0,10).'
                                        </td>
                                        <td>
                                            <span class="payment-status '.strtolower($payment_status).'">'.$payment_status.'</span>
                                        </td>
                                        <td >
                                        <div style="display:flex;justify-content: center;">
                                        
                                        <a href="?action=view&id='.$pid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                       
                                        </div>
                                        </td>
                                    </tr>';
                                    
                                }
                            }
                                 
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    <?php 
    if($_GET){
        
        $id=$_GET["id"];
        $action=$_GET["action"];
            $sqlmain= "select * from patient where pid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $name=$row["pname"];
            $email=$row["pemail"];
          
            $dob=$row["pdob"];
            $tele=$row["ptel"];
            $address=$row["paddress"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <a class="close" href="patient.php">&times;</a>
                        <div class="content">

                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Patient ID: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    P-'.$id.'<br><br>
                                </td>
                                
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$name.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$email.'<br><br>
                                </td>
                            </tr>
                            
                         
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$tele.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Address: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            '.$address.'<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="payment_status" class="form-label">Payment Status: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <span class="payment-status '.strtolower($row["payment_status"]).'">'.$row["payment_status"].'</span><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="payment_history" class="form-label">Payment History: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <table width="100%" class="payment-history-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Type</th>
                                                <th>Doctor Remarks</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        
                                        // Get payment history
                                        $payment_query = "SELECT * FROM payments WHERE pid = '$id' ORDER BY payment_date DESC";
                                        $payment_result = $database->query($payment_query);
                                        
                                        if($payment_result->num_rows > 0) {
                                            while($payment = $payment_result->fetch_assoc()) {
                                                echo '<tr>
                                                    <td>'.date('Y-m-d H:i', strtotime($payment['payment_date'])).'</td>
                                                    <td>₱'.number_format($payment['amount'], 2).'</td>
                                                    <td>'.$payment['payment_type'].'</td>
                                                    <td>'.$payment['doctor_remarks'].'</td>
                                                    <td>
                                                        <button class="btn-primary-soft btn" onclick="showReceipt('.$payment['payment_id'].', \''.$name.'\', \''.$payment['payment_date'].'\', '.$payment['amount'].', \''.$payment['payment_type'].'\', \''.addslashes($payment['doctor_remarks']).'\')">View Receipt</button>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5" style="text-align: center;">No payment history found</td></tr>';
                                        }
                                        
                                        echo '</tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="patient.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        
    };

?>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fff; margin: 5% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <div class="receipt-header" style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
            <h2>Healthserv Los Baños Medical Center</h2>
            <p>Address: 3817 National Highway, Los Baños, Laguna</p>
            <p>Phone: [Phone Number]</p>
            <p><strong>Official Receipt</strong></p>
        </div>
        <div class="receipt-body" style="margin: 20px 0;">
            <div class="receipt-info">
                <p><strong>Receipt #:</strong> <span id="receiptNumber"></span></p>
                <p><strong>Patient Name:</strong> <span id="receiptPatientName"></span></p>
                <p><strong>Date:</strong> <span id="receiptDate"></span></p>
            </div>
            <div class="payment-details" style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-radius: 5px;">
                <p><strong>Payment Type:</strong> <span id="receiptPaymentType"></span></p>
                <p><strong>Amount:</strong> <span id="receiptAmount"></span></p>
            </div>
            <div class="doctor-remarks" style="margin-top: 20px;">
                <p><strong>Doctor's Remarks:</strong></p>
                <p id="receiptRemarks" style="padding: 10px; background-color: #f5f5f5; border-radius: 5px;"></p>
            </div>
            <div class="admin-signature" style="margin-top: 40px; text-align: right;">
                <div style="border-top: 1px solid #000; width: 200px; display: inline-block; margin-bottom: 5px;"></div>
                <p>Administrator Signature</p>
            </div>
            <div class="footer" style="margin-top: 30px; font-size: 12px; text-align: center; color: #777;">
                <p>This is a computer-generated receipt. No signature is required.</p>
                <p>Thank you for your payment!</p>
            </div>
        </div>
        <div class="receipt-footer" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; border-top: 2px solid #eee; padding-top: 15px;">
            <button id="printReceipt" class="btn-primary-soft btn" style="margin-right: 10px;">Print Receipt</button>
            <button id="downloadPDF" class="btn-primary-soft btn">Download PDF</button>
            <button id="closeModal" class="btn-primary-soft btn" style="background-color: #f44336; color: white;">Close</button>
        </div>
    </div>
</div>

<script>
    function showReceipt(paymentId, patientName, paymentDate, amount, paymentType, remarks) {
        // Format the receipt number
        document.getElementById('receiptNumber').textContent = 'REC-' + String(paymentId).padStart(6, '0');
        document.getElementById('receiptPatientName').textContent = patientName;
        document.getElementById('receiptDate').textContent = new Date(paymentDate).toLocaleString();
        document.getElementById('receiptPaymentType').textContent = paymentType;
        document.getElementById('receiptAmount').textContent = '₱' + parseFloat(amount).toFixed(2);
        document.getElementById('receiptRemarks').textContent = remarks || 'No remarks available';
        
        // Store payment ID for PDF generation
        document.getElementById('receiptModal').dataset.paymentId = paymentId;
        
        // Show the modal
        document.getElementById('receiptModal').style.display = 'block';
    }

    // Print receipt functionality
    document.getElementById('printReceipt').addEventListener('click', function() {
        // Clone the modal content for printing
        const printContent = document.querySelector('.modal-content').cloneNode(true);
        
        // Hide buttons in the print view
        printContent.querySelector('.receipt-footer').style.display = 'none';
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Payment Receipt</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                        max-width: 800px;
                        margin: 0 auto;
                        line-height: 1.5;
                    }
                    .receipt-header {
                        text-align: center;
                        margin-bottom: 20px;
                        border-bottom: 2px solid #eee;
                        padding-bottom: 15px;
                    }
                    .receipt-body {
                        margin: 20px 0;
                    }
                    .receipt-info p {
                        margin: 10px 0;
                    }
                    .payment-details {
                        margin: 20px 0;
                        padding: 15px;
                        background-color: #f9f9f9;
                        border-radius: 5px;
                    }
                    .doctor-remarks {
                        margin-top: 20px;
                    }
                    .admin-signature {
                        margin-top: 40px;
                        text-align: right;
                    }
                    @media print {
                        body {
                            print-color-adjust: exact;
                            -webkit-print-color-adjust: exact;
                        }
                    }
                </style>
            </head>
            <body>
                ${printContent.outerHTML}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
    });

    // Download as PDF functionality
    document.getElementById('downloadPDF').addEventListener('click', function() {
        const paymentId = document.getElementById('receiptModal').dataset.paymentId;
        const patientName = document.getElementById('receiptPatientName').textContent;
        
        // Create form data with receipt details
        let formData = new FormData();
        formData.append('paymentId', paymentId);
        formData.append('patientName', patientName);
        
        // Submit to PDF generation script
        fetch('generate_receipt_pdf_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Could not generate PDF');
            }
            return response.blob();
        })
        .then(blob => {
            // Create a download link and trigger it
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `Receipt_${patientName.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Error generating PDF:', error);
            alert("Error generating PDF. Please try again.");
        });
    });

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('receiptModal').style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target == document.getElementById('receiptModal')) {
            document.getElementById('receiptModal').style.display = 'none';
        }
    };
</script>

</body>
</html>