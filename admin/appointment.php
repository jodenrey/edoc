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
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .receipt-container {
            padding: 20px;
            border: 1px solid #ccc;
            width: 400px;
            background-color: #f9f9f9;
        }
        .receipt-section {
            display: none;
            margin-top: 20px;
        }
        .receipt-print-button {
            margin-top: 10px;
            display: block;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .amount-input {
            margin-top: 15px;
        }

        .amount-input input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }

        .doctor-remarks {
            margin-top: 20px;
        }

        .doctor-remarks textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
            resize: vertical;
        }

        .receipt-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
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

    // Check if payment_status column exists in appointment table
    $checkColumn = $database->query("SHOW COLUMNS FROM appointment LIKE 'payment_status'");
    if ($checkColumn->num_rows === 0) {
        // Add payment_status column if it doesn't exist
        $database->query("ALTER TABLE appointment ADD COLUMN payment_status VARCHAR(20) DEFAULT 'Unpaid'");
    }

    
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
                    <td class="menu-btn menu-icon-schedule ">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="appointment.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Appointment Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Asia/Kolkata');

                        $today = date('Y-m-d');
                        echo $today;

                        $list110 = $database->query("select  * from  appointment;");

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                <!-- <tr>
                    <td colspan="4" >
                        <div style="display: flex;margin-top: 40px;">
                        <div class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49);margin-top: 5px;">Schedule a Session</div>
                        <a href="?action=add-session&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add a Session</font></button>
                        </a>
                        </div>
                    </td>
                </tr> -->
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Appointments (<?php echo $list110->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">

                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            
                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">

                        </td>
                        <td width="5%" style="text-align: center;">
                        Doctor:
                        </td>
                        <td width="30%">
                        <select name="docid" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                            <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>
                                
                            <?php 
                             
                                $list11 = $database->query("select  * from  doctor order by docname asc;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $sn=$row00["docname"];
                                    $id00=$row00["docid"];
                                    echo "<option value=".$id00.">$sn</option><br/>";
                                };


                                ?>

                        </select>
                    </td>
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
                            </table>

                        </center>
                    </td>
                    
                </tr>
                
                <?php
                    if($_POST){
                        //print_r($_POST);
                        $sqlpt1="";
                        if(!empty($_POST["sheduledate"])){
                            $sheduledate=$_POST["sheduledate"];
                            $sqlpt1=" schedule.scheduledate='$sheduledate' ";
                        }


                        $sqlpt2="";
                        if(!empty($_POST["docid"])){
                            $docid=$_POST["docid"];
                            $sqlpt2=" doctor.docid=$docid ";
                        }
                        //echo $sqlpt2;
                        //echo $sqlpt1;
                        $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate,COALESCE(appointment.payment_status, 'Unpaid') as payment_status from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid";
                        $sqllist=array($sqlpt1,$sqlpt2);
                        $sqlkeywords=array(" where "," and ");
                        $key2=0;
                        foreach($sqllist as $key){

                            if(!empty($key)){
                                $sqlmain.=$sqlkeywords[$key2].$key;
                                $key2++;
                            };
                        };
                        //echo $sqlmain;

                        
                        
                        //
                    }else{
                        $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate,COALESCE(appointment.payment_status, 'Unpaid') as payment_status from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid order by schedule.scheduledate desc";

                    }



                ?>
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                        <tr>
                                <th class="table-headin">
                                    Patient name
                                </th>
                                <th class="table-headin">
                                    
                                    Appointment number
                                    
                                </th>
                               
                                
                                <th class="table-headin">
                                    Doctor
                                </th>
                                <th class="table-headin">
                                    
                                
                                    Session Title
                                    
                                    </th>
                                
                                <th class="table-headin" style="font-size:10px">
                                    
                                    Session Date & Time
                                    
                                </th>
                                
                                <th class="table-headin">
                                    
                                    Appointment Date
                                    
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
                                    <td colspan="7">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                    <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $appoid=$row["appoid"];
                                    $scheduleid=$row["scheduleid"];
                                    $title=$row["title"];
                                    $docname=$row["docname"];
                                    $scheduledate=$row["scheduledate"];
                                    $scheduletime=$row["scheduletime"];
                                    $pname=$row["pname"];
                                    $apponum=$row["apponum"];
                                    $appodate=$row["appodate"];
                                    echo '<tr >
                                        <td style="font-weight:600;"> &nbsp;'.
                                        
                                        substr($pname,0,25)
                                        .'</td >
                                        <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">
                                        '.$apponum.'
                                        
                                        </td>
                                        <td>
                                        '.substr($docname,0,25).'
                                        </td>
                                        <td>
                                        '.substr($title,0,15).'
                                        </td>
                                        <td style="text-align:center;font-size:12px;">
                                            '.substr($scheduledate,0,10).' <br>'.substr($scheduletime,0,5).'
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            '.$appodate.'
                                        </td>

                                        <td>
                                        <div style="display:flex;justify-content: center;">
                                        
                                        <!--<a href="?action=view&id='.$appoid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                       &nbsp;&nbsp;&nbsp;-->
                                       <a href="?action=drop&id='.$appoid.'&name='.$pname.'&session='.$title.'&apponum='.$apponum.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel</font></button></a>
                                       &nbsp;&nbsp;&nbsp;
                                        
        <button class="btn-primary-soft btn button-icon btn-pay" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;" 
                onclick="showReceiptModal('.$appoid.', \''.$pname.'\', \''.$docname.'\', \''.$title.'\')" 
                '.(isset($row["payment_status"]) && $row["payment_status"] == "Paid" ? 'disabled' : '').'>
            <i class="fas fa-money-bill-wave" style="margin-right: 8px;"></i>
            <font class="tn-in-text">'.(isset($row["payment_status"]) && $row["payment_status"] == "Paid" ? 'Paid' : 'Pay').'</font>
        </button>
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
                       
                <?php
                    // Example patient data for the sake of demo
                    // In a real case, these would be dynamic values fetched from the database.
                    $patients = [
                        ['id' => 1, 'name' => 'John Doe', 'email' => 'johndoe@example.com', 'remarks' => 'Routine Checkup'],
                        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'janesmith@example.com', 'remarks' => 'Follow-up Consultation'],
                    ];
                ?>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Patients (<?php echo count($patients); ?>)</p>
                    </td>
                </tr>
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($patients as $patient) {
                                    echo '<tr>
                                        <td>' . $patient['name'] . '</td>
                                        <td>' . $patient['email'] . '</td>
                                        <td>' . $patient['remarks'] . '</td>
                                        <td>
                                            <button class="pay-btn" data-id="' . $patient['id'] . '" data-name="' . $patient['name'] . '" data-remarks="' . $patient['remarks'] . '">Pay</button>
                                        </td>
                                    </tr>';
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
     <!-- Receipt Modal -->
     <div id="receiptModal" class="modal">
        <div class="modal-content">
            <div class="receipt-header">
                <h2>Healthserv Los Baños Medical Center</h2>
                <p>Address: 3817 National Highway, Los Baños, Laguna</p>
                <p>Phone: [Phone Number]</p>
                <p><strong>Official Receipt</strong></p>
            </div>
            <div class="receipt-body">
                <div class="receipt-info">
                    <p><strong>Patient Name:</strong> <span id="receiptPatientName"></span></p>
                    <p><strong>Doctor:</strong> <span id="receiptDoctorName"></span></p>
                    <p><strong>Service:</strong> <span id="receiptService"></span></p>
                    <p><strong>Date:</strong> <span id="receiptDate"></span></p>
                </div>
                <div class="payment-details">
                    <div class="payment-type">
                        <label><strong>Payment Type:</strong></label>
                        <div class="radio-group">
                            <input type="radio" id="cash" name="paymentType" value="Cash" checked>
                            <label for="cash">Cash</label>
                            <input type="radio" id="cashless" name="paymentType" value="Cashless">
                            <label for="cashless">Cashless</label>
                        </div>
                    </div>
                    <div class="amount-input">
                        <label for="amount"><strong>Amount:</strong></label>
                        <input type="number" id="amount" placeholder="Enter amount" required>
                    </div>
                </div>
                <div class="doctor-remarks">
                    <label for="remarks"><strong>Doctor's Remarks:</strong></label>
                    <textarea id="remarks" rows="3" placeholder="Doctor's remarks will appear here if available" readonly style="background-color: #f9f9f9;"></textarea>
                    <p class="small text-muted" style="margin-top:5px; font-size:12px; color:#666;">Note: Only doctors can add remarks. Admin cannot modify these remarks.</p>
                </div>
                <div class="admin-signature" style="margin-top: 20px; display: none;" id="signatureSection">
                    <div style="display: flex; justify-content: flex-end; margin-top: 30px;">
                        <div style="text-align: center; width: 200px;">
                            <div style="border-top: 1px solid #000; margin-top: 20px;"></div>
                            <p style="margin: 5px 0 0 0;">Administrator Signature</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="receipt-footer">
                <button id="submitPayment" class="btn-primary">Submit Payment</button>
                <button id="printReceipt" class="btn-primary" style="display:none;">Print Receipt</button>
                <button id="downloadPDF" class="btn-primary" style="display:none; background-color: #2196F3;">Download PDF</button>
                <button id="closeModal" class="btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('submitPayment').addEventListener('click', function() {
        let amount = document.getElementById('amount').value;
        let paymentType = document.querySelector('input[name="paymentType"]:checked').value;
        let remarks = document.getElementById('remarks').value;
        let appoid = document.getElementById('receiptModal').dataset.appoid;

        if (!amount || amount <= 0) {
            alert("Please enter a valid amount.");
            return;
        }

        // Create form data
        let formData = new FormData();
        formData.append('appoid', appoid);
        formData.append('amount', amount);
        formData.append('payment_type', paymentType);
        formData.append('remarks', remarks);

        // Disable submit button and show loading state
        let submitButton = document.getElementById('submitPayment');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Submit payment via AJAX
        fetch('process_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Show print button, download button and signature section
                document.getElementById('printReceipt').style.display = 'inline-block';
                document.getElementById('downloadPDF').style.display = 'inline-block';
                document.getElementById('submitPayment').style.display = 'none';
                document.getElementById('signatureSection').style.display = 'block';
                
                // Disable form inputs to make it appear as a receipt
                document.querySelectorAll('#receiptModal input, #receiptModal textarea').forEach(input => {
                    input.readOnly = true;
                    if (input.type === 'radio' && !input.checked) {
                        input.disabled = true;
                    }
                });
                
                alert("Payment submitted successfully!");
                // Update the pay button in the main table
                let payButton = document.querySelector(`button[onclick*="showReceiptModal(${appoid}"]`);
                if (payButton) {
                    payButton.disabled = true;
                    payButton.querySelector('.tn-in-text').textContent = 'Paid';
                }
            } else {
                throw new Error(data.message || 'Payment processing failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error processing payment: " + error.message);
            // Reset submit button
            submitButton.disabled = false;
            submitButton.innerHTML = 'Submit Payment';
        });
    });

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
                    .admin-signature {
                        margin-top: 50px;
                    }
                    .signature-line {
                        border-top: 1px solid #000;
                        width: 200px;
                        margin-top: 20px;
                        margin-left: auto;
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
        const appoid = document.getElementById('receiptModal').dataset.appoid;
        const patientName = document.getElementById('receiptPatientName').textContent;
        const doctorName = document.getElementById('receiptDoctorName').textContent;
        const service = document.getElementById('receiptService').textContent;
        const date = document.getElementById('receiptDate').textContent;
        const amount = document.getElementById('amount').value;
        const paymentType = document.querySelector('input[name="paymentType"]:checked').value;
        
        // Create form data with all receipt details
        let formData = new FormData();
        formData.append('appoid', appoid);
        formData.append('patientName', patientName);
        formData.append('doctorName', doctorName);
        formData.append('service', service);
        formData.append('date', date);
        formData.append('amount', amount);
        formData.append('paymentType', paymentType);
        formData.append('remarks', document.getElementById('remarks').value);
        
        // Submit to PDF generation script
        fetch('generate_receipt_pdf.php', {
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
            a.download = `Receipt_${patientName.replace(/\s+/g, '_')}_${date.replace(/\//g, '-')}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => {
            console.error('Error generating PDF:', error);
            alert("Error generating PDF. Please try again.");
        });
    });

    function showReceiptModal(appoid, patientName, doctorName, service) {
        document.getElementById('receiptModal').style.display = 'block';
        document.getElementById('receiptModal').dataset.appoid = appoid;
        document.getElementById('receiptPatientName').textContent = patientName;
        document.getElementById('receiptDoctorName').textContent = doctorName;
        document.getElementById('receiptService').textContent = service;
        document.getElementById('receiptDate').textContent = new Date().toLocaleDateString();
        
        // Reset form state
        document.getElementById('submitPayment').style.display = 'inline-block';
        document.getElementById('printReceipt').style.display = 'none';
        document.getElementById('downloadPDF').style.display = 'none';
        document.getElementById('signatureSection').style.display = 'none';
        document.getElementById('amount').value = '';
        document.getElementById('amount').readOnly = false;
        
        // Re-enable radio buttons
        document.querySelectorAll('input[name="paymentType"]').forEach(input => {
            input.disabled = false;
        });
        
        // Fetch doctor's remarks if available
        fetch('get_doctor_remarks.php?appoid=' + appoid)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.remarks) {
                    document.getElementById('remarks').value = data.remarks;
                } else {
                    document.getElementById('remarks').value = 'No remarks from doctor available.';
                }
            })
            .catch(error => {
                console.error('Error fetching doctor remarks:', error);
                document.getElementById('remarks').value = 'Error loading remarks.';
            });
    }

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('receiptModal').style.display = 'none';
    });
    </script>
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='add-session'){

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    
                    
                        <a class="close" href="schedule.php">&times;</a> 
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                                <td class="label-td" colspan="2">'.
                                   ""
                                
                                .'</td>
                            </tr>

                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Session.</p><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <form action="add-session.php" method="POST" class="add-new-form">
                                    <label for="title" class="form-label">Session Title : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="title" class="input-text" placeholder="Name of this Session" required><br>
                                </td>
                            </tr>
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="docid" class="form-label">Select Doctor: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="docid" id="" class="box" >
                                    <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>';
                                        
        
                                        $list11 = $database->query("select  * from  doctor;");
        
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            $sn=$row00["docname"];
                                            $id00=$row00["docid"];
                                            echo "<option value=".$id00.">$sn</option><br/>";
                                        };
        
        
        
                                        
                        echo     '       </select><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nop" class="form-label">Number of Patients/Appointment Numbers : </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="number" name="nop" class="input-text" min="0"  placeholder="The final appointment number for this session depends on this number" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="date" class="form-label">Session Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="date" name="date" class="input-text" min="'.date('Y-m-d').'" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="time" class="form-label">Schedule Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="time" name="time" class="input-text" placeholder="Time" required><br>
                                </td>
                            </tr>
                           
                            <tr>
                                <td colspan="2">
                                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                    <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
                                </td>
                
                            </tr>
                           
                            </form>
                            </tr>
                        </table>
                        </div>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }elseif($action=='session-added'){
            $titleget=$_GET["title"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Session Placed.</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                        '.substr($titleget,0,40).' was scheduled.<br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="schedule.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        }elseif($action=='drop'){
            $nameget=$_GET["name"];
            $session=$_GET["session"];
            $apponum=$_GET["apponum"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br><br>
                            Patient Name: &nbsp;<b>'.substr($nameget,0,40).'</b><br>
                            Appointment number &nbsp; : <b>'.substr($apponum,0,40).'</b><br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            '; 
        }elseif($action=='view'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $spcil_res= $database->query("select sname from specialties where id='$spe'");
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
           
            $tele=$row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            eDoc Web App<br>
                            
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
                                    <label for="spec" class="form-label">Specialties: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            '.$spcil_name.'<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}

    ?>
    </div>


</body>
</html>