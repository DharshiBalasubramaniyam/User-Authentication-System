<?php
    include "../database/dbconnection.php";

    $error = "";
    $email = $password = "";

    if(isset($_POST['submit'])) {

        if (isset($_POST['email']))   $email = sanitizeMySQL($connection,  $_POST['email']);
        if (isset($_POST['password']))  $password = sanitizeMySQL($connection,  $_POST['password']);

        $error = validate($connection, $email, $password);

        if ($error=="") {
            session_start();

            $result = getUser($connection, $email, $password);
            $user = mysqli_fetch_assoc($result);

            $_SESSION['username'] = $user['username'];
            $_SESSION['gender'] = $user['gender'];
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $user['password'];
            $_SESSION['regdate'] = $user['reg_date'];
            header("location: ../userDashboard/index.php");
        }

    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Login - Coding.</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">#Coding.</h1>
        <form action="login.php" method="post" novalidate id="form">
            <h1>Login</h1>
            <?php if (!empty($error)) { ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php } ?>
            <div class="box">
                <label for="email">Email</label><br>
                <input type="email" name="email" value="<?php echo $email; ?>"><br>
            </div>
            <div class="box">
                <label for="password">Password</label><br>
                <input type="password" name="password"value="<?php echo $password; ?>" ><br>
            </div>
            <div class="box">
                <a href="forgotPassword.php">Forgot Password?</a>
            </div>
            <div class="box">
                <input type="submit" value="Login" name="submit">
            </div>

            <div class="box">
                Don't have an account? <a href="register.php">Register here</a>
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

    function validate($connection, $email, $password) {

        $result = getUser($connection, $email, $password);
        
        if (mysqli_num_rows($result)==0) {
            return "Invalid username or password!";
        }
        return "";

    }

    function getUser($connection, $email, $password) {
        $hash_pass = hash('ripemd128', $password);
        $sql = "SELECT * FROM user WHERE email='$email' AND password='$hash_pass'";
        $result = mysqli_query($connection, $sql);
        return $result;
    }




?>