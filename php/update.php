<?php
    function valid_friends($friends){
        return count($friends) && ((isset($_SESSION["friends"]) && !array_diff($friends, $_SESSION["friends"])) ||
               (isset($_SESSION["friends_names"]) && !array_diff($friends, $_SESSION["friends_names"])));
    }

    function get_room_id($mysqli, $ids){
        $json_ids = json_encode($ids);
        $room_id = -1;

        if(isset($_SESSION["roomDict"]) && array_key_exists($json_ids, $_SESSION["roomDict"])){
            $room_id = $_SESSION["roomDict"][$json_ids];
        }else{
            $id_string = $mysqli->real_escape_string($json_ids);
            $query = "SELECT id FROM `room` WHERE users = '$id_string' LIMIT 1";
            $result = $mysqli->query($query);

            if(isset($result) && $result->num_rows){ //if result is not NULL and has >0 rows
                $row = $result->fetch_row();
                $room_id = intval($row[0]);
                
                $_SESSION["roomDict"][$json_ids] = $room_id;
            }
        }

        return $room_id;
    }

    function get_messages($mysqli, $room_id) {
        $query = "SELECT msgid, name, text, time FROM `chat` WHERE roomid=$room_id ORDER BY msgid ASC LIMIT 50";
        $result = $mysqli->query($query);

        if(!isset($_SESSION))
            session_start();

        $_SESSION["time"] = intval(microtime(true)*100);        

        $messages = array();
        while ($row = $result->fetch_row()) {
            $endindex = count($messages) - 1;
            $messageArray = array("id"=>$row[0], "name"=>$row[1], "text"=>$row[2], "time"=>$row[3]);

            if($endindex >= 0 && ($row[1] == $messages[$endindex][0]["name"])){
                $messages[$endindex][] = $messageArray;
            }else{
                $messages[] = array($messageArray);
            }
        }
        
        return json_encode($messages);

    }

    function get_latest_messages($mysqli, $room_id){
        if(!isset($_SESSION))
            session_start();
        
        $time = $_SESSION["time"];
        $query = "SELECT msgid, name, text, time FROM `chat` WHERE roomid=$room_id AND time>$time ORDER BY msgid ASC";
        $result = $mysqli->query($query);

        $messageArray = array();
        while ($row = $result->fetch_row()){
            $messageArray[] = array("id"=>$row[0], "name"=>$row[1], "text"=>$row[2], "time"=>$row[3]);
        }

        $_SESSION["time"] = intval(microtime(true)*100);

        return $messageArray;
    }

    function get_all($mysqli, $friend_ids){
        if(!isset($_SESSION))
            session_start();
        if(!isset($_SESSION["id"]) || !valid_friends($friend_ids))
            die("[]");

        $my_id = $_SESSION["id"];
        $friend_ids[] = $my_id;
        $ids = $friend_ids;
        sort($ids);

        $room_id = get_room_id($mysqli, $ids);

        return get_messages($mysqli, $room_id);
    }

    function get_users_ids($mysqli, $users){
        $limit = count($users);
        $fullUserString = "('";
        foreach ($users as $index => $user){
            $userString = $mysqli->real_escape_string($user);
            $fullUserString .= $userString;
            if($index != $limit - 1)
                $fullUserString .= "','";
        }
        $fullUserString .= "')";
        $query = "SELECT id FROM user WHERE username IN $fullUserString ORDER BY id ASC LIMIT $limit";
        $result = $mysqli->query($query);

        $ids = array();
        while ($row = $result->fetch_row()) {
            $ids[] = intval($row[0]);
        }
        return $ids;
    }
?>
