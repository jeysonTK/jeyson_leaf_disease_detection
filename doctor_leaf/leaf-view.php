<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(!isset($_POST["id"]) && !isset($_GET["id"]) ){
    header("location: leafs.php");
    exit;
}
$id=$_POST["id"];
if (!empty($_GET['id'])) { $id = $_GET['id']; }

// Include config file
require_once "database/config.php";

$my_leafs = $link->query("SELECT * FROM users_leaf WHERE `id`='$id'");
$leaf_comments_result= $link->query("SELECT * FROM comments WHERE `image_id`='$id'");
$detections_result= $link->query("SELECT * FROM detections WHERE `users_leaf_id`='$id'");
$original_image_path=$analized_image_path=$username=$result="";

foreach( $my_leafs as $row) {
	$original_image_path=$row["original_image_path"];
	$analized_image_path=$row["analized_image_path"];
	$username=$row["username"];
	$result=$row["result"];
}

$leaf_comments="";
foreach( $leaf_comments_result as $row) {
	
	$com_username=$row["username"];
	$datetime=$row["datetime"];
	$comment=$row["message"];
	$leaf_comments = $leaf_comments . "<table style='font-size:25px'><thead><tr><th>$com_username</th></tr></thead><tbody><tr><td>$datetime</td></tr><tr><td>$comment</td></tr></tbody></table><BR><BR><BR>";
}

$leaf_detections="";
foreach( $detections_result as $row) {
	$name=$row["name"];
	$percentage_probability=$row["percentage_probability"];
	$box_points=$row["box_points"];
	$leaf_detections = $leaf_detections . "<tr><td colspan='2'>$name $box_points $percentage_probability</td></tr>";
}

echo "Leaf viewr page";

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor-Leaf - Leaf Viewer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center;  background-color: #9bfab9 }
    </style>
</head>
<body>
    <?php require_once "navbar.php"; ?>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to leaf viewer</h1>
    <table style=" width: 60vw; margin-left : 20vw;">
    <thead>
        <tr>
	    <th colspan="2"><p style="font-size:50px">Uploader : <?php echo $username; ?></p></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><img src="/<?php echo $original_image_path; ?>" alt="Original" width="100%" height="100%"></td>
        <td><img src="/<?php echo $analized_image_path; ?>" alt="Analized" width="100%" height="100%"></td>
    </tr>
    <tr>
       <td colspan="2"> <p style="font-size:50px"> Result : </p> </td>
    </tr>
    <?php echo $leaf_detections; ?>
    </tbody>
    </table>
    <form  style=" width: 60vw; margin-left : 0vw;" action="comment.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/><BR><BR><BR>
        Comment:
        <textarea  style=" width: 60vw; margin-left : 20vw;height:150px" name="comment"></textarea><BR><BR>
        <button style=" width: 60%; margin-left : 16vw;height:50px" type="submit" value="Upload Image" name="submit"> Post </button>
    </form>
    <div style=" width: 60vw; margin-left : 20vw;">
    <?php echo $leaf_comments; ?>
    <div>
</body>
</html>
