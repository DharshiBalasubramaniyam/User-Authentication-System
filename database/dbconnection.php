<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "UAS";
    $connection = "";

    try {
        $connection = mysqli_connect($server, 
                                    $user, 
                                    $password, $database);
    }catch(mysqli_sql_exception) {
        echo "could not connect";
    }

    // if($connection) { echo "you are connected.<br>"; }
    // else { echo "you are not connected.<br>"; }

?>