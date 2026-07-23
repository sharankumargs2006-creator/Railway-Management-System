<?php
session_start();
include('DBConnection.php');

header('Content-Type: application/json');

$train_no = $_GET['train_no'] ?? '';
$class = $_GET['class'] ?? '';

try {
    // Get train details and seat configuration
    $sql = "SELECT 
        CASE 
            WHEN ? = 'AC' THEN seats_ac_total
            WHEN ? = 'SL' THEN seats_sleeper_total
            ELSE seats_general_total
        END as total_seats,
        train_name
        FROM train WHERE train_no = ?";
        
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $class, $class, $train_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $train_data = $result->fetch_assoc();

    // Get booked seats
    $stmt = $conn->prepare("SELECT seat_no FROM seat_bookings WHERE train_no = ? AND class = ? AND status = 'booked'");
    $stmt->bind_param("ss", $train_no, $class);
    $stmt->execute();
    $booked_result = $stmt->get_result();
    
    $booked_seats = [];
    while($row = $booked_result->fetch_assoc()) {
        $booked_seats[] = $row['seat_no'];
    }

    $response = [
        'totalSeats' => (int)$train_data['total_seats'],
        'trainName' => $train_data['train_name'],
        'bookedSeats' => $booked_seats,
        'prefix' => $class === 'AC' ? 'A' : ($class === 'SL' ? 'S' : 'G')
    ];

    echo json_encode($response);
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>