<?php
session_start();
include('DBConnection.php');

// Check admin login
if(!isset($_SESSION["admin_uname"])){
    header("location: ./Adminlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking Statistics</title>
    <?php include("adminheader2.html"); ?>
    <style>
        .booking-stats {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-card {
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        .progress {
            height: 20px;
            margin-bottom: 10px;
        }
        
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table {
            background-color: white;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .table td {
            vertical-align: middle;
            background-color: white;
        }

        .badge {
            padding: 8px 12px;
            font-size: 0.9em;
        }

        .badge-success {
            background-color: #28a745;
        }

        .booking-details-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
            display: inline-block;
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
                <div class="booking-stats">
                    <!-- Search Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row align-items-end">
                                <div class="col-md-3">
                                    <label>Train Number</label>
                                    <input type="text" name="train_no" class="form-control" 
                                           value="<?php echo $_GET['train_no'] ?? ''; ?>" 
                                           placeholder="Enter train number">
                                </div>
                                <div class="col-md-3">
                                    <label>Date Range</label>
                                    <input type="date" name="date_from" class="form-control" 
                                           value="<?php echo $_GET['date_from'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label>To</label>
                                    <input type="date" name="date_to" class="form-control" 
                                           value="<?php echo $_GET['date_to'] ?? ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <h2 class="mb-4">Train Booking Statistics</h2>
                    <?php
                    $where_conditions = [];
                    $params = [];
                    $param_types = '';

                    if(!empty($_GET['train_no'])) {
                        $where_conditions[] = "t.train_no = ?";
                        $params[] = $_GET['train_no'];
                        $param_types .= 's';
                    }

                    if(!empty($_GET['date_from'])) {
                        $where_conditions[] = "EXISTS (SELECT 1 FROM seat_bookings sb 
                                                  WHERE sb.train_no = t.train_no 
                                                  AND sb.journey_date >= ?)";
                        $params[] = $_GET['date_from'];
                        $param_types .= 's';
                    }

                    if(!empty($_GET['date_to'])) {
                        $where_conditions[] = "EXISTS (SELECT 1 FROM seat_bookings sb 
                                                  WHERE sb.train_no = t.train_no 
                                                  AND sb.journey_date <= ?)";
                        $params[] = $_GET['date_to'];
                        $param_types .= 's';
                    }

                    $query = "SELECT 
                        t.train_no,
                        t.train_name,
                        t.seats_ac_total,
                        t.seats_ac_available,
                        t.seats_sleeper_total,
                        t.seats_sleeper_available,
                        t.seats_general_total,
                        t.seats_general_available
                        FROM train t";

                    if(!empty($where_conditions)) {
                        $query .= " WHERE " . implode(" AND ", $where_conditions);
                    }
                    $query .= " ORDER BY t.train_no";

                    $stmt = $conn->prepare($query);
                    if(!empty($params)) {
                        $stmt->bind_param($param_types, ...$params);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if($result && $result->num_rows > 0):
                        while($train = $result->fetch_assoc()):
                            // Calculate booked seats
                            $ac_booked = $train['seats_ac_total'] - $train['seats_ac_available'];
                            $sleeper_booked = $train['seats_sleeper_total'] - $train['seats_sleeper_available'];
                            $general_booked = $train['seats_general_total'] - $train['seats_general_available'];
                            
                            // Calculate percentages
                            $ac_percent = $train['seats_ac_total'] > 0 ? 
                                ($ac_booked / $train['seats_ac_total']) * 100 : 0;
                            $sleeper_percent = $train['seats_sleeper_total'] > 0 ? 
                                ($sleeper_booked / $train['seats_sleeper_total']) * 100 : 0;
                            $general_percent = $train['seats_general_total'] > 0 ? 
                                ($general_booked / $train['seats_general_total']) * 100 : 0;
                    ?>
                        <div class="card stats-card">
                            <div class="card-body">
                                <h4>Train <?php echo htmlspecialchars($train['train_no']); ?> - 
                                    <?php echo htmlspecialchars($train['train_name']); ?></h4>
                                
                                <!-- AC Class -->
                                <div class="mt-3">
                                    <h5><i class="fas fa-snowflake"></i> AC Class</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: <?php echo $ac_percent; ?>%">
                                            <?php echo round($ac_percent); ?>%
                                        </div>
                                    </div>
                                    <small>Booked: <?php echo $ac_booked; ?> / <?php echo $train['seats_ac_total']; ?></small>
                                </div>

                                <!-- Sleeper Class -->
                                <div class="mt-3">
                                    <h5><i class="fas fa-bed"></i> Sleeper Class</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: <?php echo $sleeper_percent; ?>%">
                                            <?php echo round($sleeper_percent); ?>%
                                        </div>
                                    </div>
                                    <small>Booked: <?php echo $sleeper_booked; ?> / <?php echo $train['seats_sleeper_total']; ?></small>
                                </div>

                                <!-- General Class -->
                                <div class="mt-3">
                                    <h5><i class="fas fa-users"></i> General Class</h5>
                                    <div class="progress">
                                        <div class="progress-bar bg-secondary" style="width: <?php echo $general_percent; ?>%">
                                            <?php echo round($general_percent); ?>%
                                        </div>
                                    </div>
                                    <small>Booked: <?php echo $general_booked; ?> / <?php echo $train['seats_general_total']; ?></small>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <div class="alert alert-info">No booking data available.</div>
                    <?php endif; ?>
                </div>

                <!-- Booking Details Table -->
                <div class="container mt-4">
                     <h2 class="booking-details-title">
                        <i class="fas fa-ticket-alt"></i> Booking Details
                    </h2>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Train Details</th>
                                        <th>Booked Seats</th>
                                        <th>Available Seats</th>
                                        <th>User ID</th>
                                        <th>Journey Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Get booking details with seat information
                                    $sql = "SELECT sb.booking_id, sb.train_no, t.train_name, 
                                            GROUP_CONCAT(sb.seat_no) as booked_seats, 
                                            sb.class, sb.user_id, sb.journey_date,
                                            t.seats_ac_total - COUNT(CASE WHEN sb.class = 'AC' THEN 1 END) as ac_available,
                                            t.seats_sleeper_total - COUNT(CASE WHEN sb.class = 'SL' THEN 1 END) as sleeper_available,
                                            t.seats_general_total - COUNT(CASE WHEN sb.class = 'GENERAL' THEN 1 END) as general_available
                                            FROM seat_bookings sb
                                            JOIN train t ON sb.train_no = t.train_no
                                            GROUP BY sb.booking_id, sb.train_no, t.train_name, sb.class, sb.user_id, sb.journey_date";

                                    $result = $conn->query($sql);
                                    while($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($row['train_name']); ?><br>
                                            <small>(<?php echo htmlspecialchars($row['train_no']); ?>)</small>
                                        </td>
                                        <td>
                                            <strong>Class:</strong> <?php echo htmlspecialchars($row['class']); ?><br>
                                            <strong>Seats:</strong> <?php echo htmlspecialchars($row['booked_seats']); ?>
                                        </td>
                                        <td>
                                            AC: <?php echo $row['ac_available']; ?><br>
                                            Sleeper: <?php echo $row['sleeper_available']; ?><br>
                                            General: <?php echo $row['general_available']; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['journey_date']); ?></td>
                                        <td>
                                            <span class="badge badge-success">Booked</span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>