<?php

    session_start();
    include "../database/dbconnection.php";

    $newpassError = $confirmpassError = $newpass = $confirmpass = "";

    if(isset($_POST['submit'])) {

        if (isset($_POST['newpass']))  $newpass = sanitizeMySQL($connection,  $_POST['newpass']);
        if (isset($_POST['confirm']))   $confirmpass = sanitizeMySQL($connection,  $_POST['confirm']);
        
        $newpassError = validate_newpass($newpass);
        $confirmpassError = validate_confirmpass($newpass, $confirmpass);
        

        if ($newpassError ==""  && $confirmpassError =="") {
            $pass = hash('ripemd128', $newpass);

            if(changePassword($connection, $newpass)) {
                $newpass = $confirmpass = "";
                session_destroy();
                echo "<script>alert('you have successfully changed your password!');</script>";
                header("location:login.php");
            }   
        }    
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Reset Password - Coding.</title>
</head>
<body>
    <div class="container">
        <div class="logo">#Coding.</div>
        <form action="resetPassword.php" method="post" novalidate id="form">
            <h1>Change password</h1>
            <div class="box" style="text-align: center;"><?php echo $_SESSION['email']; ?>, Now you can reset your password and log in.</div>
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
                <input type="submit" value="Reset password" name="submit">
            </div>

            <a href="login.php">Back to login</a>
            
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
            return true;
        }else {
            echo "cannot change the password!";
            return false;
        }
    }
?>