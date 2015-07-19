<?php
    require_once("redirect.php");
    require_once("db.php");
    require_once("update.php");

    function send_message($mysqli, $room_id, $msg){
        $result = $mysqli->query("SELECT MAX(msgid) AS msgid FROM `chat` WHERE roomid=$room_id LIMIT 1");

        $next_msg_id = 0;

        if($result->num_rows){
            $row = $result->fetch_row();
            $next_msg_id = $row[0] + 1;
        }

        $time = intval(microtime(true)*100);

        $stmt = $mysqli->prepare("INSERT INTO `chat` (roomid, text, msgid, name, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isisi', $room_id, $msg, $next_msg_id, $_SESSION['name'], $time);
        $stmt->execute();
    }

    $json = json_decode(file_get_contents('php://input')); //get data from json headers

    if(!isset($json->users))
        die();
    if(!isset($_SESSION))
        session_start();
    if(!isset($_SESSION['name']))
        die();
 

    $user = $_SESSION['name'];
    $users = $json->users;
    $msg = isset($json->msg) && is_string($json->msg) && strlen($json->msg) ? $json->msg : False;

    $friend_ids = get_users_ids($mysqli, $users);
    $ids = array_merge($friend_ids, (array)$_SESSION["id"]);    
    sort($ids);

    if(valid_friends($users)){
        $room_id = get_room_id($mysqli, $ids);
 
        if($room_id == -1)
            die();

        if($msg)
            send_message($mysqli, $room_id, $msg);

        $msgArrayArray = get_latest_messages($mysqli, $room_id);

        die(json_encode($msgArrayArray));
    }

    die();
?>
