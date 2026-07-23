<?php
session_start();
include('DBConnection.php');

header('Content-Type: application/json');

if(!isset($_SESSION["uname"])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if(!isset($data['train_no']) || !isset($data['class']) || !isset($data['seats']) || empty($data['seats'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required booking information']);
    exit();
}

try {
    $train_no = $data['train_no'];
    $class = $data['class'];
    $seats = $data['seats'];
    $user_id = $_SESSION["uname"];
    $booking_id = 'BK' . time() . rand(100,999);
    $journey_date = isset($_SESSION['journey_date']) ? $_SESSION['journey_date'] : date('Y-m-d');

    $conn->begin_transaction();

    // Check if seats are already booked
    $seat_check_sql = "SELECT seat_no FROM seat_bookings 
                      WHERE train_no = ? AND class = ? 
                      AND journey_date = ? AND status = 'booked' 
                      AND seat_no IN (" . str_repeat('?,', count($seats) - 1) . "?)";
    
    $params = array_merge([$train_no, $class, $journey_date], $seats);
    $types = "sss" . str_repeat('s', count($seats));
    
    $stmt = $conn->prepare($seat_check_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        throw new Exception('Some seats are already booked. Please refresh and try again.');
    }

    // Insert booking records
    $insert_sql = "INSERT INTO seat_bookings 
                  (booking_id, train_no, seat_no, class, user_id, journey_date) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    
    foreach($seats as $seat_no) {
        $stmt->bind_param("ssssss", 
            $booking_id, 
            $train_no, 
            $seat_no, 
            $class, 
            $user_id, 
            $journey_date
        );
        $stmt->execute();
    }

    // Update available seats in train table
    $column = '';
    switch(strtoupper($class)) {
        case 'AC': 
            $column = 'seats_ac_available';
            break;
        case 'SL': 
            $column = 'seats_sleeper_available';
            break;
        case 'GENERAL': 
            $column = 'seats_general_available';
            break;
    }

    if($column) {
        $update_sql = "UPDATE train 
                      SET $column = $column - ? 
                      WHERE train_no = ?";
        $stmt = $conn->prepare($update_sql);
        $seats_count = count($seats);
        $stmt->bind_param("is", $seats_count, $train_no);
        $stmt->execute();
    }

    $conn->commit();
    // Store booking success in session
    $_SESSION['booking_success'] = true;
    $_SESSION['booked_seats'] = $seats;

    echo json_encode([
        'success' => true,
        'message' => 'Booking successful'
    ]);

} catch(Exception $e) {
    if(isset($conn)) {
        $conn->rollback();
    }
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?>