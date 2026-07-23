<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php 
//header which has logout btn and also have username who have been currently login
    // session_start();

    include('DBConnection.php');

    $uname = $_SESSION['uname'];
    //take data of user who currently login from the user table
    $sql = "select * from user where username = '$uname'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            //taking first & last name of user
            $name = $row["first_name"]." ".$row["last_name"];
        }
    }
    else{
        echo $conn->error;
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

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">

    <!-- :end of optional css -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>

    <style>
        :root {
            --primary: #000000;          /* Black */
            --primary-light: #222222;    /* Slightly lighter black */
            --white: #ffffff;            /* White */
            --gray-100: #f3f4f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .navbar, .bg-black {
            background: var(--primary) !important;
            box-shadow: var(--shadow);
            padding: 1.5rem 1rem;
            min-height: 90px;
        }

        .navbar-brand, .navbar-brand a {
            color: var(--white) !important;
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        .navbar-brand img {
            height: 38px;
            margin-right: 10px;
        }

        .nav-link {
            position: relative;
            background: var(--white);
            color: var(--primary) !important;
            border: none;
            border-radius: 30px;
            padding: 0.6rem 1.5rem !important;
            margin: 0 7px;
            font-weight: 600;
            font-size: 1.05rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: 
                background 0.3s cubic-bezier(.4,2,.6,1),
                color 0.2s,
                box-shadow 0.2s,
                transform 0.2s;
            overflow: hidden;
            z-index: 1;
        }

        .nav-link:hover, .nav-link:focus {
            background: var(--gray-100);
            color: var(--primary) !important;
            box-shadow: 0 6px 18px rgba(0,0,0,0.13);
            transform: translateY(-2px) scale(1.04);
            text-decoration: none;
        }

        .nav-link.active, .nav-link[aria-current="page"] {
            background: var(--primary-light);
            color: var(--white) !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.09);
        }

        .dropdown-menu {
            background: var(--white);
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.13);
            margin-top: 10px;
            min-width: 180px;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            color: var(--primary);
            font-weight: 500;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            transition: background 0.2s, color 0.2s, transform 0.2s;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background: var(--primary-light);
            color: var(--white);
            transform: translateX(5px);
        }

        .navbar-toggler {
            background: var(--white);
            border: none;
            padding: 0.5rem 0.75rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0,0,0,1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Welcome and datetime text */
        .welcome-text, .datetime-text {
            color: var(--white) !important;
            font-weight: 500;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0.5rem 1rem;
            margin: 0 7px;
            border-radius: 30px;
        }

        /* Logout and Register as special buttons */
        .nav-link.logout-btn, .nav-link.register-btn {
            background: var(--white) !important;
            color: var(--primary) !important;
            border: 2px solid var(--primary);
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .nav-link.logout-btn:hover, .nav-link.register-btn:hover {
            background: var(--primary-light) !important;
            color: var(--white) !important;
            border-color: var(--primary-light);
        }

        .logout-btn {
            background: #fff !important;
            color: #000 !important;
            border-radius: 30px;
            font-weight: 600;
            margin-left: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .logout-btn:hover {
            background: #222 !important;
            color: #fff !important;
        }
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
</div>

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

    <nav class="navbar bg-primary navbar-dark navbar-expand-sm">
        
        <div class="navbar-brand ml-auto">
            <img class="" src="asset/img/logo/passangerW.png">
            IR
        </div>
                
        <div class="navbar-collapse collapse" id="myNav">
            <ul class="navbar-nav ml-5">
                <li class="nav-item"><a href="index.php" class="nav-link"><i class="fa fa-home"></i></a></li>
                <li class="nav-item dropdown"><a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">Trains</a>
                    <div class="dropdown-menu">
                        <a href="trainschedule.php" class="dropdown-item">Train Schedule</a>
                        <a href="pnrstatus.php" class="dropdown-item">PNR Status</a>
                        <a href="cancelticket.php" class="dropdown-item">Cancel Ticket</a>
                        <a href="index.php" class="dropdown-item">Book Ticket</a>
                    </div>
                </li><li class="nav-item"><a href="places.php" class="nav-link">Places</a></li>
                <li class="nav-item"><a href="contact-us.php" class="nav-link">Contact Us</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <!-- ...other nav-items... -->
                <?php if(isset($_SESSION["uname"])): ?>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link logout-btn">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    
    </nav>
</div>
</body>
</html>