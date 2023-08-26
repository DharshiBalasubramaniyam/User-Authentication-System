<?php

    session_start();

    $_SESSION['code'] = '';

    if(isset($_POST['submit'])) {

        if (isset($_POST['code']))   $_SESSION['code'] = SanitizeString($_POST['code']);

        $error = validate_code($_SESSION['generated_code'],  $_SESSION['code']);

        if ($error=="") {
            unset($_SESSION['generated_code']);
            header("location:resetPassword.php");
        }
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Code Verification - Coding.</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">#Coding.</h1>
        <form action="codeVerification.php" method="post" novalidate id="form">
            <h1>Code Verification</h1>
            <div class="box" style="text-align: center;">Please enter the code that we have sent to the email <?php echo $_SESSION['email']; ?></div>
            <?php if (!empty($error)) { ?>
                <div class="error-box"><?php echo $error; ?></div>
            <?php } ?>
            <div class="box">
                <label for="code">Verification Code</label><br>
                <input type="text" name="code" value="<?php echo $_SESSION['code']; ?>"><br>
            </div>
            <div class="box">
                <input type="submit" value="Submit" name="submit">
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
    function validate_code($code1, $code2) {
        if ($code1 != $code2) {
            return "Incorrect code";
        }
        return "";
    }
?>