<?php 

    // this page helps admin to add trains in db
    session_start();

    include('DBConnection.php');

    // Change this condition - it was redirecting when user IS logged in
    if(!isset($_SESSION["admin_uname"])){
        header("Location: Adminlogin.php");
        exit();
    }

    include("adminheader2.html");

    // when user clicked add btn then if execute
    if(isset($_POST['add'])){
        // Sanitize and validate inputs
        $train_no = mysqli_real_escape_string($conn, $_POST['trainno']);
        $train_name = mysqli_real_escape_string($conn, ucwords($_POST['trainname']));
        $src = mysqli_real_escape_string($conn, ucwords($_POST['src']));
        $dest = mysqli_real_escape_string($conn, ucwords($_POST['dest']));
        $depart = mysqli_real_escape_string($conn, $_POST['depart']);
        $arr = mysqli_real_escape_string($conn, $_POST['arr']);
        
        // Validate fare inputs with default values
        $fare_ac = !empty($_POST['fare_ac']) ? (int)$_POST['fare_ac'] : 0;
        $fare_sleeper = !empty($_POST['fare_sleeper']) ? (int)$_POST['fare_sleeper'] : 0;
        $fare_general = !empty($_POST['fare_general']) ? (int)$_POST['fare_general'] : 0;

        $seats_ac_total = $_POST['seats_ac_total'];
        $seats_sleeper_total = $_POST['seats_sleeper_total'];
        $seats_general_total = $_POST['seats_general_total'];
        
        // Initially available seats = total seats
        $seats_ac_available = $seats_ac_total;
        $seats_sleeper_available = $seats_sleeper_total;
        $seats_general_available = $seats_general_total;

        // Add this check before the insert queries
        $check_sql = "SELECT train_no FROM train WHERE train_no = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $train_no);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if($result->num_rows > 0) {
            echo "<script>alert('Train number already exists! Please use a different train number.');</script>";
        } else {
            // First insert into train table
            $sql1 = "INSERT INTO train (train_no, train_name, seats_ac_total, seats_ac_available, 
            seats_sleeper_total, seats_sleeper_available, seats_general_total, 
            seats_general_available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("isiiiiii", $train_no, $train_name, 
                      $seats_ac_total, $seats_ac_available,
                      $seats_sleeper_total, $seats_sleeper_available,
                      $seats_general_total, $seats_general_available);
            
            if($stmt1->execute()) {
                // Then insert into station table
                $sql2 = "INSERT INTO station (source, destination, fare_ac, fare_sleeper, arrival_time, depart_time, duration, train_no) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = $conn->prepare($sql2);
                $duration = round(abs(strtotime($depart) - strtotime($arr)) / 3600, 1);
                
                $stmt2->bind_param("ssiissdi", 
                    $src,
                    $dest,
                    $fare_ac,
                    $fare_sleeper,
                    $arr,
                    $depart,
                    $duration,
                    $train_no
                );
                
                if($stmt2->execute()) {
                    echo "<script>alert('Train Added Successfully');</script>";
                } else {
                    echo "<script>alert('Error adding station details: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error adding train: " . $conn->error . "');</script>";
            }
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

    <!-- custom css -->
    <link rel="stylesheet" type="text/css" href="asset/css/custom.css">

    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="asset/js/jquery-3.4.1.slim.min.js"></script>
    <script src="asset/js/popper.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/validation.js"></script>
    <style>




        .logo{
            border-radius: 1000px;
        }
        div.shadow-cust{
            width: 230px;
            background-color: #DCEEFF;
       }
       .shadow-cust{
            box-shadow: 3px 3px 5px 0px #333;
       }
       i.fa-circle{
            box-shadow:inset 0px 0px 3px 0px #222;
            border-radius: 10px;  
       }
       .text-main h5, .text-main{
            font-size: 16px;
            font-weight: bold;
            color: #333;
            font-family: serif;
        }

        .input-group {
    margin-bottom: 1rem;
}

.input-group-text {
    background-color: #f8f9fa;
    min-width: 100px;
}

input[type="number"] {
    text-align: right;
    padding-right: 10px;
}

.text-main h5 {
    margin-bottom: 1rem;
    color: #333;
}

.seat-config {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 1rem;
}

    </style>

</head>
<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
    	   <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-9">
            <form action="" method="post" name="train" onsubmit="return(validtrain())">
                <div class="row bg-light m-3 p-4 border-radius">
                    <!-- 1st row -->
                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Train No<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" id="trainnoid" name="trainno" maxlength="5">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_trainno"></span>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Train Name<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input name="trainname" type="text" id="trainnameid" class="form-control" >
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_trainname"></span>
                        </div>
                    </div>

                    <!--  -->
                    <div class="col-12"><hr></div>
                     <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Source <span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" id="srcid" name="src">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_src"></span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Destination<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" id="destid" name="dest">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_dest"></span>
                        </div>
                    </div>

                    <div class="col-12"><hr></div>

                     <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Departure Time<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" id="departid" name="depart">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_depart"></span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Arrival Time<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" id="arrid" name="arr">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_arr"></span>
                        </div>
                    </div>

                    <div class="col-12"><hr></div>

                    

                    <div class="col-12"><hr></div>
<div class="col-12">
    <h4 class="text-primary mb-3">Seat Configuration</h4>
</div>

<!-- AC Class Seats -->
<div class="col-sm-6 col-md-4">
    <div class="text-main">
        <h5>AC Class Seats<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_ac_total" 
                   id="seatsAcId"
                   min="0"
                   required>
        </div>
        <div class="text-red">
            <span id="er_seats_ac"></span>
        </div>
    </div>
</div>

<!-- Sleeper Class Seats -->
<div class="col-sm-6 col-md-4">
    <div class="text-main">
        <h5>Sleeper Class Seats<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_sleeper_total" 
                   id="seatsSleeperId"
                   min="0"
                   required>
        </div>
        <div class="text-red">
            <span id="er_seats_sleeper"></span>
        </div>
    </div>
</div>

<!-- General Class Seats -->
<div class="col-sm-6 col-md-4">
    <div class="text-main">
        <h5>General Class Seats<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_general_total" 
                   id="seatsGeneralId"
                   min="0"
                   required>
        </div>
        <div class="text-red">
            <span id="er_seats_general"></span>
        </div>
    </div>
</div>

                    <div class="col-12"><hr></div>
<div class="col-sm-6 col-md-3">
    <div class="text-main">
        <h5>Fares<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
    </div>
</div>

<div class="col-sm-6 col-md-9">
    <div class="row">
        <div class="col-md-4">
            <div class="text-main input-group">
                <input class="form-control" type="text" id="fareAcId" name="fare_ac" placeholder="AC Class Fare">
            </div>
            <div class="text-red">
                <span id="er_fare_ac"></span>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="text-main input-group">
                <input class="form-control" type="text" id="fareSleeperId" name="fare_sleeper" placeholder="Sleeper Class Fare">
            </div>
            <div class="text-red">
                <span id="er_fare_sleeper"></span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="text-main input-group">
                <input class="form-control" type="number" id="fareGeneralId" name="fare_general" placeholder="General Class Fare">
            </div>
            <div class="text-red">
                <span id="er_fare_general"></span>
            </div>
        </div>
    </div>
</div>

                    <div class="col-sm-6 col-md-3 offset-1">
                        <div class="text-main input-group">
                            <input class="btn btn-success" type="submit" value="Add Details" name="add">
                        </div>
                    </div>

                    

                </div> <!-- row ends -->
                    
            </form>
        </div>
    </div>
</body>
</html>

