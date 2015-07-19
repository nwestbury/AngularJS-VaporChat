<?php
    require_once("db.php");

    if(!isset($_SESSION))
        session_start();

    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $mysqli->query("UPDATE `user` SET uid='' WHERE id=$id");
    }

    session_destroy();
    session_write_close();
?>
