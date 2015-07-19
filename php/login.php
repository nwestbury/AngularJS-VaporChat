<?php
    require_once("destroy_session.php");
    require_once("db.php");

    function get_names_from_ids($mysqli, $ids){
        $friendString = substr_replace(substr_replace($ids, '(', 0, 1), ')', -1, 1);
        $query = "SELECT username FROM `user` WHERE id IN $friendString";
        $result = $mysqli->query($query);
        if($result->num_rows){
            while($row = $result->fetch_row()){
                $_SESSION['friends_names'][] = $row[0];
            }
        }
    }
    
    $user = json_decode(file_get_contents('php://input')); //get user from json headers

    if(!isset($user) || !isset($user->name) || !isset($user->pass))
        die("Please fill in the fields!");

    $stmt = $mysqli->prepare("SELECT id, hash, friends FROM `user` WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $user->name);
    $stmt->execute();
    $stmt->store_result();

    if(!$stmt->num_rows)
        die("User not found");

    $stmt->bind_result($id, $hash, $friends);
    $stmt->fetch();

    if(!password_verify($user->pass, $hash))
        die("Incorrect password");

    session_start();
    $uid = "vc_" . bin2hex(openssl_random_pseudo_bytes(16));
    $mysqli->query("UPDATE user SET uid='$uid' WHERE id=$id");

    $friend_array = json_decode($friends);
    
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $user->name;
    $_SESSION['uid'] = $uid;
    $_SESSION['friends'] = $friend_array;

    $stmt->free_result();
    $stmt->close();

    $_SESSION['friends_names'] = array();

    if(count($friend_array))
        get_names_from_ids($mysqli, $friends);

    die($uid);
?>
