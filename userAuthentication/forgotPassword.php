<?php

    session_start();
    include "../database/dbconnection.php";

    $error = "";
    $email = "";

    if(isset($_POST['submit'])) {

        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        $error = validateEmail($connection, $email);

        if ($error == "") {
            $_SESSION['generated_code'] = getCode();
            $_SESSION['email'] = $email;
            header("location:sendCode.php");
        }

    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Forgot Password - Coding.</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">#Coding.</h1>
        <form action="forgotPassword.php" method="post" novalidate id="form">
            <h1>Forgot Password?</h1>
            <div class="box" style="text-align: center;">No problem. Please provide the email that you used to signed up for your account.</div>
            <?php if (!empty($error)) { ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php } ?>
            <div class="box">
                <label for="email">Email</label><br>
                <input type="email" name="email" value="<?php echo $email; ?>"><br>
            </div>
            <div class="box">
                <input type="submit" value="Continue" name="submit">
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


    function validateEmail($connection, $email) {
        if ($email == "") {
            return "Email is required!";
        }
        else {
            $result = getEmail($connection, $email);
            if (mysqli_num_rows($result)==0) {
                return "Incorrect Email!";
            }
        }
        return "";
    }

    function getEmail($connection, $email) {
        $sql = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($connection, $sql);
        return $result;
    }

    function getCode() {
       $code = random_int(100000, 999999);
       return $code; 
    }


?>