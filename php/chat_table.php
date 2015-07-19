<?php
    require_once("redirect.php");
    require_once("db.php");
    if(!isset($_SESSION))
        session_start();

    $friend_string = "'".implode("','",$_SESSION["friends"])."'";
    $query = "SELECT username, uid, status FROM `user` WHERE id IN ($friend_string)";
    $result = $mysqli->query($query);

    if(!$result->num_rows)
        die("[]");

    $table_array = array();    
    while ($row = $result->fetch_row()){
        $array = array();
        $array["name"] = $row[0];
        $array["online"] = substr($row[1], 0, 2) === "vc" && intval($row[2]);
        $table_array[] = $array;
    }

    $mysqli->close();
    die(json_encode($table_array));
?>
