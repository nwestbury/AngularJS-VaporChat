<?php
    require_once("redirect.php");
    require_once("db.php");
    require_once("update.php");

    function delete_all_messages($mysqli, $room_id){
        $mysqli->query("DELETE FROM `chat` WHERE roomid=$room_id");
    }

    $json = json_decode(file_get_contents('php://input')); //get data from json headers

    if(!isset($json) || !isset($json->users))
        die();
    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION['name']))
        die();

    $user = $_SESSION['name'];
    $users = $json->users;

    $friend_ids = get_users_ids($mysqli, $users);
    $ids = array_merge($friend_ids, (array)$_SESSION["id"]);    
    sort($ids);

    if(valid_friends($users)){
        $room_id = get_room_id($mysqli, $ids);

        if($room_id == -1)
            die();

        delete_all_messages($mysqli, $room_id);

        die("1");
    }

    die();
    
?>

