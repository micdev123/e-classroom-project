<?php 
    // Define some constants
    define('DB_HOST', 'localhost');
    define('DB_USER', 'mic__dev');
    define('DB_PASS', 'mic__dev');
    define('DB_NAME', 'e-classroom-project');
    define('BASE_URL', 'http://localhost/e-classroom-project/');
    

    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if($conn->connect_error) {
        // Called the die() fnx
        die('Connection Failed!'. $conn->connect_error);
    }

    // echo '<h1 style="text-align: center">Connected!</h1>';
?>