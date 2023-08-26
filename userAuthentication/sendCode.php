<?php

    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    $mail -> isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = ''; //your email

    $mail->Password = ''; //your password

    $mail->SMTPSecure = 'ssl';

    $mail->Port = 465;

    $mail->setFrom(''); //your email

    $mail->addAddress("{$_SESSION['email']}");

    $mail->isHTML(true);

    $mail->Subject = "Reset Password - Coding.";

    $mail->Body =  "
        Hi {$_SESSION['email']}, <br> <br> Your requested code is {$_SESSION['generated_code']}. <br>Do not share this code with any other third party.<br><br>Happy Coding!<br><br>Best regards,<br>Coding team.
    ";


    $mail->send();

    header("location:codeVerification.php");
    
?>
