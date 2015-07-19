<?php
    $mysqli = new mysqli('localhost', 'root', 'toor', 'vaporchat');

    if (mysqli_connect_errno()) {
        $error_message = "Connect failed: " . mysqli_connect_error();
        die($error_message);
    }
?>
