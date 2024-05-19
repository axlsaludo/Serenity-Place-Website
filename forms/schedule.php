<?php
// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    header("Location: ../pages/login.html");
    exit();
}

// Create Bookings table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    villa_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    time_in TIME NOT NULL,
    time_out TIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) !== TRUE) {
    die("Error creating Bookings table: " . $conn->error);
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
    // Get the booking ID of the last inserted record
    $booking_id = $conn->insert_id;
    // Redirect to mock payment page with the booking ID as a query parameter
    header("Location: ../pages/payment.html?booking_id=$booking_id");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
