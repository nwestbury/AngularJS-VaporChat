<?php
    require_once("redirect.php");
    require_once("db.php");
    require_once("update.php");

    $json = json_decode(file_get_contents('php://input')); //get user from json headers

    if(isset($json) && $json->users){
        $users = $json->users;
        $users = get_users_ids($mysqli, $users);
        if(!isset($_SESSION))
            session_start();
        if(valid_friends($users)){
            $json_string = get_all($mysqli, $users);
            die($json_string);
        }
    }
    die();
?>
