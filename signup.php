<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Sign Up</title>
</head>
<body>
<?php
session_start();

$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

if ($_POST) {
    $_SESSION["personal"] = array(
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'address' => $_POST['address'],
        'gender' => $_POST['gender'], // Save Gender
        'dob' => $_POST['dob'],
        'mobile' => $_POST['mobile']  // Save Mobile Number
    );

    print_r($_SESSION["personal"]);
    header("location: create-account.php");
}
?>

<center>
    <div class="container">
        <table border="0">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">Add Your Personal Details to Continue</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST">
                <td class="label-td" colspan="2">
                    <label for="name" class="form-label">Name: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <input type="text" name="fname" class="input-text" placeholder="First Name" required>
                </td>
                <td class="label-td">
                    <input type="text" name="lname" class="input-text" placeholder="Last Name" required>
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="address" class="form-label">Address: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="text" name="address" class="input-text" placeholder="Address" required>
                </td>
            </tr>

            <!-- Added Gender Field -->
            <tr>
                <td class="label-td" colspan="2">
                    <label for="gender" class="form-label">Gender: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <select name="gender" class="input-text" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                       
                    </select>
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="dob" class="form-label">Date of Birth: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="date" name="dob" class="input-text" required>
                </td>
            </tr>

            <!-- Mobile Number Input with 11 digits limit -->
            <tr>
                <td class="label-td" colspan="2">
                    <label for="mobile" class="form-label">Mobile Number: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="text" name="mobile" class="input-text" placeholder="Mobile Number" required
                           pattern="^\d{11}$" 
                           title="Please enter exactly 11 digits." maxlength="11" minlength="11" oninput="validateMobile(this)">
                </td>
            </tr>

            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </td>
                <td>
                    <input type="submit" value="Next" class="login-btn btn-primary btn">
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>
                </form>
            </tr>
        </table>
    </div>
</center>

<script>
    // JavaScript to restrict input to digits only
    function validateMobile(input) {
        input.value = input.value.replace(/[^0-9]/g, ''); // Replace any non-digit characters with an empty string
    }
</script>

</body>
</html>
