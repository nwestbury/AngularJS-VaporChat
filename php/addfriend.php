<?php

require_once("redirect.php");
require_once("db.php");

$json = json_decode(file_get_contents('php://input'), true); //get data from json headers

if(!isset($_SESSION))
    session_start();

if(!isset($json["name"]))
    die("Failure");

if(!ctype_alnum($json["name"]))
    die("Name should only contain alphanumeric characters");

$name = $json["name"];
$result = $mysqli->query("SELECT id, username, friends FROM `user` WHERE username = '$name' LIMIT 1");

if(!$result->num_rows)
    die("No user with name '$name' found.");

$row = $result->fetch_row();

$friend_id = $row[0];
$name = $row[1];
$friends = $row[2];

if($friend_id == $_SESSION['id'])
    die("Cannot add yourself!");

if(in_array($friend_id, $_SESSION['friends']))
    die("Already added");

$_SESSION['friends'][] = intval($friend_id);
$_SESSION['friends_names'][] = $name;

$friend_ids = json_decode($friends, true);
$friend_ids[] = intval($_SESSION['id']);

asort($_SESSION['friends']);
asort($friend_ids);

$my_ids_str = json_encode($_SESSION['friends']);
$my_id = $_SESSION['id'];

$friend_ids_str = json_encode($friend_ids);

$mysqli->query("UPDATE `user` SET friends = '$my_ids_str' WHERE id=$my_id");
$mysqli->query("UPDATE `user` SET friends = '$friend_ids_str' WHERE id=$friend_id");

die("Success");
?>
