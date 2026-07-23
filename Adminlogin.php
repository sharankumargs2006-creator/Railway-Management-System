<?php
session_start();
include('DBConnection.php');

// Debug mode - remove in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['logbtn'])) {  // Changed from 'login' to 'logbtn' to match form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Debug log - remove in production
    error_log("Login attempt - Username: $username");
    
    $query = "SELECT * FROM admin WHERE username=? AND password=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $_SESSION["admin_uname"] = $username;
        // Clear any previous output before redirect
        ob_clean();
        header("Location: admin_home.php");
        exit();
    } else {
        $er_invalid = "Invalid Username or Password";
    }
}
?>


<!doctype html>
<html lang="en">
<head>
	<title>IR</title>
	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="icon/png" href="asset/img/logo/rail_icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">

    <!-- :start of optional css-->

    <!-- font-awesome for icon -->
    <link rel="stylesheet" href="asset/font-awesome/css/all.min.css">

    <!-- animation css -->
    <link rel="stylesheet" href="asset/css/animate.css">

    <!-- hover css animations -->
    <link rel="stylesheet" href="asset/css/hover-min.css">

    <!-- :end of optional css -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>


    

    <!-- custom style -->
    <style>
    	#bg-custom{
            background-color:rgba(2,2,2,0.8);
        }
        .bg-custom{
            background-color:rgba(2,2,2,0.8);
        }
        .bg-img{
        	background-image:url('asset/img/5.jpg'); 
        	background-size: 100%;
        }
        .bg-img2{
            background-image:url('asset/img/5.jpg'); 
            background-size: 100%;
        }
        .m-cust{
        	margin-right: 250px;
        	margin-top: 60px; 
        }
    </style>

</head>
<body class="bg-img2">

	

	<!-- include header -->
   	<?php include('adminheader1.html') ?>
	
	<!--  Admin Login Page -->
	<div class="container " id="id1">
        
        <div class="modal-dialog" id="m-cust">
            <div class="modal-content bg-custom" id="bg-custom">
                <div class="modal-header">
                    <img src="asset/img/8.jpg" width="480">
                </div>
                <div class="modal-body">
                <span class=" text-danger fs-18 badge badge-light offset-5" id="er_username"></span>
                <span class="fs-18 text-danger badge badge-light offset-5" id="er_password"></span>
                    <!-- form -->
                    <div  class="text-red">
                                <span ><?php if (isset($er_invalid)){ echo "$er_invalid"; }?></span>
                            </div>
                    <form action="" method="post" name="logForm" onsubmit="return(logvalidation());">
                        <div class="input-group">   
                            <!-- username label -->
                            <div class="input-group-prepend">
                                <span class="input-group-text alert-danger text-dark">Username</span>
                            </div>
                            <input type="text" name="username" id="uname" class="form-control" placeholder="Enter Username" required> 
                        </div><!-- group1 ends -->
                        <br>
                        <div class="input-group">
                            <!-- password label -->
                            <div class="input-group-prepend">
                                 <span class="input-group-text alert-danger text-dark">
                                Password    
                                </span>
                            </div>
                            <input type="password" name="password" id="pass" class="form-control" placeholder="Enter Password" required>
                        </div> <!-- group2 ends -->  
                        <br>
                        <div class="input-group">
                            <input class="btn btn-success btn-block" type="submit" value="Login" name="logbtn">
                        </div>  
                            
                    </form>
                </div><!-- modal-body ends -->

                <div class="modal-footer">
                    <span id="error"></span>
                    <!-- <a href="" class=" badge-pill badge-dark">Forget Password</a> -->
                </div>

            <!-- modal-content -->
            </div>
        <!-- modal-dialog ends -->
        </div>

    <!-- modal ends -->
    </div>
<!-- admin login ends -->

</body>
</html>