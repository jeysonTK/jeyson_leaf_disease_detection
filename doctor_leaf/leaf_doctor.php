<?php 
require($_SERVER['DOCUMENT_ROOT'].'/wp-config.php'); 
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php'); 
$current_user = wp_get_current_user(); 


if(empty($current_user->display_name))
{

echo '<!DOCTYPE html>
<html>
<head>
	<style>
                p{

                        text-align:center;
                }
		button {
			color: #ffffff;
			background-color: #babdb6;
			font-size: 19px;
			border: 1px solid #4e9a06;
			padding: 15px 50px;
			cursor: pointer;
          
		}
		.button {
		        background-color: #1c87c9;
			border: none;
			color: white;
			padding: 20px 34px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 20px;
			margin: 4px 2px;
			cursor: pointer;
                        margin:auto;
                        display:block;
		}
		button:hover {
			color: #2d63c8;
			background-color: #ffffff;
		}
	</style>
</head>
<body>
<p>If you want to us LeafDoctor</p>
	<a href="index.php/login/" class="button">login</a>
<p>OR</p>
	<a href="index.php/registration/" class="button">register</a>
</body>
</html>';

}
else
{

echo '<!DOCTYPE html>
<html>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
  Selecteaza imaginea:<BR>
  <input type="file" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="fileToUpload" id="fileToUpload">
<img id="pic" />

 <input type="submit" value="Upload Image" name="submit">


</form>

</body>
</html>';
}

?>


