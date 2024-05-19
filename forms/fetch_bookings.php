<?php
// Start session
session_start();

// Database connection details
$servername = "localhost"; // Your MySQL server name
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "accounts"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking data
$sql = "SELECT villa_name, start_datetime, end_datetime FROM bookings";
$result = $conn->query($sql);

$bookings = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = array(
            'title' => $row['villa_name'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime']
        );
    }
}

echo json_encode($bookings);

$conn->close();
?>
