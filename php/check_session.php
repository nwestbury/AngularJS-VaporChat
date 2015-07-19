<?php
    session_start();
    if(isset($_SESSION['uid']))
        die('logged on');
?>
