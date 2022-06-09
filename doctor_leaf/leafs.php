<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "database/config.php";

$city      = $_POST['city'];
$username  = $_POST['username'];
$dateStart = $_POST['dateStart'];
$dateEnd   = $_POST['dateEnd'];

if (empty($username)) {
    $username = $_SESSION["username"];
}

if (!empty($_GET['city'])) {
    $city = $_GET['city'];
}
if (!empty($_GET['username'])) {
    $username = $_GET['username'];
}
if (!empty($_GET['dateStart'])) {
    $username = $_GET['dateStart'];
}
if (!empty($_GET['dateEnd'])) {
    $dateEnd = $_GET['dateEnd'];
}

$extra_param_pagination = "";
if (isset($city)) {
    $extra_param_pagination = "&city=" . $city;
}
if (isset($username)) {
    $extra_param_pagination = $extra_param_pagination . "&username=" . $username;
}
if (isset($dateStart)) {
    $extra_param_pagination = $extra_param_pagination . "&dateStart=" . $dateStart;
}
if (isset($dateEnd)) {
    $extra_param_pagination = $extra_param_pagination . "&dateEnd=" . $dateEnd;
}

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$where_sql = " WHERE `username` like '%" . $username . "%' ";

$dateStart = $_POST['dateStart'];
$dateEnd   = $_POST['dateEnd'];

if (!empty($dateStart)) {
    $where_sql = $where_sql . " and `datetime` > '" . $dateStart . " 00:00:00' ";
}

if (!empty($dateEnd)) {
    $where_sql = $where_sql . " and `datetime` < '" . $dateEnd . " 23:59:59' ";
}

if (!empty($city)) {
    $where_sql = $where_sql . " and `location` = '" . $city . "' ";
}

$no_of_records_per_page = 5;
$offset                 = ($pageno - 1) * $no_of_records_per_page;
$total_rows             = 0;
if ($result = $link->query("SELECT id FROM users_leaf" . $where_sql)) {
    $total_rows = $result->num_rows;
    $result->free_result();
}
$total_pages = ceil($total_rows / $no_of_records_per_page);

$my_leafs = $link->query("SELECT * FROM users_leaf " . $where_sql . " ORDER BY datetime DESC LIMIT $offset, $no_of_records_per_page");

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor-Leaf - Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center;  background-color: #9bfab9 }
    </style>
</head>
<body>
    <?php
require_once "navbar.php";
?>
    <h1 class="my-5">Hi, <b><?php
echo htmlspecialchars($_SESSION["username"]);
?></b>. Welcome to leaf viewer</h1>
    <form style=" width: 40vw; margin-left : 30vw;" action="leafs.php" method="post" style=" max-width: 75%;">
	<table width="100%">
		<thead>
		  <tr>
		    <td width="25%">City <input type="text" name="city" value="<?php
echo $city;
?>"/> </td>
		    <td>Start Date <input type="date" name="dateStart"  value="<?php
echo $dateStart;
?>"/></td>
		    <td>End Date<input type="date" name="dateEnd"  value="<?php
echo $dateEnd;
?>"/> </td>
		    <td width="25%">User <input type="text" name="username" value="<?php
echo $username;
?>"> </td>
		    <td><button type="submit">Filter</button></td>
		  </tr>
		</thead>
	</table>
     </form>
    <table style=" width: 60vw; margin-left : 20vw;">


<?php
foreach ($my_leafs as $row) {
    echo '<BR><tr>
	    <td colspan="2"></td>
	    <td rowspan="2">' . $row["username"] . '</td>
	    <td rowspan="2">
	    	<form action="./leaf-view.php" id="homePage" method="post">
   			 <input type="hidden" name="id" value="' . $row["id"] . '" />
   			 <button type="submit" value="View"> View </button>
		</form><br></td>
	  </tr>
	  <tr>
	    <td> <img src="/' . $row["original_image_path"] . '" alt="Original" width="300" height="300"> </td>
	    <td> <img src="/' . $row["analized_image_path"] . '" alt="Analized" width="300" height="300"> </td>
	  </tr>';
}
?>
	</table>
     	<table style=" width: 40vw; margin-left : 30vw;"  class="pagination" >
	<thead>
		<tr>
			<td><div>
				<a href="?pageno=1<?php echo $extra_param_pagination; ?>">First........</a>
			</div></td>
			<td><div class="<?php if ($pageno <= 1) { echo 'disabled'; } ?>">
				<a href="<?php if ($pageno <= 1) { echo '#'; } else { echo "?pageno=" . ($pageno - 1) . $extra_param_pagination; } ?>">........Prev........</a>
			</div></td>
			<td><div class="<?php if ($pageno >= $total_pages) { echo 'disabled'; }?>">
				<a href="<?php if ($pageno >= $total_pages) {echo '#';} else {echo "?pageno=" . ($pageno + 1) . $extra_param_pagination;}?>">........Next........</a>
       		</div></td>
       		<td><div>
				<a href="?pageno=<?php 	echo $total_pages . $extra_param_pagination;?>">........Last</a>
			</div></td>
		</tr>
	</thead>
</table
</body>
</html>
