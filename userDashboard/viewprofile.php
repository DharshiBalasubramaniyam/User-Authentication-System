<?php
    session_start();

    $initial = substr($_SESSION['username'], 0, 1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>View profile - Coding.</title>
</head>
<body>

    <div class="container">
        <div class="logo">#Coding.</div>
        <div class="profile">
                <h1>Your Profile</h1>
                <a href="index.php">Go to Home</a>
                <div class="image"><div class="img"><?php echo $initial; ?></div></div>
                <div class="details">
                    <div class="box"><div class="title">Username</div><div class="data"><?php echo  $_SESSION['username']; ?></div></div>
                    <div class="box"><div class="title">Email</div><div class="data"><?php echo  $_SESSION['email']; ?></div></div>
                    <div class="box"><div class="title">Gender</div><div class="data"><?php echo  $_SESSION['gender']; ?></div></div>
                    <div class="box"><div class="title">Reg. Date</div><div class="data"><?php echo  $_SESSION['regdate']; ?></div></div>
                    <a href="changePassword.php">Change password</a>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>