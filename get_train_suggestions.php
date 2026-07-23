<?php
include('DBConnection.php');

if(isset($_GET['term'])) {
    $search = $_GET['term'];
    
    $sql = "SELECT train_name 
            FROM train 
            WHERE train_name LIKE ? 
            GROUP BY train_name 
            ORDER BY train_name 
            LIMIT 10";
            
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $suggestions = [];
    while($row = $result->fetch_assoc()) {
        $suggestions[] = $row['train_name'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($suggestions);
}
?>