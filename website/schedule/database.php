<?php
// Database connection
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_username';
$pass = 'your_password';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save schedule to database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scheduleData = $_POST['scheduleData']; // Assuming you receive the schedule data from the frontend
    
    $sql = "INSERT INTO schedules (schedule_data) VALUES ('$scheduleData')";
    if ($conn->query($sql) === TRUE) {
        echo "Schedule saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
