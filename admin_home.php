<?php 
session_start();
include('DBConnection.php');

// Check if admin is logged in
if(!isset($_SESSION["admin_uname"])){
    header("Location: Adminlogin.php");
    exit();
}

include("adminheader2.html");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">
    <style>
        .bg-img {
            background: url(asset/img/10.jpg);
            background-repeat: no-repeat;
            background-size: 100%;
            background-attachment: fixed;
        }
        .welcome-text {
            color: white;
            text-align: center;
            margin-top: 50px;
            font-size: 2.5em;
        }
    </style>
</head>
<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
           <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-9">
            <div class="container mt-4">
                <h1 class="welcome-text">Welcome to Railway Administration System</h1>
            </div>
        </div>
    </div>

    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
</body>
</html>