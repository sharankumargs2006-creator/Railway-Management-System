<?php
// Adjust the path below if necessary to the actual location of NotificationManager.php
require_once __DIR__ . '/classes/NotificationManager.php';
// Make sure NotificationManager.php exists and defines the NotificationManager class.

// Initialize notification manager
$notificationManager = new NotificationManager();

// Test data
$testData = [
    'recipient' => 'your phone number', // Replace with your phone number
    'message' => 'Test booking confirmation - Train: 12345',
    'type' => 'sms'
];

// Send test notification
try {
    $result = $notificationManager->send($testData);
    echo "<div style='margin: 20px;'>";
    echo "<h3>Notification Test Results:</h3>";
    echo "<p>Send Status: <strong>" . ($result ? "Success" : "Failed") . "</strong></p>";
    
    // Check notification logs
    $logs = $notificationManager->getLogs();
    if (!empty($logs)) {
        echo "<h4>Recent Notifications:</h4>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Recipient</th><th>Type</th><th>Status</th><th>Sent At</th></tr>";
        foreach($logs as $log) {
            echo "<tr>";
            echo "<td>{$log['id']}</td>";
            echo "<td>{$log['recipient']}</td>";
            echo "<td>{$log['type']}</td>";
            echo "<td>{$log['status']}</td>";
            echo "<td>{$log['sent_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No notification logs found.</p>";
    }
    echo "</div>";
} catch (Exception $e) {
    echo "<div style='color: red; margin: 20px;'>";
    echo "Error: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}
?>