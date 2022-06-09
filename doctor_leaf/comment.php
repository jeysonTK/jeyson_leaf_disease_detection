
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
if(!isset($_POST["id"])){
    header("location: leafs.php");
    exit;
}
$id=$_POST["id"];
$message=$_POST["comment"];
$user_id=$_SESSION["id"];

// Include config file
require_once "database/config.php";

$current_user = $_SESSION["username"];
$sql="INSERT INTO comments (user_id, image_id, username, message ) VALUES ( $user_id, $id, '$current_user', '$message'  )";
if ($link->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
header("location: leaf-view.php?id=$id");
exit;
