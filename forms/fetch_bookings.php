<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accounts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Bookings";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = array(
            'title' => $row['booking_id'],
            'start' => $row['check_in'],
            'end' => $row['check_out']
        );
    }
}

echo json_encode($events);

$conn->close();
?>
