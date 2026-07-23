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

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">
    <link rel="stylesheet" href="asset/css/modern-styles.css">
    <!-- :end of optional css -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>

    <style>

        /*.bg-img{
            background-image: url('asset/img/7.jpg');
            background-size: 100%;
            background-repeat: no-repeat;
        }*/

    </style>

</head>
<body>
<div class="bg-black">
	<nav class="navbar bg-dark navbar-dark navbar-expand-sm mb-1">

        <button class="navbar-toggler" data-target="#myNav" data-toggle="collapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand ml-auto"><a href="index.php" class="text-light hvr-grow" style="text-decoration: none;">Indian Railways</a></div>
        <div class="navbar-collapse collapse" id="myNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="" class="nav-link">♣</a></li>
                <li class="nav-item"><a href="" class="nav-link">
                    <script type="text/javascript">
                        var today = new Date();
                        var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
                        var time = " [ " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds()+ " ]";
                        var dateTime = date+' '+time;
                        document.write(dateTime);
                    </script></a></li>
                <li class="nav-item"><a href="Adminlogin.php" class="nav-link text-danger">Admin Login</a>
                </li>
            </ul>
        </div>
    </nav>

<!-- get system current time script -->
<script type="text/javascript">
    function dateTime(){
    var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var dateTime = date+' '+time;
    return dateTime;
}
</script>

    <nav class="navbar navbar-expand-lg sticky-top glass">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="asset/img/logo/passangerW.png" alt="IR Logo" class="mr-2">
                IR
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <?php if(isset($_SESSION["uname"])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="navbar-text ml-3">
                    <span id="datetime" class="text-light"></span>
                </div>
            </div>
        </div>
    </nav>
</div>
<nav class="navbar navbar-expand-lg sticky-top glass">
    <!-- Add modern navbar content -->
</nav>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ...existing head content... -->

    <script>
    // Add success message handler
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            showNotification('Successfully logged in!', 'success');
        <?php elseif (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
            showNotification('Successfully logged out!', 'info');
        <?php endif; ?>
    });

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} notification-toast`;
        notification.innerHTML = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }, 100);
    }
    </script>

    <style>
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        padding: 15px 25px;
        border-radius: 10px;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .notification-toast.show {
        transform: translateX(0);
    }

    .alert-success {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
    }

    .alert-info {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
    }
    </style>
</head>