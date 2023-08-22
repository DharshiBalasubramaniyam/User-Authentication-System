<?php
    include "../database/dbconnection.php";


    $usernameError = $genderError = $emailError = $passwordError = $cpasswordError = $agreeError = "";
    $gender = $username = $email = $password = $cpassword = $agree = "";

    if(isset($_POST['submit'])) {

        if (isset($_POST['username']))  $username = sanitizeMySQL($connection,  $_POST['username']);
        if (isset($_POST['gender']))  $gender = sanitizeMySQL($connection,  $_POST['gender']);
        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['password']))  $password = sanitizeMySQL($connection,  $_POST['password']);
        if (isset($_POST['cpassword']))   $cpassword = sanitizeMySQL($connection,  $_POST['cpassword']);
        if (isset($_POST['agree']))  $agree = sanitizeMySQL($connection,  $_POST['agree']);


        $usernameError = validate_uname($username);
        $genderError = validate_gender($gender);
        $emailError = validate_email($email);
        $passwordError = validate_password($password);
        $cpasswordError = validate_cpassword($password, $cpassword);
        $agreeError = validate_agree($agree);


        if ($usernameError=="" && $genderError ==""  && $emailError =="" && $passwordError=="" && $cpasswordError =="" && $agreeError =="") {
            $pass = hash('ripemd128', $password);
            $usernameError = checkUsername($connection, $username);
            $emailError = checkEmail($connection, $email);

            if ($usernameError=="" && $emailError =="") {
                if(addUser($connection, $username, $email, $gender, $pass)) {
                    $username = $email = $gender = $password = $cpassword = $agree = "";
                    header("location: registrationcomplete.php");
                }   
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
    <title>Register</title>
</head>
<body>
    <div class="container">
        <form action="register.php" method="post" novalidate id="form">
            <h1>Register</h1>
            <div class="box">
                <label for="uname">Username</label><br>
                <input type="text" name="username" id="uname" value="<?php echo $username; ?>"><br>
                <small class="error"><?php echo "$usernameError"; ?></small>
            </div>
            <div class="box">
                <label>Gender</label><br>
                <span class="radio-box">
                    <input type="radio" name="gender" id="male" value="Male" <?php if (isset($gender) && $gender=="Male") { echo "checked"; } ?>><label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="Female" <?php if (isset($gender) && $gender=="Female") { echo "checked"; } ?>><label for="female">female</label>
                    <input type="radio" name="gender" id="other" value="Other" <?php if (isset($gender) && $gender=="Other") { echo "checked"; } ?>><label for="other">Other</label>
                </span><br>
                <small class="error"><?php echo "$genderError"; ?></small>
            </div>
            <div class="box">
                <label for="email">Email</label><br>
                <input type="email" name="email" value="<?php echo $email; ?>"><br>
                <small class="error"><?php echo "$emailError"; ?></small>
            </div>
            <div class="box">
                <label for="password">Password</label><br>
                <input type="password" name="password"value="<?php echo $password; ?>" ><br>
                <small class="error"><?php echo "$passwordError"; ?></small>
            </div>
            <div class="box">
                <label for="cpassword">Confirm Password</label><br>
                <input type="password" name="cpassword" value="<?php echo $cpassword; ?>"><br>
                <small class="error"><?php echo "$cpasswordError"; ?></small>
            </div>
            <div class="box">
                <input type="checkbox" name="agree" id="agree" value="agree" <?php if (isset($_POST['agree']) && $agree=="agree") { echo "checked"; } ?>> <label for="agree">I accept the terms of use & privacy Policy.</label> <br>
                <small class="error"><?php echo "$agreeError"; ?></small>
            </div>
            <div class="box">
                <input type="submit" value="Submit" name="submit">
            </div>

            <div class="box">
                Already a member? <a href="login.php">Login here</a>
            </div>
            
        </form>
    </div>
</body>
</html>


<?php


    function validate_uname($field) {
        if ($field == "") {
            return "Username is required!";
        }
        else if ((preg_match("/[^a-zA-Z0-9]/", $field))) {
            return "Username cannot have special characters or white spaces!";
        }
        return "";
    }

    function validate_gender($field) {
        return ($field == "") ? "Gender is required!" : "";
    }

    function validate_email($field) {
        if ($field == "") {
            return "Email is required!";
        }
        else if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
            return "Invalid Email format";
        }
        return "";
    }

    function validate_password($field) {
        if ($field == "") {
            return "Password is required!";
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

    function validate_cpassword($pass, $cpass) {
        if ($cpass == "") {
            return "Confirm password is required!";
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
            return "Confirm password does not match!";
        }
        return "";
    }

    function validate_agree($field) {
        return ($field == "") ? "Agreement is required!" : "";
    }

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

    function checkUsername($connection, $field) {
        $sql = "SELECT username from user where username='$field'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result)>0) {
            return "Given username already exists";
        }
        return "";
    }

    function checkEmail($connection, $field) {
        $sql = "SELECT email from user where email='$field'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result)>0) {
            return "Given Email already exists";
        }
        return "";
    }

    function addUser($connection, $username, $email, $gender, $password) {
        $insertSql = "INSERT INTO user(username, gender, email, password) 
                VALUES('$username', '$gender', '$email', '$password')";
        $result = mysqli_query($connection, $insertSql);
        if ($result) {
            return true;
        }else {
            echo "cannot add user to the system!";
            return false;
        }
                
    }

    

?>