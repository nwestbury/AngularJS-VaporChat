<?php
    require_once("redirect.php");
    require_once("db.php");
    $json = json_decode(file_get_contents('php://input')); //get user from json headers

    if(!isset($json->status) || !is_int($json->status) || $json->status > 1 || $json->status < 0)
        var_dump("KILLED");
    
    if(!isset($_SESSION))
        session_start();

    $number = intval($json->status) > 0 ? 1 : 0;
    $id = $_SESSION['id'];
    $result = $mysqli->query("UPDATE `user` SET status=$number WHERE id=$id LIMIT 1");

    die((string)$json->status);
?>
