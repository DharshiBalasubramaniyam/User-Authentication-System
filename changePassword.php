<?php

    session_start();
    include "database/dbconnection.php";

    $curpassError = $newpassError = $confirmpassError = $curpass = $newpass = $confirmpass = "";

    if(isset($_POST['submit'])) {

        if (isset($_POST['curpass']))  $curpass = sanitizeMySQL($connection,  $_POST['curpass']);
        if (isset($_POST['newpass']))  $newpass = sanitizeMySQL($connection,  $_POST['newpass']);
        if (isset($_POST['confirm']))   $confirmpass = sanitizeMySQL($connection,  $_POST['confirm']);
        
        $curpassError = validate_curpass($curpass);
        $newpassError = validate_newpass($newpass);
        $confirmpassError = validate_confirmpass($newpass, $confirmpass);
        

        if ($curpassError=="" && $newpassError ==""  && $confirmpassError =="") {
            $pass = hash('ripemd128', $newpass);

            if(changePassword($connection, $newpass)) {
                $curpass = $newpass = $confirmpass = "";
                // header("location: registrationcomplete.php");
            }   
        }    
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Change Password - Coding.</title>
</head>
<body>
    <div class="container">
        <div class="logo">#Coding.</div>
        <form action="changePassword.php" method="post" novalidate id="form">
            <h1>Change password</h1>
            <br><a href="viewprofile.php">Back</a>
            <div class="box">
                <label>Current Password</label><br>
                <input type="password" name="curpass" value="<?php echo $curpass; ?>"><br>
                <small class="error"><?php echo "$curpassError"; ?></small>
            </div>
            <div class="box">
                <label>New Password</label><br>
                <input type="password" name="newpass" value="<?php echo $newpass; ?>" ><br>
                <small class="error"><?php echo "$newpassError"; ?></small>
            </div>
            <div class="box">
                <label>Confirm New Password</label><br>
                <input type="password" name="confirm" value="<?php echo $confirmpass; ?>" ><br>
                <small class="error"><?php echo "$confirmpassError"; ?></small>
            </div>
            <div class="box">
                <input type="submit" value="Submit" name="submit">
            </div>
            
        </form>
    </div>
</body>
</html>


<?php


    function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
    function sanitizeMySQL($connection, $var){ 
        $var = mysqli_real_escape_string($connection, $var);
        $var = sanitizeString($var);
        return $var;
    }

    function validate_curpass($field) {
        if ($field == "") {
            return "Current password is required!";
        }
        else if (hash('ripemd128', $field)!= $_SESSION['password']){
            return "Incorrect current password";
        }
        return "";
    }

    function validate_newpass($field) {
        if ($field == "") {
            return "New password is required!";
        }
        else if (!((preg_match("/[a-z]/", $field)) &&
                (preg_match("/[0-9]/", $field)) &&
                (preg_match("/[^a-zA-Z0-9]/", $field)))) {
            return "Password must contain atleast one lower case letter, one digit and  one symbol!";
        }
        else if (strlen($field)<8) {
            return "Password must contain atleast 8 characters!";
        }
        return "";
    }

    function validate_confirmpass($pass, $cpass) {
        if ($cpass == "") {
            return "Confirm new password is required!";
        }
        else if (!((preg_match("/[a-z]/", $cpass)) &&
                (preg_match("/[0-9]/", $cpass)) &&
                (preg_match("/[^a-zA-Z0-9]/", $cpass)))) {
            return "Password must contain atleast one lower case letter, one digit and  one symbol!";
        }
        else if (strlen($cpass)<8) {
            return "Password must contain atleast 8 characters!";
        }
        else if ($pass != $cpass) {
            return "Confirm new password does not match!";
        }
        return "";
    }

    function changePassword($connection, $newpassword) {
        $hashed = hash('ripemd128', $newpassword);
        $updateSql = "UPDATE user set password = '$hashed'
                        WHERE email = '{$_SESSION['email']}'";
        $result = mysqli_query($connection, $updateSql);
        if ($result) {
            $_SESSION['password'] = $hashed;
            return true;
        }else {
            echo "cannot change the password!";
            return false;
        }
    }
?>