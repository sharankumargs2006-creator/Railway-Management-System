<?php 
 
    // registration page
    include('DBConnection.php'); 
 
if(isset($_POST['register'])){
    //$err_uname="";
    $uname = $_POST['username'];
    $pass = $_POST['password1'];
    //$pass = $_POST['password2'];
    $secque = $_POST['secque'];
    $secans = $_POST['secans'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    
    // Gmail validation
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        $_SESSION['email_error'] = true;
        header("Location: register.php");
        exit();
    }
    
    $phno = $_POST['phno'];
    $area = $_POST['add_area'];
    $pin = $_POST['add_pin'];
    $city = $_POST['add_city'];
    $state = $_POST['add_state'];


    // query for checked whether username is already registered or not
    $sql_uname = "SELECT *FROM user where username = '$uname'";
    $result = $conn->query($sql_uname);
    if( $result->num_rows > 0 ){
        $err_uname= "Username is already registered";
    }
    else{
        $sql="INSERT INTO user (username, password, first_name, middle_name, last_name, gender, date_of_birth, email, mobile_number, area, pincode, city, state, security_question, security_answare) VALUES ( '$uname', '$pass', '$fname', '$mname', '$lname', '$gender', '$dob', '$email', '$phno', '$area', '$pin', '$city', '$state', '$secque', '$secans')";
        

        if($conn->query($sql)== TRUE){
            // Replace the alert with redirect
            $_SESSION['registration_success'] = true;
            header("Location: index.php");
            exit();
        }
        else{
            echo "<script>alert('Error during registration');</script>";

        }
    }
}

// Modify the PHP validation section
if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Only check if username exists
    $check_sql = "SELECT username FROM user WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if($result->num_rows > 0) {
        $er_uname = "Username already exists";
    } else {
        // Insert user data
        $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        
        if($stmt->execute()) {
            header("Location: login.php?registration=success");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
    <script src="asset/js/validation.js"></script>

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
        }

        .text-red {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .bg-black{
            background-color:black;
        }
        /* .container{
            padding-top: 40px;
        } */
        .text-main h5, .text-main{
            font-size: 16px;
            font-weight: bold;
            color: #333;
            font-family: serif;
        }

        .password-toggle {
            cursor: pointer;
            padding: 0.375rem 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-left: none;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            background-color: #e9ecef;
        }

        .password-toggle.active {
            color: #0066B3;
        }

        .input-group-append {
            margin-left: -1px;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            border: 1px solid #ced4da;
            border-radius: 0 0.25rem 0.25rem 0;
        }
    </style>

</head>
<body>

	<!-- header navbar -->
    <?php include('header.php') ?>

   <!-- light background color -->
<form action="" name="regForm" onsubmit="return(validation());" method="post" class="bg-light" autocomplete="off">

    <!-- container -->
    <div class="container">
        <div class="row">
            
            <!-- title individual registration -->
    <!-- 1st row start -->
            <div class="col-12">
                <div class="text-blue mt-5 mb-4">
                    <h2>Individual Registration</h2>
                </div>
                <hr> <!-- horizontal line -->
            </div> <!-- 1st row ends -->

            <div class="col-12">
               <div class="text-dark">
                    <h6>Please use a vaild E-Mail and mobile number in registration</h6>
                </div>
                <hr>     
            </div>

    <!--  2nd row start -->
            <!-- username -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Username<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 ">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="uname" name="username">
                </div>
                <!-- error code -->
                <div class="text-red">
                    <span id="er_uname"><?php if(isset($err_uname)){ echo "$err_uname"; } ?></span>
                </div>
            </div> <!-- username ends -->

            <!-- password -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Password<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <input class="form-control" type="password" id="pass1" name="password1">
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" id="toggle-pass1">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="text-red">
                    <span id="er_pass1"></span>
                </div>
            </div> <!-- password ends --> 
    <!-- 2nd row ends -->


    <!-- Horizontal row -->
            <div class="col-12"><hr></div>


    <!-- 3rd row  -->
            <!-- Confirm password -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Confirm Password<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <input class="form-control" type="password" id="pass2" name="password2">
                    <div class="input-group-append">
                        <span class="input-group-text password-toggle" id="toggle-pass2">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="text-red">
                    <span id="er_pass2"></span>
                </div>
            </div> <!-- password ends --> 

    <!--3rd row ends -->
            

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>


    <!-- 4th row -->
            <!-- security question -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Security Question<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 ">
                <div class="text-main input-group">
                    <select class="custom-select" id="secQue" name="secque">
                        <option class="" value="default">Select Security Question</option>
                        <option class="" value="1">first mobile no</option>
                        <option class="" value="2">your pet name</option>
                        <option class="" value="3">your nick name</option>
                        <option class="" value="4">your favourite teacher name</option>
                    </select>
                </div>
                <!-- er_secque code -->
                <div  class="text-red">
                    <span id="er_secque"></span>
                </div>
            </div> <!-- security question ends -->

            <!-- security answare -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Security Answare<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="secAns" name="secans">
                </div>
                <!-- er_secans code -->
                <div class="text-red">
                    <span id="er_secans"></span>
                </div>
            </div> <!-- security answare ends --> 
    <!-- 4th row ends -->


    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!--5th row  -->
            <!-- alert personal details -->
            <div class="col-12 alert alert-primary text-bold">Personal Details</div>
            <!-- name field -->
            <div class="col-12 col-sm-3 col-md-3">
                <div class="text-main text-center">
                    <h5>Name<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div><!-- name ends  -->

            <!-- input field of names -->
            <div class="col-12 col-sm-3 col-md-3 ">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="fn" name="fname" placeholder="First Name">
                </div>
                <!-- er_fname code -->
                <div  class="text-red">
                    <span id="er_fname"></span>
                </div>
            </div> 

            <div class="col-12 col-sm-3 col-md-3 ">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="mn" name="mname" placeholder="Middle Name">
                </div>
                <!-- er_fname code -->
                <div  class="text-red">
                    <span id="er_mname"></span>
                </div>
            </div> 

            <div class="col-12 col-sm-3 col-md-3 ">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="ln" name="lname" placeholder="Last Name">
                </div>
                <!-- er_fname code -->
                <div  class="text-red">
                    <span id="er_lname"></span>
                </div>
            </div>  <!-- input field ends -->
    <!-- 5th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!-- 6th row start -->
            <!-- Gender -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Gender<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- male -->
            <div class="col-6 col-sm-2">
                <div class="text-main input-group">
                    <input class="" type="radio" name="gender" value="M" required>
                    <span class="text-main">&nbsp;&nbsp;Male</span>
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_gender"></span>
                </div>
            </div>

            <!-- female -->
            <div class="col-6 col-sm-2">
                <div class="text-main input-group">
                    <input class="" type="radio" name="gender" value="F" required>
                    <span class="text-main">&nbsp;&nbsp;Female</span>
                </div>
            </div>

            <!-- Transgender -->
            <!-- <div class="col-12 col-sm-4">
                <div class="text-main input-group">
                    <input class="" type="radio" name="gender" value="male">
                    <span class="text-main">&nbsp;&nbsp;Transgender</span>
                </div>
            </div> -->


    <!-- 6th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!-- 7th row -->
             <!-- DOB -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Date Of Birth<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- input tag -->
            <div class="col-12 col-sm-3">
                <div class="text-main input-group">
                    <input class="form-control" type="date" id="date" name="dob">
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_dob"></span>
                </div>
            </div>
    <!-- 7th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!-- 8th row -->
            <!-- email -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Email<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- email input-->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <input class="form-control <?php echo isset($_SESSION['email_error']) ? 'is-invalid' : ''; ?>" 
                           type="email" 
                           id="mail" 
                           name="email" 
                           pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
                           placeholder="Enter your Gmail address"
                           title="Please enter a valid Gmail address"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
                </div>
                <div class="text-red">
                    <span id="er_email">
                        <?php 
                        if(isset($_SESSION['email_error'])) {
                            echo "Please enter a valid Gmail address (@gmail.com)";
                            unset($_SESSION['email_error']);
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- phone number -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Mobile No<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- number input -->
            <div class="col-12 col-sm-6 col-md-3">
                <!-- 91 label -->
                <div class="text-main input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text alert-dark text-dark">+91</span>
                </div>
                    <input class="form-control" type="text" id="mono" maxlength="10" name="phno">
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_phno"></span>
                </div>
            </div>
    <!-- 8th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!-- 9th row -->
            <!-- alert personal details -->
            <div class="col-12 alert alert-primary text-bold">Residential Address</div>
            <!-- area -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Area<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- area input-->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <textarea class="form-control" type="text" id="area" name="add_area" ></textarea>
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_addarea"></span>
                </div>
            </div>

            <!-- pincode  -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>Pincode<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- pincode input -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                    <input class="form-control" type="text" id="pin" maxlength="
                    6" name="add_pin">
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_addpin"></span>
                </div>
            </div>
    <!-- 9th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>

    <!-- 10th row -->
            <!-- city -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>City<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- city input-->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                     <select class="custom-select" id="city" name="add_city">
                        <option class="" value="default">Select City</option>
                        <option class="" value="">Gadag</option>
                        <option class="" value="">Dharwad</option>
                        <option class="" value="">Bagalkot</option>
                        <option class="" value="">Bangalore</option>
                        <option class="" value="">Belagavi</option>
                        <option class="" value="">Mysore</option>
                        <option class="" value="">Uttara Kannada</option>
                        <option class="" value="">Haveri</option>
                        <option class="" value="">Chitradurga</option>
                        <option class="" value="">Davangere</option>
                        <option class="" value="">Vijayapura</option>

                    </select>
                </div>

                <!-- error code -->
                <div class="text-red">
                    <span  id="er_addcity"></span>
                </div>
            </div>

            <!-- state -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main text-center">
                    <h5>State<span class="text-red text-strong">&nbsp;*&nbsp;</span>:</h5>
                </div>
            </div>

            <!-- state input -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="text-main input-group">
                     <select class="custom-select" id="state" name="add_state">
                        <option class="" value="default">Select State</option>
                        <option class="" value="">Karnataka</option>
                    </select>
                </div>

                <!-- error code -->
                <div  class="text-red">
                    <span id="er_addstate"></span>
                </div>
            </div>
    <!-- 10th row ends -->

    <!-- Horizontal row -->
            <div class="col-12"><hr></div>
            <div class="col-12"><hr></div>

    <!-- 11th row  -->
            <!-- register button -->
            <div class="col-12">
                <div class="text-main text-center">
                    <input class="btn btn-success" type="submit" name="register" value="Register">
                    <input class="btn btn-success" type="reset" name="cancel" value="Reset">
                </div>
            </div>

    <!-- 11th row ends -->

        </div> <!-- row ends -->
    </div> <!-- container ends -->
    <br>
    <br>
</form> <!-- main div ends -->





    <!-- Footer -->
    <?php include('footer.html') ?>
	
</body>
</html>




<?php
function sendSMS($phone, $message) {
    $apiKey = 'hzxktPjGQ39aT6FpJ8qyNE4vIm2MbBAgOrWceYKZLCiXRnSHU55zoAnh9GYW1HV8p7vXD0xfIsmTeKPd'; // Replace with your key
    $fields = array(
        "sender_id" => "FSTSMS",
        "message" => $message,
        "language" => "english",
        "route" => "p",
        "numbers" => $phone,
    );
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: $apiKey",
            "accept: */*",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        error_log("SMS Error: " . $err);
        return false;
    } else {
        return true;
    }
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle password visibility
    function setupPasswordToggle(passwordId, toggleId) {
        const passwordInput = document.getElementById(passwordId);
        const toggleButton = document.getElementById(toggleId);
        const toggleIcon = toggleButton.querySelector('i');

        toggleButton.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.getAttribute('type');
            passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
            
            // Toggle icon
            toggleIcon.classList.toggle('fa-eye-slash');
            toggleIcon.classList.toggle('fa-eye');
            
            // Toggle active state
            toggleButton.classList.toggle('active');
        });
    }

    // Setup toggle for both password fields
    setupPasswordToggle('pass1', 'toggle-pass1');
    setupPasswordToggle('pass2', 'toggle-pass2');
});
</script>
