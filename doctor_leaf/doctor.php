<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor-Leaf - Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center;  background-color: #9bfab9 }
        div{
          text-align:center;
          padding:3%;
          border:thin solid black;
        }

        input{
          display: none;
        }
        label{
          cursor:pointer;
        }
        #imageName{
          color:green;
        }
    </style>
</head>
<body>
    <?php require_once "navbar.php"; ?>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Upload a image to analyze.</h1>

    <?php if(isset($_GET["message"]) ) echo $_GET["message"];  ?>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div>
      <label for="fileToUpload">
        Select Image <br/>
        <input type="file" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="fileToUpload" id="fileToUpload">
        <br>
        <img id="pic" /><BR>
        
        <span id="imageName"></span> 
      </label><BR>
        <button style="width:10%;height:30px" type="submit" value="Upload Image" name="submit"> Upload Image </button>
    </form>
    <script>
        let input = document.getElementById("fileToUpload");
        let imageName = document.getElementById("imageName")

        input.addEventListener("change", ()=>{
            let inputImage = document.querySelector("input[type=file]").files[0];

            imageName.innerText = inputImage.name;
        })
    </script>
</body>
</html>
