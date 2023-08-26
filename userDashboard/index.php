<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Home - Coding.</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">#Coding.</h1>
        <div class="message-box">
            <h1>Welcome 
                <?php if (isset($_SESSION['username'])) { 
                    echo "back {$_SESSION['username']} : )"; 
                    } else {
                        echo " to Coding!";
                    } 
                ?>
            </h1>

            <h4>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Recusandae eum eos beatae consectetur odio aperiam? Quis aut vero esse autem.</h4>

            <div class="btns">
                <?php if (isset($_SESSION['username'])) { ?>
                    <a href="viewprofile.php"><button>View Profile</button></a>
                    <a href="logout.php"><button>Log out</button></a>
                <?php } else { ?>
                    <a href="../userAuthentication/login.php"><button>Sign in</button></a>
                <?php }  ?>   
            </div>

        </div>
    </div>
</body>
</html>