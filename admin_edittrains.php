<?php 
// Fix the session check logic - it was reversed
session_start();
include('DBConnection.php');

if(!isset($_SESSION["admin_uname"])){
    header("Location: Adminlogin.php");
    exit();
}

include("adminheader2.html");

$train_no='';
$result = null; // Initialize $result variable
$count = 1;

// execute if user clicked on show btn after entering train number
if(isset($_GET['show'])){
   if(isset($_GET['train_no'])) {
        $train_no = mysqli_real_escape_string($conn, $_GET['train_no']);
        
        // Modified query with proper column checks
        $sql1 = "SELECT 
                s.*,
                t.train_no,
                t.train_name,
                t.fare_ac,
                t.fare_sleeper,
                t.fare_general,
                t.seats_ac_total,
                t.seats_ac_available,
                t.seats_sleeper_total,
                t.seats_sleeper_available,
                t.seats_general_total,
                t.seats_general_available
                FROM station s 
                INNER JOIN train t ON s.train_no = t.train_no 
                WHERE t.train_no = ?";
                
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("s", $train_no);
        
        try {
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows == 0) {
                echo "<script>alert('Train Not Found');</script>";
            }
        } catch(Exception $e) {
            echo "<script>alert('Database Error: " . $e->getMessage() . "');</script>";
        }
    }
}


// execute when admin clicked on update btn after editing train details
if(isset($_POST['update'])) {
    if(isset($_POST['trainno']))
        $train_no = $_POST['trainno'];
    $station_no = $_POST['station_no'];


    $train_name  = ucwords($_POST['trainname']);
    $fare_ac  = $_POST['fare_ac'];
    $fare_sleeper  = $_POST['fare_sleeper'];
    $fare_general  = $_POST['fare_general'];

            // calculating duration from two given times
    $duration = round(abs(strtotime($depart) - strtotime($arr)) / 3600,1);

    // query for update train details
    $sql2 = "UPDATE train SET 
             train_name = ?,
             fare_ac = ?,
             fare_sleeper = ?,
             fare_general = ?,
             seats_ac_total = ?,
             seats_sleeper_total = ?,
             seats_general_total = ?
             WHERE train_no = ?";

    $stmt1 = $conn->prepare($sql2);
    $stmt1->bind_param("sdddiiii", 
        $train_name,
        $fare_ac,
        $fare_sleeper,
        $fare_general,
        $_POST['seats_ac_total'],
        $_POST['seats_sleeper_total'],
        $_POST['seats_general_total'],
        $train_no
    );
    
    if($stmt1->execute()) {
        // Then update station details
        $sql3 = "UPDATE station SET source=?, destination=?, 
                 fare_ac=?, fare_sleeper=?, fare_general=?, 
                 arrival_time=?, depart_time=?, duration=? 
                 WHERE train_no=? AND station_no=?";
        
        $stmt2 = $conn->prepare($sql3);
        $stmt2->bind_param("ssiiissiii", 
            $src,
            $dest,
            $fare_ac,
            $fare_sleeper,
            $fare_general,
            $arr,
            $depart,
            $duration,
            $train_no,
            $station_no
        );
        
        if($stmt2->execute()) {
            echo "<script>alert('Train details updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating station details: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error updating train: " . $conn->error . "');</script>";
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


    </style>

</head>
<body class="bg-img">
    <div class="row">
        <div class="col-12 col-sm-3">    
           <?php include("adminmenu.html"); ?>
        </div>
        <div class="col-12 col-sm-9">


            <form name="payForm" onsubmit="return(pnrvalid());" class="m-5 p-5 border bg-light" action="" method="get">
                <div class="row">
                    <div class="col-12">
                        <h4 class="navbar-brand text-primary">Train Number:</h4>
                    </div>
                    <div class="col-8">
                        <input class="form-control" type="text" placeholder="Enter Train Number" name="train_no" id="train" maxlength="5">
                        <span id="er_train" class="text-red"></span>
                    </div>
                    <div class="col-4">      
                        <input type="submit" class="btn btn-dark text-light" value="Get Details" name="show">
                    </div>
                </div>
            </form>



                    <?php 
                        if(isset($result) && $result->num_rows > 0){
                            while($data = $result->fetch_assoc()){
                     ?>
            <form action="" method="post" name="train">
                <div class="row bg-light m-3 p-4 border-radius">
                    <!-- 1st row -->
            <div class="col-12">
               <div class="text-danger text-bold bg-light">
                <hr>     
                    <h6 class="font-weight-bold">Note: You can't edit train number</h6>
                <hr>     
                </div>
            </div>

                     <input type="hidden" name="station_no" value="<?php echo $data['station_no']; ?>">
                    <span class="text-bold"><?php echo "station ".$count; ?></span>
                    <div class="col-12"><hr></div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Train No<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" type="text" value="<?php echo $data['train_no'] ?>" id="trainnoid" disabled name="trainno" maxlength="5">
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
                            <input name="trainname" value="<?php echo $data['train_name'] ?>" type="text" id="trainnameid" class="form-control" >
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
                            <input class="form-control" value="<?php echo $data['source'] ?>" type="text" id="srcid" name="src">
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
                            <input class="form-control" value="<?php echo $data['destination'] ?>" type="text" id="destid" name="dest">
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
                            <input class="form-control" value="<?php echo $data['depart_time'] ?>" type="text" id="departid" name="depart">
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
                            <input class="form-control" value="<?php echo $data['arrival_time'] ?>" type="text" id="arrid" name="arr">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_arr"></span>
                        </div>
                    </div>

                    <div class="col-12"><hr></div>

                     <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Fare (AC)<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" value="<?php echo $data['fare_ac'] ?>" type="text" id="fareid" name="fare_ac">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_fare_ac"></span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main ">
                            <h5>Fare (Sleeper)<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" value="<?php echo $data['fare_sleeper'] ?>" type="text" id="fareid" name="fare_sleeper">
                        </div>
                        <!-- er_pass1 code -->
                        <div  class="text-red">
                            <span id="er_fare_sleeper"></span>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main">
                            <h5>Fare (General)<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="text-main input-group">
                            <input class="form-control" 
                                   type="text" 
                                   id="fareGeneralId" 
                                   name="fare_general" 
                                   value="<?php echo $data['fare_general'] ?>">
                        </div>
                        <div class="text-red">
                            <span id="er_fare_general"></span>
                        </div>
                    </div>


                    <div class="col-12"><hr></div>
<div class="col-12">
    <h4 class="text-primary mb-3">Seat Configuration</h4>
</div>

<!-- AC Class Seats -->
<div class="col-sm-12 col-md-4">
    <div class="text-main">
        <h5>AC Class<span class="text-red text-strong">&nbsp;*&nbsp;</span></h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_ac_total" 
                   id="seatsAcTotal"
                   value="<?php echo $data['seats_ac_total'] ?? '0'; ?>" 
                   min="0" 
                   required>
        </div>
    </div>
</div>

<!-- Sleeper Class Seats -->
<div class="col-sm-12 col-md-4">
    <div class="text-main">
        <h5>Sleeper Class<span class="text-red text-strong">&nbsp;*&nbsp;</span></h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_sleeper_total" 
                   id="seatsSleeperTotal"
                   value="<?php echo $data['seats_sleeper_total'] ?? '0'; ?>" 
                   min="0" 
                   required>
        </div>
    </div>
</div>

<!-- General Class Seats -->
<div class="col-sm-12 col-md-4">
    <div class="text-main">
        <h5>General Class<span class="text-red text-strong">&nbsp;*&nbsp;</span></h5>
        <div class="input-group">
            <span class="input-group-text">Total Seats</span>
            <input type="number" 
                   class="form-control" 
                   name="seats_general_total" 
                   id="seatsGeneralTotal"
                   value="<?php echo $data['seats_general_total'] ?? '0'; ?>" 
                   min="0" 
                   required>
        </div>
    </div>
</div>


                    <div class="col-sm-6 col-md-3 offset-1">
                        <div class="text-main input-group">
                            <input class="btn btn-success" type="submit" value="Update Details" name="update">
                        </div>
                    </div>
                    <div class="col-12"><hr></div>

                    

                </div> <!-- row ends -->
                    
            </form>
                <?php $count++; 
                 } // while ends
                } //if ends
                else{
                    if(isset($_GET['show'])) {
                        echo "<script>alert('Train Not Found');</script>";
                    }
                }
                ?>
        </div>
    </div>
    <script>
/*
function validateSeats() {
    const classes = ['ac', 'sleeper', 'general'];
    let isValid = true;
    
    classes.forEach(classType => {
        const total = parseInt(document.getElementById(`seats${classType.charAt(0).toUpperCase() + classType.slice(1)}Total`).value);
        const available = parseInt(document.getElementById(`seats${classType.charAt(0).toUpperCase() + classType.slice(1)}Available`).value);
        const error = document.getElementById(`er_seats_${classType}`);
        
        if (available > total) {
            error.textContent = "Available seats cannot exceed total seats";
            isValid = false;
        }
    });
    
    return isValid;
}

// Add to form submit
document.querySelector('form[name="train"]').onsubmit = function(e) {
    if (!validateSeats()) {
        e.preventDefault();
        return false;
    }
    return true;
};
*/
</script>
</body>
</html>

