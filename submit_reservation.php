<?php
// Start session
session_start();

// Database connection
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

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    echo "Access denied. You must log in first.";
    header("Location: login.html");
    exit();
}

// Get form data
$villa_id = $_POST['villa_id'];
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];
$time_in = $_POST['time_in'];
$time_out = $_POST['time_out'];
$total_amount = $_POST['total_amount'];
$booking_status = 'pending'; // Default status
$username = $_SESSION['username'];

// Insert booking into database
$sql = "INSERT INTO Bookings (username, villa_id, check_in_date, check_out_date, time_in, time_out, total_amount, booking_status)
        VALUES ('$username', '$villa_id', '$check_in_date', '$check_out_date', '$time_in', '$time_out', '$total_amount', '$booking_status')";

if ($conn->query($sql) === TRUE) {
    echo "Booking made successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
