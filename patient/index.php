<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,.anime{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
    
    
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

   // Check if session data exists and assign values
$fname = isset($_SESSION["personal"]['fname']) ? $_SESSION["personal"]['fname'] : "";
$lname = isset($_SESSION["personal"]['lname']) ? $_SESSION["personal"]['lname'] : "";
$address = isset($_SESSION["personal"]['address']) ? $_SESSION["personal"]['address'] : "";
$gender = isset($_SESSION["personal"]['gender']) ? $_SESSION["personal"]['gender'] : "";
$dob = isset($_SESSION["personal"]['dob']) ? $_SESSION["personal"]['dob'] : "";
$mobile = isset($_SESSION["personal"]['mobile']) ? $_SESSION["personal"]['mobile'] : "";

// Force modal to open if signup data exists
$showModal = (!empty($fname) || !empty($lname)) ? "true" : "false";



    //echo $userid;
    //echo $username;
    
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
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
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
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                        <tr >
                            
                            <td colspan="1" class="nav-bar" >
                            <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Home</p>
                          
                            </td>
                            <td width="25%">

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


                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");


                                ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                            </td>
        
        
                        </tr>
                <tr>
                    <td colspan="4" >
                        
                    <center>
                    <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                    <tr>
                        <td >
                            <h3>Welcome!</h3>
                            <h1><?php echo $username  ?>.</h1>
                            <p>Haven't any idea about doctors? no problem let's jumping to 
                                <a href="doctors.php" class="non-style-link"><b>"All Doctors"</b></a> section or 
                                <a href="schedule.php" class="non-style-link"><b>"Sessions"</b> </a><br>
                                Track your past and future appointments history.<br>Also find out the expected arrival time of your doctor or medical consultant.<br><br>
                            </p>
                            
                            <h3>Channel a Doctor Here</h3>
                            <form action="schedule.php" method="post" style="display: flex">

                                <input type="search" name="search" class="input-text " placeholder="Search Doctor and We will Find The Session Available" list="doctors" style="width:45%;">&nbsp;&nbsp;
                                
                                <?php
                                    echo '<datalist id="doctors">';
                                    $list11 = $database->query("select  docname,docemail from  doctor;");
    
                                    for ($y=0;$y<$list11->num_rows;$y++){
                                        $row00=$list11->fetch_assoc();
                                        $d=$row00["docname"];
                                        
                                        echo "<option value='$d'><br/>";
                                        
                                    };
    
                                echo ' </datalist>';
    ?>
                                
                           
                                <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                            
                            <br>
                            <br>
                            
                        </td>
                    </tr>
                    </table>
                    </center>
                    
                </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table border="0" width="100%"">
                            <tr>
                                <td width="50%">

                                    




                                    <center>
                                        <table class="filter-container" style="border: none;" border="0">
                                            <tr>
                                                <td colspan="4">
                                                    <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Status</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php    echo $doctorrow->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    All Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php    echo $patientrow->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    All Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                                        <div>
                                                                <div class="h1-dashboard" >
                                                                    <?php    echo $appointmentrow ->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard" >
                                                                    NewBooking &nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="margin-left: 0px;background-image: url('../img/icons/book-hover.svg');"></div>
                                                    </div>
                                                    
                                                </td>

                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php    echo $schedulerow ->num_rows  ?>
                                                                </div><br>
                                                                <div class="h3-dashboard" style="font-size: 15px">
                                                                    Today Sessions
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/session-iceblue.svg');"></div>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                            <tr>




                                        </table>
                                    </center>


                                </td>
                                <td>


                            
                                    <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Your Upcoming Booking</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
                                        <thead>
                                            
                                        <tr>
                                        <th class="table-headin">
                                                    
                                                
                                                    Appoint. Number
                                                    
                                                    </th>
                                                <th class="table-headin">
                                                    
                                                
                                                Session Title
                                                
                                                </th>
                                                
                                                <th class="table-headin">
                                                    Doctor
                                                </th>
                                                <th class="table-headin">
                                                    
                                                    Sheduled Date & Time
                                                    
                                                </th>
                                                    
                                                </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                            $nextweek=date("Y-m-d",strtotime("+1 week"));
                                                $sqlmain= "select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid  and schedule.scheduledate>='$today' order by schedule.scheduledate asc";
                                                //echo $sqlmain;
                                                $result= $database->query($sqlmain);
                
                                                if($result->num_rows==0){
                                                    echo '<tr>
                                                    <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                    <img src="../img/notfound.svg" width="25%">
                                                    
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nothing to show here!</p>
                                                    <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Channel a Doctor &nbsp;</font></button>
                                                    </a>
                                                    </center>
                                                    <br><br><br><br>
                                                    </td>
                                                    </tr>';
                                                    
                                                }
                                                else{
                                                for ( $x=0; $x<$result->num_rows;$x++){
                                                    $row=$result->fetch_assoc();
                                                    $scheduleid=$row["scheduleid"];
                                                    $title=$row["title"];
                                                    $apponum=$row["apponum"];
                                                    $docname=$row["docname"];
                                                    $scheduledate=$row["scheduledate"];
                                                    $scheduletime=$row["scheduletime"];
                                                   
                                                    echo '<tr>
                                                        <td style="padding:30px;font-size:25px;font-weight:700;"> &nbsp;'.
                                                        $apponum
                                                        .'</td>
                                                        <td style="padding:20px;"> &nbsp;'.
                                                        substr($title,0,30)
                                                        .'</td>
                                                        <td>
                                                        '.substr($docname,0,20).'
                                                        </td>
                                                        <td style="text-align:center;">
                                                            '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
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
                    </td>
                <tr>
            </table>
            
            <!-- Add the Check-up Forms section -->
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
                <tr>
                    <td colspan="4">
                        <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Your Checkup Forms</p>
                        <center>
                            <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                <table width="85%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Date</th>
                                            <th class="table-headin">Doctor</th>
                                            <th class="table-headin">Session Title</th>
                                            <th class="table-headin">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if checkup_data table exists (this is the new table where form data is stored)
                                        $checkTableQuery = $database->query("SHOW TABLES LIKE 'checkup_data'");
                                        
                                        if ($checkTableQuery->num_rows === 0) {
                                            // Table doesn't exist yet
                                            echo '<tr>
                                                <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                        <img src="../img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No checkup forms available yet!</p>
                                                    </center>
                                                    <br><br><br><br>
                                                </td>
                                            </tr>';
                                        } else {
                                            // Get checkup forms for this patient from checkup_data table
                                            $checkupQuery = "SELECT cd.id, cd.created_at as form_date, d.docname, s.title 
                                                            FROM checkup_data cd 
                                                            INNER JOIN appointment a ON cd.appoid = a.appoid 
                                                            INNER JOIN schedule s ON a.scheduleid = s.scheduleid 
                                                            INNER JOIN doctor d ON s.docid = d.docid 
                                                            WHERE a.pid=$userid 
                                                            ORDER BY cd.created_at DESC";
                                            
                                            $checkupResult = $database->query($checkupQuery);
                                            
                                            if (!$checkupResult || $checkupResult->num_rows == 0) {
                                                echo '<tr>
                                                    <td colspan="4">
                                                        <br><br><br><br>
                                                        <center>
                                                            <img src="../img/notfound.svg" width="25%">
                                                            <br>
                                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">No checkup forms available yet!</p>
                                                        </center>
                                                        <br><br><br><br>
                                                    </td>
                                                </tr>';
                                            } else {
                                                while ($row = $checkupResult->fetch_assoc()) {
                                                    $formId = $row['id'];
                                                    $formDate = date('M d, Y', strtotime($row['form_date']));
                                                    $docName = $row['docname'];
                                                    $sessionTitle = $row['title'];
                                                    
                                                    echo '<tr>
                                                        <td style="padding:20px;font-size:15px;">'.$formDate.'</td>
                                                        <td style="padding:20px;font-size:15px;">'.substr($docName, 0, 20).'</td>
                                                        <td style="padding:20px;font-size:15px;">'.substr($sessionTitle, 0, 30).'</td>
                                                        <td style="padding:20px;font-size:15px;">
                                                            <button class="btn-primary-soft btn button-icon" style="padding:15px; margin:0px;" 
                                                                onclick="event.preventDefault(); event.stopPropagation(); openFormModal('.$formId.'); return false;">
                                                                <font class="tn-in-text">View Form</font>
                                                            </button>
                                                        </td>
                                                    </tr>';
                                                }
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


    <!-- Form Modal -->
    <div id="formModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); overflow:auto;">
        <div class="modal-content" style="background-color:white; margin:5% auto; padding:20px; width:80%; max-width:800px; border-radius:5px; box-shadow:0 4px 8px rgba(0,0,0,0.2); position:relative;">
            <span class="close" onclick="closeFormModal()" style="position:absolute; right:20px; top:10px; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
            <div style="height:500px; margin-top:30px;">
                <iframe id="formFrame" style="width:100%; height:100%; border:none;"></iframe>
            </div>
        </div>
    </div>

    <script>
        // Function to open the form modal
        function openFormModal(formId) {
            // Prevent event propagation
            event.preventDefault();
            event.stopPropagation();
            
            // Display the modal
            var modal = document.getElementById('formModal');
            modal.style.display = 'block';
            
            // Set the iframe source
            var iframe = document.getElementById('formFrame');
            iframe.src = '../patient_form_view.php?id=' + formId + '&modal=true';
            
            return false;
        }

        // Function to close the form modal
        function closeFormModal() {
            document.getElementById('formModal').style.display = 'none';
            document.getElementById('formFrame').src = '';
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('formModal');
            if (event.target == modal) {
                closeFormModal();
            }
        }
    </script>
</body>
</html>