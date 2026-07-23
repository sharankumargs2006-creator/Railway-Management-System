<?php 

    // this page showing train-list to user on there entered inputs
    session_start();
    if(!isset($_SESSION["uname"])){
        header("Location: ./index.php?logout=1");
        exit(); // Add exit after redirect
    }

    include('DBConnection.php');
    include('Details.php');

    include("header2.php");
    
    //taking this data from index page and also from same page    
    // Sanitize inputs before using in SQL query
$src = mysqli_real_escape_string($conn, ucwords($_POST['src']));
$dest = mysqli_real_escape_string($conn, ucwords($_POST['dest']));
$class = mysqli_real_escape_string($conn, ucwords($_POST['class']));
$date = mysqli_real_escape_string($conn, $_POST['date']);

// Use prepared statement
$sql = "SELECT t.train_no, t.train_name, s.source, s.arrival_time, s.destination,
        s.depart_time, s.duration, t.seat_avail, t.class, s.station_no,
        s.fare_all, s.fare_ac, s.fare_sleeper,
        t.seats_ac_available, t.seats_sleeper_available, t.seats_general_available 
        FROM train t, station s 
        WHERE s.source = ? AND s.destination = ? AND s.train_no = t.train_no";

    // Add try-catch for database operations
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $src, $dest);
        $stmt->execute();
        $result = $stmt->get_result();
    } catch(Exception $e) {
        error_log("Database error: " . $e->getMessage());
        echo "<div class='alert alert-danger m-3'>An error occurred while fetching train details.</div>";
        exit();
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
    <script src="asset/js/train-list.js"></script>
    <style>

         #bg-custom{
            background-color:rgba(2,2,2,0.8);
        }
        #m-cust{
            margin-right: 250px;
            margin-top: 60px; 
        }
        .bg-black{
            background-color:black;
        }
        .bg-img{
            background-image: url('asset/img/7.jpg');
            /*background-repeat: no-repeat;*/
            background-size: 100%;
            max-width: 1300px;
            min-height: 700px;
            /*opacity: 0.8;*/
        }
        @media(max-width: 400px){
            .bg-img{
                background-image: url('asset/img/5.jpg');
                background-size: auto;
                background-repeat: no-repeat;
                /*background-position: center*/

            }
        }

        .bg-img2{
            background-image:url('asset/img/5.jpg'); 
            background-size: 100%;
        }
        .pnr{
            background-color: white;
            color: black;
            /*width: 340px;*/
            padding-top: 10px;
            box-shadow: 2px 2px 18px 10px #222;
            border-radius: 2px;
        }
        
        .fs-1{
            font-size: 42px;
            font-family: Tempus Sans ITC;
            margin-top: 50px;
        }
        .fs-2{
            font-size: 18px;
            font-family: Yu Gothic Light;
            font-weight: lighter;
            margin-bottom: 50px; 
        }
        .form-control{
            width: 80px;
        }

        .card-body {
    padding: 1rem;
}

.font-light {
    font-size: 0.9rem;
    line-height: 1.5;
    white-space: nowrap;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-muted {
    color: #6c757d !important;
}

.align-items-center {
    align-items: center !important;
}

.mb-1 {
    margin-bottom: 0.25rem !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

.mr-2 {
    margin-right: 0.5rem !important;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.border-left {
    border-left: 1px solid #dee2e6;
}

.opacity-75 {
    opacity: 0.75;
}

.fare-details {
    font-size: 0.9rem;
}

.fare-details .small {
    font-size: 0.8rem;
    line-height: 1.4;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
}

.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 80%;
    color: #dc3545;
}

.is-invalid ~ .invalid-feedback {
    display: block;
}

.seat-availability {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 5px;
}

.availability-indicator {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
}

.available {
    background-color: #d4edda;
    color: #155724;
}

.limited {
    background-color: #fff3cd;
    color: #856404;
}

.full {
    background-color: #f8d7da;
    color: #721c24;
}

.seat-class-info {
    text-align: center;
    margin-bottom: 15px;
    font-weight: 500;
}

.seat-map-container {
    display: grid;
    grid-template-columns: repeat(8, 1fr); /* Increased columns */
    gap: 10px;
    padding: 15px;
    background: #ffffff;
    border-radius: 8px;
}

.seat-modal-content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.seat-layout {
    padding: 20px;
    background: #fff;
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}

.booking-footer {
    padding: 15px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    position: sticky;
    bottom: 0;
}

.seat-row {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px;
}

.seat-section {
    display: flex;
    gap: 10px;
}

.passage {
    width: 30px;
    margin: 0 15px;
    border-left: 1px dashed #ccc;
}

.seat-item {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #28a745;
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 12px;
    font-weight: bold;
}

.seat-booked {
    background: #f8d7da;
    border-color: #dc3545;
    cursor: not-allowed;
}

.seat-selected {
    background: #007bff;
    border-color: #0056b3;
    color: white;
}
    </style>

</head>
<body class="bg-light">
    
    <!-- include header -->
	<!-- <?php //include('header2.php'); ?> -->


<div class="container-fluid bg-light shadow">
    <div class="row">
        <div class="col-12 col-sm-1 mt-5 pt-4">
            <img src="asset/img/logo/rail_icon.png">
        </div>
        <div class="col-12 col-sm-11 pt-5 pb-5">
            <form action="train_list.php" method="post" class="row">
                <div class="col-3">
                    <label class="text-bold" for="origin">Origin</label>
                    <input class="form-control" 
                           type="text" 
                           id="origin"
                           name="src" 
                           value="<?php echo isset($src) ? htmlspecialchars($src) : ''; ?>" 
                           pattern="[A-Za-z\s]+" 
                           title="Please enter a valid city name (letters and spaces only)"
                           required>
                    <div class="invalid-feedback">
                        Please enter a valid origin station
                    </div>
                </div>
                <div class="col-3">
                    <label class="text-bold">Destination</label>
                    <input class="form-control" 
                           name="dest" 
                           type="text" 
                           value="<?php echo htmlspecialchars($dest); ?>" 
                           required>
                </div>
                <div class="col-2">
                    <label class="text-bold">Journey Class</label>
                    <select class="custom-select hvr-shadow" name="class" onchange="this.form.submit()">
                        <option value="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($class); ?></option>
                        <option value="AC">AC</option>
                        <option value="SL">Sleeper(SL)</option>
                        <option value="General">General</option>
                    </select>
                </div>
                <div class="col-2">
                    <label class="text-bold">Journey Date</label>
                    <div class="input-group">
                        <input class="form-control" 
                               type="date" 
                               name="date" 
                               id="journey-date"
                               value="<?php echo htmlspecialchars($date); ?>"
                               min="<?php echo date('Y-m-d'); ?>"
                               required>
                        <div class="invalid-feedback">
                            Please select today or a future date
                        </div>
                    </div>
                </div>
                <div class="col-2 mt-4 pt-2">
                    <input class="form-control btn-blue text-light hvr-shadow" 
                           type="submit" 
                           value="Modify Search" 
                           name="modify">
                </div>
            </form>
        </div>
    </div> <!-- row ends -->

</div> <!-- container-fluid ends -->
<!-- Where is my Train Icon -->
<div id="train-tracker-btn" title="Track your train" style="
    position: fixed;
    bottom: 150px;
    right: 20px;
    background-color: #007bff;
    color: white;
    padding: 12px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 9999;
">
    <i class="fas fa-train"></i>
</div>

<!-- Train Tracking Modal -->
<div id="train-modal" style="
    display: none;
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 600px;
    background: white;
    border: 2px solid #007bff;
    border-radius: 10px;
    z-index: 10000;
    padding: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
">
    <div style="text-align: right;">
        <button id="close-train-modal" style="border:none; background:none; font-size: 20px; cursor:pointer;">&times;</button>
    </div>
    <h3 style="margin-top: 0; color: #007bff;">Train Location Tracker</h3>
    <div id="dummy-map" style="width: 100%; height: 300px; border-radius: 10px;"></div>
    <div style="margin-top: 10px; font-size: 14px;">
        <strong>Train:</strong> Chennai Express<br>
        <strong>Current Status:</strong> Arriving at Tumkur<br>
        <strong>Estimated Arrival:</strong> 12:45 PM<br>
        <strong>Delay:</strong> 5 minutes
    </div>
</div>

<!-- filepath: c:\xampp\htdocs\Project\Railway-System-master\train_list.php -->
<!-- Seat Map Modal -->
<div class="modal fade" id="seatMapModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Seats</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="seatMapContainer" class="seat-map-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- container 2nd -->
<div class="container bg-light border-left border-right mt-5">
    <!-- Card Header -->
    <div class="row border bg-white mx-1 mb-2 p-2">
        <div class="col-md-3">
            <strong>Train name & no</strong>
        </div>
        <div class="col-md-2 text-center">
            <strong>Departs</strong>
        </div>
        <div class="col-md-2 text-center">
            <strong>Arrival</strong>
        </div>
        <div class="col-md-2 text-center">
            <strong>Duration</strong>
        </div>
        <div class="col-md-2 text-center">
            <strong>Fares</strong>
        </div>
        <div class="col-md-1">
        </div>
    </div>

    <!-- Train Cards -->
    <?php if($result->num_rows > 0) {
        while($data = $result->fetch_assoc()) { ?>
        <div class="card mb-3 mx-1">
            <form action="./psg_details.php" method="post">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <!-- Train Details -->
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <img src="asset/img/logo/rail_icon.png" width="25" class="mr-3">
                                <div>
                                    <h6 class="mb-1 font-weight-bold"><?php echo ucwords($data["train_name"]); ?> 
                                        <small>(<?php echo $data["train_no"]; ?>)</small>
                                    </h6>
                                    <small class="text-muted d-block">
                                        <?php echo ucwords($data["source"]); ?> → <?php echo ucwords($data["destination"]); ?>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Time Details -->
                        <div class="col-md-2 text-center border-left">
                            <img src="asset/img/logo/depar.png" width="20" class="mb-2 opacity-75">
                            <div class="font-weight-bold"><?php echo $data["depart_time"]; ?></div>
                        </div>

                        <div class="col-md-2 text-center border-left">
                            <img src="asset/img/logo/arrive.png" width="20" class="mb-2 opacity-75">
                            <div class="font-weight-bold"><?php echo $data["arrival_time"]; ?></div>
                        </div>

                        <div class="col-md-2 text-center border-left">
                            <img src="asset/img/logo/time.png" width="20" class="mb-2 opacity-75">
                            <div class="text-muted">
                                <?php 
                                    $duration = calculateDuration($data["depart_time"], $data["arrival_time"]);
                                    echo $duration;
                                    // Update the hidden duration field
                                    $data["duration"] = $duration;
                                ?>
                            </div>
                        </div>

                        <!-- Fare Details -->
                        <div class="col-md-2 text-center border-left">
                            <img src="asset/img/logo/rupee.png" width="20" class="mb-2">
                            <div class="fare-details">
                                <?php 
$selected_class = strtoupper($class);
switch($selected_class) {
    case 'AC':
        echo "<div class='font-weight-bold'>AC: ₹" . $data['fare_ac'] . "</div>";
        $fare_to_pass = $data['fare_ac'];
        break;
    case 'SL':
        echo "<div class='font-weight-bold'>SL: ₹" . $data['fare_sleeper'] . "</div>";
        $fare_to_pass = $data['fare_sleeper'];
        break;
    case 'GENERAL':
        echo "<div class='font-weight-bold'>General: ₹" . $data['fare_all'] . "</div>";
        $fare_to_pass = $data['fare_all'];
        break;
    default:
        echo "<div class='font-weight-bold'>General: ₹" . $data['fare_all'] . "</div>";
        $fare_to_pass = $data['fare_all'];
        break;
}
?>
                            </div>
                        </div>

                        <!-- Book Button -->
                        <div class="col-md-1 text-center">
                            <?php if ($data["seat_avail"] > 0) { ?>
                                <button type="button" 
                                        class="btn btn-primary btn-sm"
                                        onclick="viewSeats('<?php echo $data['train_no']; ?>', '<?php echo $selected_class; ?>')">
                                    Book Now
                                </button>
                            <?php } else { ?>
                                <button type="button" disabled class="btn btn-secondary btn-sm">Not Available</button>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="station_no" value="<?php echo htmlspecialchars($data["station_no"]); ?>">
                    <input type="hidden" name="fare" value="<?php echo htmlspecialchars($fare_to_pass); ?>">
                    <input type="hidden" name="src" value="<?php echo htmlspecialchars($src); ?>">
                    <input type="hidden" name="dest" value="<?php echo htmlspecialchars($dest); ?>">
                    <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                    <input type="hidden" name="train_name" value="<?php echo htmlspecialchars($data["train_name"]); ?>">
                    <input type="hidden" name="train_no" value="<?php echo htmlspecialchars($data["train_no"]); ?>">
                    <input type="hidden" name="dep_time" value="<?php echo htmlspecialchars($data["depart_time"]); ?>">
                    <input type="hidden" name="arr_time" value="<?php echo htmlspecialchars($data["arrival_time"]); ?>">
                    <input type="hidden" name="duration" value="<?php echo htmlspecialchars($data["duration"]); ?>">
                </div>
            </form>
        </div>
    <?php }
    } else { ?>
        <div class="alert alert-info m-3">No trains available for the selected route.</div>
    <?php } ?>
</div>
    

    <?php 
        // Function to calculate duration between two times in HH:MM format
        function calculateDuration($depart_time, $arrival_time) {
            // Convert times to 24-hour format
            $depart = DateTime::createFromFormat('g:i A', $depart_time);
            $arrive = DateTime::createFromFormat('g:i A', $arrival_time);
            
            if (!$depart || !$arrive) {
                // If parsing fails, try 24-hour format
                $depart = DateTime::createFromFormat('H:i', $depart_time);
                $arrive = DateTime::createFromFormat('H:i', $arrival_time);
                
                if (!$depart || !$arrive) {
                    return "Invalid time format";
                }
            }

            // If arrival is less than departure, assume arrival is next day
            if ($arrive < $depart) {
                $arrive->modify('+1 day');
            }

            $interval = $depart->diff($arrive);
            
            $hours = $interval->h;
            $minutes = $interval->i;
            
            if ($interval->days > 0) {
                $hours += $interval->days * 24;
            }
            
            return sprintf("%02d hr %02d min", $hours, $minutes);
        }

        function avail(){
            return true;
        }
     ?>


    <!-- include footer -->
    <?php include('footer.html'); ?>
	


<script>
// Add client-side validation
function validateForm() {
    const src = document.getElementById('origin').value;
    const dest = document.getElementById('destination').value;
    const date = document.getElementById('journey-date').value;
    
    if(src === dest) {
        alert('Source and destination cannot be same');
        return false;
    }
    
    if(new Date(date) < new Date()) {
        alert('Please select a future date');
        return false;
    }
    
    return true;
}

// Update the viewSeats function
function viewSeats(trainNo, selectedClass) {
    fetch(`get_seats.php?train_no=${trainNo}&class=${selectedClass}`)
    .then(response => response.json())
    .then(data => {
        let seatMapHtml = `
            <div class="seat-modal-content">
                <div class="seat-layout">`;

        const seatsPerRow = 6;
        const totalRows = Math.ceil(data.totalSeats / seatsPerRow);
        let seatNumber = 1;

        // Generate seat rows
        for(let row = 1; row <= totalRows; row++) {
            seatMapHtml += `
                <div class="seat-row">
                    <div class="row-number">${row}</div>
                    <div class="seat-section">`;

            // Left side seats
            for(let i = 1; i <= 3 && seatNumber <= data.totalSeats; i++) {
                const seatLabel = `${data.prefix}${String(seatNumber).padStart(2, '0')}`;
                const isBooked = data.bookedSeats.includes(seatLabel);
                
                seatMapHtml += `
                    <div class="seat-item ${isBooked ? 'seat-booked' : ''}" 
                         data-seat="${seatLabel}" 
                         onclick="${!isBooked ? 'selectSeat(this)' : ''}" 
                         title="${isBooked ? 'Booked' : 'Available'}">
                        ${seatLabel}
                    </div>`;
                seatNumber++;
            }

            seatMapHtml += `
                    </div>
                    <div class="passage"></div>
                    <div class="seat-section">`;

            // Right side seats
            for(let i = 1; i <= 3 && seatNumber <= data.totalSeats; i++) {
                const seatLabel = `${data.prefix}${String(seatNumber).padStart(2, '0')}`;
                const isBooked = data.bookedSeats.includes(seatLabel);
                
                seatMapHtml += `
                    <div class="seat-item ${isBooked ? 'seat-booked' : ''}" 
                         data-seat="${seatLabel}"
                         onclick="${!isBooked ? 'selectSeat(this)' : ''}" 
                         title="${isBooked ? 'Booked' : 'Available'}">
                        ${seatLabel}
                    </div>`;
                seatNumber++;
            }

            seatMapHtml += `
                    </div>
                </div>`;
        }

        seatMapHtml += `
                </div>
                <div class="booking-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="confirmBooking('${trainNo}', '${selectedClass}')">
                        Book Selected Seats
                    </button>
                </div>
            </div>`;

        document.getElementById('seatMapContainer').innerHTML = seatMapHtml;
        $('#seatMapModal').modal('show');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load seat layout');
    });
}

function selectSeat(seatElement) {
    // Toggle selection
    if (seatElement.classList.contains('seat-selected')) {
        seatElement.classList.remove('seat-selected');
        seatElement.style.transform = 'scale(1)';
    } else {
        seatElement.classList.add('seat-selected');
        seatElement.style.transform = 'scale(1.1)';
    }
    
    updateBookingForm();
}

// Update the updateBookingForm function
function updateBookingForm() {
    const selectedSeats = document.querySelectorAll('.seat-selected');
    const selectedSeatInfo = document.getElementById('selectedSeatInfo');
    const noSeatSelected = document.getElementById('noSeatSelected');
    
    if (selectedSeats.length > 0) {
        const trainNo = selectedSeats[0].dataset.train;
        const seatClass = document.querySelector('.seat-class-info h5').textContent.trim();
        
        // Show selected seats info
        selectedSeatInfo.classList.remove('d-none');
        noSeatSelected.classList.add('d-none');
        
        // Create selected seats list
        let seatsListHtml = '<div class="selected-seats-list">';
        selectedSeats.forEach(seat => {
            seatsListHtml += `
                <div class="mb-2">
                    <i class="fas fa-chair mr-2"></i>
                    Seat ${seat.dataset.seat}
                </div>`;
        });
        seatsListHtml += '</div>';
        
        // Add booking button
        seatsListHtml += `
            <button type="button" 
                    class="btn btn-primary btn-block mt-3" 
                    onclick="bookSelectedSeats('${trainNo}', '${seatClass}')">
                Book ${selectedSeats.length} Selected Seat${selectedSeats.length > 1 ? 's' : ''}
            </button>`;
        
        selectedSeatInfo.innerHTML = seatsListHtml;
    } else {
        selectedSeatInfo.classList.add('d-none');
        noSeatSelected.classList.remove('d-none');
    }
}

// Add new function to handle seat booking
function bookSelectedSeats(trainNo, seatClass) {
    const selectedSeats = document.querySelectorAll('.seat-selected');
    const seats = Array.from(selectedSeats).map(seat => seat.dataset.seat);
    
    // Create form data
    const formData = new FormData();
    formData.append('train_no', trainNo);
    formData.append('class', seatClass);
    formData.append('seats', JSON.stringify(seats));
    formData.append('action', 'book_seats');

    // Send AJAX request
    fetch('book_seats.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            $('#seatMapModal').modal('hide');
            
            // Show success message
                        const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Successfully booked ${seats.length} seat(s)!
                            </div>
                        `;
                        document.body.insertAdjacentHTML('afterbegin', alertHtml);
                    } else {
                        alert('Booking failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert('An error occurred: ' + error);
                });
            }

function confirmBooking(trainNo, selectedClass) {
    const selectedSeats = document.querySelectorAll('.seat-item.seat-selected');
    if(selectedSeats.length === 0) {
        alert('Please select at least one seat');
        return;
    }

    // First add the seats to database
    const bookingData = {
        train_no: trainNo,
        class: selectedClass,
        seats: Array.from(selectedSeats).map(seat => seat.dataset.seat)
    };

    // Book seats in database first
    fetch('book_seats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If booking successful, proceed to passenger details
            const trainForm = document.querySelector(`input[value="${trainNo}"]`).closest('form');
            const bookingForm = document.createElement('form');
            bookingForm.method = 'POST';
            bookingForm.action = 'psg_details.php';

            // Add all required booking data
            const formData = {
                train_no: trainNo,
                class: selectedClass,
                selected_seats: Array.from(selectedSeats).map(seat => seat.dataset.seat).join(','),
                src: trainForm.querySelector('input[name="src"]').value,
                dest: trainForm.querySelector('input[name="dest"]').value,
                date: trainForm.querySelector('input[name="date"]').value,
                train_name: trainForm.querySelector('input[name="train_name"]').value,
                dep_time: trainForm.querySelector('input[name="dep_time"]').value,
                arr_time: trainForm.querySelector('input[name="arr_time"]').value,
                duration: trainForm.querySelector('input[name="duration"]').value,
                fare: trainForm.querySelector('input[name="fare"]').value,
                station_no: trainForm.querySelector('input[name="station_no"]').value,
                book: 'true'
            };

            // Add hidden inputs
            Object.entries(formData).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                bookingForm.appendChild(input);
            });

            // Close modal and submit form
            if(typeof $('#seatMapModal').modal === 'function') {
                $('#seatMapModal').modal('hide');
            }
            document.body.appendChild(bookingForm);
            bookingForm.submit();
        } else {
            alert(data.message || 'Failed to book seats. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while booking seats');
    });
}
</script>

</body>
</html>
