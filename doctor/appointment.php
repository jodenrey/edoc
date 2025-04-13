<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        /* Modal styles */  
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
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .checkup-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        .checkup-section {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .form-row {
            display: flex;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .form-group {
            margin-right: 20px;
            margin-bottom: 10px;
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-close {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-submit:hover, .btn-close:hover {
            opacity: 0.9;
        }
</style>
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    
    

       //import database
       include("../connection.php");
       $userrow = $database->query("select * from doctor where docemail='$useremail'");
       $userfetch=$userrow->fetch_assoc();
       $userid= $userfetch["docid"];
       $username=$userfetch["docname"];
    //echo $userid;
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
                    <td class="menu-btn menu-icon-dashbord " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">My Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
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

                        $list110 = $database->query("select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ");

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
                        </div>
                    </td>
                </tr> -->
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My Appointments (<?php echo $list110->num_rows; ?>)</p>
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


                    $sqlmain= "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ";

                    if($_POST){
                        //print_r($_POST);
                        


                        
                        if(!empty($_POST["sheduledate"])){
                            $sheduledate=$_POST["sheduledate"];
                            $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
                        };

                        

                        //echo $sqlmain;

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
                                    
                                
                                    Session Title
                                    
                                    </th>
                                
                                <th class="table-headin" >
                                    
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
                                        '.substr($title,0,15).'
                                        </td>
                                        <td style="text-align:center;;">
                                            '.substr($scheduledate,0,10).' @'.substr($scheduletime,0,5).'
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            '.$appodate.'
                                        </td>

                                        <td>
                                        <div style="display:flex;justify-content: center;">
                                        
                                       <a href="?action=drop&id='.$appoid.'&name='.$pname.'&session='.$title.'&apponum='.$apponum.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel</font></button></a>
                                       &nbsp;&nbsp;&nbsp;
                                         <!-- Check Up Form button -->
          <button onclick="showCheckupModal('.$appoid.', \''.$pname.'\', \''.$title.'\')" class="btn-primary-soft btn button-icon btn-form" style="padding-left: 10px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
              <i class="fas fa-file-alt" style="margin-right: 8px;"></i>
              <font class="tn-in-text">Check Up Form</font>
          </button>
        &nbsp;&nbsp;&nbsp;

        <!-- Done button with finish icon, with added space between Form and Done -->
<a href="patient.php?patientid=<?php echo $appoid; ?>&action=done" class="non-style-link">
    <button class="btn-primary-soft btn button-icon btn-done" style="padding-left: 10px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px; margin-left: 10px;">
        <i class="fas fa-check-circle" style="margin-right: 8px;"></i> <!-- Check icon -->
        <font class="tn-in-text">Done</font>
    </button>
</a>
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

    <!-- Checkup Form Modal -->
    <div id="checkupModal" class="modal">
        <div class="modal-content">
            <div class="checkup-header">
                <h2 style="text-align:center; margin:5px 0;">CHECK UP FORM</h2>
                <div style="text-align:right; margin:5px 0;">
                    <span>PLEASE PRINT</span>
                    <div style="margin-top:5px;">
                        <input type="checkbox" id="periodic"> <label for="periodic">Periodic</label>
                        <input type="checkbox" id="interpPeriodic"> <label for="interpPeriodic">Interperiodic</label>
                        <input type="checkbox" id="parentRequest"> <label for="parentRequest">Parent/Caregiver Request</label>
                    </div>
                </div>
            </div>
            
            <form id="checkupForm">
                <input type="hidden" id="appoid" name="appoid">
                
                <!-- PERSONAL -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">PERSONAL</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td colspan="2" style="border:1px solid #ddd; padding:5px;">
                                <label>NAME</label>
                                <div style="display:flex;">
                                    <div style="flex:1;">
                                        <input type="text" id="patientName" name="patientName" placeholder="(Last)" readonly>
                                    </div>
                                    <div style="flex:1;">
                                        <input type="text" id="firstName" name="firstName" placeholder="(First)">
                                    </div>
                                </div>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>ID</label>
                                <input type="text" id="patientId" name="patientId">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>DATE OF BIRTH</label>
                                <input type="date" id="dob" name="dob">
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>DATE</label>
                                <input type="text" id="checkupDate" name="checkupDate" readonly>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>AGE</label>
                                <input type="text" id="patientAge" name="patientAge">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>ACCOMPANIED BY</label>
                                <input type="text" id="accompaniedBy" name="accompaniedBy">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <label>RELATIONSHIP</label>
                                <input type="text" id="relationship" name="relationship">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- INTERVAL HISTORY -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">INTERVAL HISTORY</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <div>PAST MEDICAL HISTORY WNL</div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="pastMedical" id="pastMedicalYes" value="Yes"> <label for="pastMedicalYes">YES</label>
                                    <input type="radio" name="pastMedical" id="pastMedicalNo" value="No"> <label for="pastMedicalNo">NO</label>
                                    <span>(IF NO, DESCRIBE)</span>
                                </div>
                                <textarea id="pastMedicalDesc" name="pastMedicalDesc" rows="1" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <div>DEVELOPMENTAL HISTORY WNL</div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="developmentalHistory" id="developmentalYes" value="Yes"> <label for="developmentalYes">YES</label>
                                    <input type="radio" name="developmentalHistory" id="developmentalNo" value="No"> <label for="developmentalNo">NO</label>
                                    <span>(IF NO, DESCRIBE)</span>
                                </div>
                                <textarea id="developmentalDesc" name="developmentalDesc" rows="1" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <div>BEHAVIORAL HEALTH STATUS WNL</div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="behavioralHealth" id="behavioralYes" value="Yes"> <label for="behavioralYes">YES</label>
                                    <input type="radio" name="behavioralHealth" id="behavioralNo" value="No"> <label for="behavioralNo">NO</label>
                                    <span>(IF NO, DESCRIBE)</span>
                                </div>
                                <textarea id="behavioralDesc" name="behavioralDesc" rows="1" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- NUTRITIONAL ASSESSMENT -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">NUTRITIONAL ASSESSMENT</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:60%;">
                                <div>WNL</div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="nutritional" id="nutritionalYes" value="Yes"> <label for="nutritionalYes">YES</label>
                                    <input type="radio" name="nutritional" id="nutritionalNo" value="No"> <label for="nutritionalNo">NO</label>
                                    <span>(IF NO, DESCRIBE)</span>
                                </div>
                                <textarea id="nutritionalDesc" name="nutritionalDesc" rows="1" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:20%; text-align:center;">
                                <input type="checkbox" id="fluoride"> <label for="fluoride">FLUORIDE</label>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:20%; text-align:center;">
                                <input type="checkbox" id="referred"> <label for="referred">REFERRED</label>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- PHYSICAL EXAM -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">PHYSICAL EXAM</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <label>HEIGHT</label>
                                <input type="text" id="height" name="height">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <label>WEIGHT</label>
                                <input type="text" id="weight" name="weight">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <label>BLOOD PRESSURE</label>
                                <input type="text" id="bloodPressure" name="bloodPressure">
                            </td>
                        </tr>
                    </table>
                    
                    <table style="width:100%; border-collapse:collapse; margin-top:10px;">
                        <tr style="background-color:#f5f5f5;">
                            <td style="border:1px solid #ddd; padding:5px; width:30%;">Are the following normal?</td>
                            <td style="border:1px solid #ddd; padding:5px; width:10%; text-align:center;">YES</td>
                            <td style="border:1px solid #ddd; padding:5px; width:10%; text-align:center;">NO</td>
                            <td style="border:1px solid #ddd; padding:5px; width:50%; text-align:center;">COMMENTS</td>
                        </tr>
                        
                        <!-- Physical exam rows -->
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Appearance</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="appearance" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="appearance" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="appearanceComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Skin</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="skin" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="skin" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="skinComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Head</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="head" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="head" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="headComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Eyes</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="eyes" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="eyes" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="eyesComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Ears</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="ears" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="ears" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="earsComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Nose</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="nose" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="nose" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="noseComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Mouth/Throat/Teeth/Gums</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="mouth" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="mouth" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <input type="text" style="width:90%;" name="mouthComments">
                                <div style="margin-top:5px;"><input type="checkbox" id="dentalReferral"> <label for="dentalReferral">DENTAL REFERRAL AGE 3 AND UP REQUIRED</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Nodes</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="nodes" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="nodes" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="nodesComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Heart</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="heart" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="heart" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="heartComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Lungs</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="lungs" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="lungs" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="lungsComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Abdomen</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="abdomen" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="abdomen" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="abdomenComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Fem. Pulse</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="femPulse" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="femPulse" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="femPulseComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Ext. Gen.</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="extGen" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="extGen" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="extGenComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Extremities</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="extremities" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="extremities" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="extremitiesComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Spine</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="spine" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="spine" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="spineComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Neuro</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="neuro" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="neuro" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="neuroComments"></td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px;">Other</td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="other" value="Yes"></td>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;"><input type="radio" name="other" value="No"></td>
                            <td style="border:1px solid #ddd; padding:5px;"><input type="text" style="width:100%;" name="otherComments"></td>
                        </tr>
                    </table>
                </div>
                
                <!-- LAB TESTS -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">LAB TESTS</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <input type="checkbox" id="ua"> <label for="ua">UA ___ (if pos & as indicated)</label>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <input type="checkbox" id="leadScreen"> <label for="leadScreen">LEAD SCREEN (blood) @ 12 & 24 mo. @ 3%<br>if high risk exposure urine @ 3-6 mo.)</label>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:33%;">
                                <input type="checkbox" id="other"> <label for="other">OTHER (specify as indicated)</label>
                                <input type="text" style="width:100%; margin-top:5px;">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- SENSORY SCREEN -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">SENSORY SCREEN</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <div>VISION:</div>
                                <div style="margin-top:5px;">
                                    <input type="checkbox" id="normalVision"> <label for="normalVision">NORMAL</label>
                                    <input type="checkbox" id="abnormalVision"> <label for="abnormalVision">ABNORMAL</label>
                                    <input type="checkbox" id="referredVision"> <label for="referredVision">REFERRED</label>
                                </div>
                                <div style="margin-top:5px;">
                                    RIGHT _____ LEFT _____ BOTH _____
                                </div>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <div>HEARING:</div>
                                <div style="margin-top:5px;">
                                    <input type="checkbox" id="normalHearing"> <label for="normalHearing">NORMAL</label>
                                    <input type="checkbox" id="abnormalHearing"> <label for="abnormalHearing">ABNORMAL</label>
                                    <input type="checkbox" id="referredHearing"> <label for="referredHearing">REFERRED</label>
                                </div>
                                <div style="margin-top:5px;">
                                    RIGHT _____ LEFT _____
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border:1px solid #ddd; padding:5px;">
                                <div>DOES PARENT FEEL SPEECH & HEARING ARE NORMAL FOR AGE?</div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="speechHearing" id="speechHearingYes" value="Yes"> <label for="speechHearingYes">YES</label>
                                    <input type="radio" name="speechHearing" id="speechHearingNo" value="No"> <label for="speechHearingNo">NO</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- DEVELOPMENT ASSESSMENT -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">DEVELOPMENT ASSESSMENT</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <div><input type="checkbox" id="developmentNormal"> <label for="developmentNormal">DEVELOPMENT NORMAL FOR AGE AND CULTURE?</label></div>
                                <div style="margin-top:5px;">
                                    <input type="radio" name="development" id="developmentYes" value="Yes"> <label for="developmentYes">YES</label>
                                    <input type="radio" name="development" id="developmentNo" value="No"> <label for="developmentNo">NO</label>
                                </div>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:50%; vertical-align:top;">
                                <div><strong>DIAGNOSIS:</strong></div>
                                <textarea id="diagnosis" name="diagnosis" rows="4" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- IMMUNIZATIONS -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">IMMUNIZATIONS</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <input type="checkbox" id="current"> <label for="current">CURRENT</label>
                                <input type="checkbox" id="deferred"> <label for="deferred">DEFERRED</label>
                                <input type="checkbox" id="provided"> <label for="provided">PROVIDED: LIST</label>
                                <input type="text" style="width:100%; margin-top:5px;">
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:50%; vertical-align:top;">
                                <div><strong>PLAN:</strong></div>
                                <textarea id="plan" name="plan" rows="4" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- HEALTH EDUCATION -->
                <div class="checkup-section">
                    <h4 style="margin:5px 0;">HEALTH EDUCATION, ANTICIPATORY GUIDANCE</h4>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <div style="margin:5px 0;"><input type="checkbox" id="dental"> <label for="dental">DENTAL</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="nutrition"> <label for="nutrition">NUTRITION</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="regularActivity"> <label for="regularActivity">REGULAR PHYSICAL ACTIVITY</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="safety"> <label for="safety">SAFETY: WATER, SEAT BELTS, SKATE BOARD, BICYCLE</label></div>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px; width:50%;">
                                <div style="margin:5px 0;"><input type="checkbox" id="peerRelations"> <label for="peerRelations">PEER RELATIONS</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="communication"> <label for="communication">COMMUNICATION</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="parentalRole"> <label for="parentalRole">PARENTAL ROLE MODEL</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="schoolPerformance"> <label for="schoolPerformance">SCHOOL PERFORMANCE</label></div>
                                <div style="margin:5px 0;"><input type="checkbox" id="limitSetting"> <label for="limitSetting">LIMIT SETTING</label></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:5px; text-align:center;">
                                <div><strong>SIGNATURE:</strong></div>
                                <div style="margin-top:30px; border-top:1px solid #000; display:inline-block; width:200px;"></div>
                            </td>
                            <td style="border:1px solid #ddd; padding:5px;">
                                <!-- Doctor's Remarks that will be saved to database -->
                                <div><strong>DOCTOR'S REMARKS (visible to admin):</strong></div>
                                <textarea id="doctorRemarks" name="doctorRemarks" rows="4" style="width:100%; margin-top:5px;"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="form-footer">
                    <button type="button" id="closeCheckupModal" class="btn-close">Close</button>
                    <button type="button" id="submitCheckupForm" class="btn-submit">Submit Remarks</button>
                    <button type="button" id="printCheckupForm" class="btn-submit" style="margin-right: 10px; background-color: #2196F3;">Print Form</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Function to show the checkup modal
    function showCheckupModal(appoid, patientName, appointmentType) {
        document.getElementById('checkupModal').style.display = 'block';
        document.getElementById('appoid').value = appoid;
        document.getElementById('patientName').value = patientName;
        document.getElementById('checkupDate').value = new Date().toLocaleDateString();
        
        // Load any existing form data for this appointment
        loadCheckupData(appoid);
    }
    
    // Function to load existing checkup data
    function loadCheckupData(appoid) {
        // Create form data for the request
        let formData = new FormData();
        formData.append('appoid', appoid);
        formData.append('action', 'load_checkup');
        
        // Show loading indicator
        const formFields = document.getElementById('checkupForm').elements;
        for (let i = 0; i < formFields.length; i++) {
            if (formFields[i].type !== 'button') {
                formFields[i].disabled = true;
            }
        }
        
        // Fetch existing data
        fetch('../load_checkup_data.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.checkupData) {
                // Populate form fields with the retrieved data
                const checkupData = data.checkupData;
                
                // Populate text inputs and textareas
                for (const field in checkupData) {
                    const element = document.getElementById(field);
                    if (element && (element.type === 'text' || element.tagName === 'TEXTAREA')) {
                        element.value = checkupData[field];
                    }
                }
                
                // Handle radio buttons
                for (const field in checkupData) {
                    if (field.endsWith('Yes') || field.endsWith('No')) {
                        const radioGroup = field.replace(/Yes|No/, '');
                        const value = checkupData[field] === 'true' ? (field.endsWith('Yes') ? 'Yes' : 'No') : null;
                        if (value) {
                            const radio = document.querySelector(`input[name="${radioGroup}"][value="${value}"]`);
                            if (radio) radio.checked = true;
                        }
                    }
                }
                
                // Handle checkboxes
                for (const field in checkupData) {
                    const element = document.getElementById(field);
                    if (element && element.type === 'checkbox') {
                        element.checked = checkupData[field] === 'true';
                    }
                }
                
                // Handle comments fields
                document.querySelectorAll('input[name$="Comments"]').forEach(input => {
                    const fieldName = input.name;
                    if (checkupData[fieldName]) {
                        input.value = checkupData[fieldName];
                    }
                });
            } else {
                console.log('No existing data found or error loading data');
            }
        })
        .catch(error => {
            console.error('Error loading checkup data:', error);
        })
        .finally(() => {
            // Re-enable form fields
            for (let i = 0; i < formFields.length; i++) {
                if (formFields[i].type !== 'button') {
                    formFields[i].disabled = false;
                }
            }
        });
    }

    // Close modal when close button is clicked
    document.getElementById('closeCheckupModal').addEventListener('click', function() {
        document.getElementById('checkupModal').style.display = 'none';
    });

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        var modal = document.getElementById('checkupModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Handle print button
    document.getElementById('printCheckupForm').addEventListener('click', function() {
        // Create a new window for printing
        let printContent = document.querySelector('.modal-content').cloneNode(true);
        
        // Remove buttons from print view - fixed removeChild issue
        let footer = printContent.querySelector('.form-footer');
        if (footer && footer.parentNode) {
            footer.parentNode.removeChild(footer);
        }
        
        let printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Check Up Form</title>');
        printWindow.document.write('<link rel="stylesheet" href="../css/main.css">');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; margin: 20px; }
            .modal-content { width: 100%; box-shadow: none; }
            .checkup-header { text-align: center; margin-bottom: 20px; }
            .checkup-section { margin: 10px 0; }
            h4 { margin: 5px 0; }
            table { border-collapse: collapse; width: 100%; }
            td { border: 1px solid #ddd; padding: 5px; }
            input[type="text"], textarea { width: 100%; padding: 4px; box-sizing: border-box; }
            @media print {
                .modal-content { border: none; }
                input[type="radio"], input[type="checkbox"] { -webkit-appearance: none; appearance: none; }
                input[type="radio"]:checked, input[type="checkbox"]:checked { 
                    content: '✓';
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    background: #000;
                    border-radius: 50%;
                }
            }
        `);
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
        };
    });

    // Handle form submission - only saves the doctor remarks
    document.getElementById('submitCheckupForm').addEventListener('click', function() {
        // Get form data
        let appoid = document.getElementById('appoid').value;
        let remarks = document.getElementById('doctorRemarks').value;
        
        if (!remarks.trim()) {
            alert("Please enter doctor's remarks before submitting.");
            return;
        }
        
        // Create form data object for AJAX request
        let formData = new FormData();
        formData.append('appoid', appoid);
        formData.append('doctorRemarks', remarks);
        formData.append('action', 'save_remarks');
        
        // Collect all form fields to save comprehensive checkup data
        // Personal section
        formData.append('patientName', document.getElementById('patientName').value.trim());
        formData.append('firstName', document.getElementById('firstName').value.trim());
        formData.append('patientId', document.getElementById('patientId').value.trim());
        formData.append('dob', document.getElementById('dob').value.trim());
        formData.append('patientAge', document.getElementById('patientAge').value.trim());
        formData.append('accompaniedBy', document.getElementById('accompaniedBy').value.trim());
        formData.append('relationship', document.getElementById('relationship').value.trim());
        
        // Physical Exam section
        formData.append('height', document.getElementById('height').value.trim());
        formData.append('weight', document.getElementById('weight').value.trim());
        formData.append('bloodPressure', document.getElementById('bloodPressure').value.trim());
        
        // Medical History 
        const pastMedical = document.querySelector('input[name="pastMedical"]:checked');
        formData.append('pastMedical', pastMedical ? pastMedical.value : '');
        formData.append('pastMedicalDesc', document.getElementById('pastMedicalDesc').value);
        
        // Developmental History
        const developmentalHistory = document.querySelector('input[name="developmentalHistory"]:checked');
        formData.append('developmentalHistory', developmentalHistory ? developmentalHistory.value : '');
        formData.append('developmentalDesc', document.getElementById('developmentalDesc').value);
        
        // Behavioral Health
        const behavioralHealth = document.querySelector('input[name="behavioralHealth"]:checked');
        formData.append('behavioralHealth', behavioralHealth ? behavioralHealth.value : '');
        formData.append('behavioralDesc', document.getElementById('behavioralDesc').value);
        
        // Nutritional Assessment
        const nutritional = document.querySelector('input[name="nutritional"]:checked');
        formData.append('nutritional', nutritional ? nutritional.value : '');
        formData.append('nutritionalDesc', document.getElementById('nutritionalDesc').value);
        
        // Diagnosis & Plan
        formData.append('diagnosis', document.getElementById('diagnosis').value);
        formData.append('plan', document.getElementById('plan').value);
        
        // All Physical Exam Sections (collect all radio buttons)
        document.querySelectorAll('input[type="radio"]:checked').forEach(function(radio) {
            if (radio.name !== 'pastMedical' && radio.name !== 'developmentalHistory' && 
                radio.name !== 'behavioralHealth' && radio.name !== 'nutritional') {
                formData.append(radio.name, radio.value);
                
                // Also collect corresponding comments if any
                const commentInput = document.querySelector(`input[name="${radio.name}Comments"]`);
                if (commentInput) {
                    formData.append(`${radio.name}Comments`, commentInput.value);
                }
            }
        });
        
        // Collect checkbox values
        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            formData.append(checkbox.id, checkbox.checked);
        });
        
        // Show loading state
        let submitButton = document.getElementById('submitCheckupForm');
        submitButton.innerHTML = 'Saving...';
        submitButton.disabled = true;
        
        // Send data via AJAX
        fetch('../save_remarks.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Get the raw text first
        })
        .then(text => {
            // Try to parse as JSON, but handle cases where it might not be valid JSON
            try {
                const data = JSON.parse(text);
                if (data.status === 'success') {
                    alert("Check-up form submitted successfully! The patient can now view the form on their dashboard.");
                    document.getElementById('checkupModal').style.display = 'none';
                } else {
                    throw new Error(data.message || 'Error saving check-up form');
                }
            } catch (e) {
                console.error('Error parsing JSON response:', text);
                throw new Error('Invalid response from server. See console for details.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error saving check-up form: " + error.message);
        })
        .finally(() => {
            // Reset submit button
            submitButton.innerHTML = 'Submit Remarks';
            submitButton.disabled = false;
        });
    });
    </script>
</body>
</html>