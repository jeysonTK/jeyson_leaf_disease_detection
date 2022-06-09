
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$current_user = $_SESSION["username"];
$message="";

$destination_path = getcwd().DIRECTORY_SEPARATOR;	
$target_dir = $destination_path ."uploads/";
$ip = file_get_contents("http://ipecho.net/plain");
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

$target_file = $target_dir .  $current_user . '--u--' .date("Y-m-d H:i:s") . '--u--' . $details->city. '--u--' .basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $message = "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    $message = "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  $message = "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
  $message = "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $message = "Sorry, your file was not uploaded.".  $message ;
// if everything is ok, try to upload file
} else {
  if (@move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $message = "Your image was uploaded";
  } else {
    $message = "Sorry, there was an error uploading your file.";
  }
}

    header("location: doctor.php?message=$message");
    exit;
?>

