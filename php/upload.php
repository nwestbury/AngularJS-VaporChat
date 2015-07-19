<?php
require_once("db.php");

function get_id($mysqli){
    $results = $mysqli->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'vaporchat' AND TABLE_NAME = 'user' LIMIT 1");

    $row = $results->fetch_row();

    return $row[0];
}

$required_post_fields = array("name", "pass", "email");
$required_files_fields = array("icon");

foreach($required_post_fields as $name){
    if(!isset($_POST[$name]))
        die("Please fill all the fields");
}

foreach($required_files_fields as $name){
    if(!isset($_FILES[$name]))
        die("Please fill all the fields");
}

if(!ctype_alnum($_POST["name"]))
    die("Name should only contain alphanumeric characters");

if(strlen($_POST["name"]) > 64)
    die("Name should be <= 64 chars");

if(strlen($_POST["pass"]) < 8 || strlen($_POST["pass"]) > 64)
    die("Password should be 8 to 64 chars");

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    die("Invalid email");

$name = $_POST["name"];
$hash = password_hash($_POST["pass"], PASSWORD_BCRYPT);
$email = $_POST["email"];

$results = $mysqli->query("SELECT id FROM `user` WHERE username = '$name' LIMIT 1");
if($results->num_rows)
    die("Username already used");

$fn = $_FILES['icon']['tmp_name'];
$img = file_get_contents( $fn );
$ext = strtolower(pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION));
$valid_exts = array("jpg", "jpeg", "png", "gif", "bmp");

if(!in_array($ext, $valid_exts))
    die("Icon must be bmp, gif, png or jpg (will be converted to jpg).");

$maxDim = 64;

list($width, $height, $type, $attr) = getimagesize( $_FILES['icon']['tmp_name'] );

$size = getimagesize( $fn );
$ratio = $size[0]/$size[1]; // width/height
if( $ratio > 1) {
    $width = $maxDim;
    $height = $maxDim/$ratio;
} else {
    $width = $maxDim*$ratio;
    $height = $maxDim;
}
$src = imagecreatefromstring( $img );

$dst = imagecreatetruecolor( $width, $height );
imagecopyresampled( $dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1] );
imagedestroy( $src );

$id = get_id($mysqli);

$target_filename = "../img/icons/" . $name . ".jpg";
imagejpeg($dst, $target_filename);
imagedestroy( $dst );

$mysqli->query("INSERT into `user` (username, hash) VALUES ('$name', '$hash')");
die("Sucessfully registered!");
?>
