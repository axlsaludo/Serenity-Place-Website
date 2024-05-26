<?php
// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "accounts";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

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

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../pages/login.html");
    exit(); 
}

// Get form data
$villa_id = $_POST['villa_id'] ?? null;
$check_in_date = $_POST['check_in_date'] ?? null;
$check_out_date = $_POST['check_out_date'] ?? null;
$time_in = $_POST['time_in'] ?? null;
$time_out = $_POST['time_out'] ?? null;
$total_amount = $_POST['total_amount'] ?? null;
$booking_status = 'pending'; // Default status
$username = $_SESSION['username'] ?? null;

// Validate form data
if (!$villa_id || !$check_in_date || !$check_out_date || !$time_in || !$time_out || !$total_amount || !$username) {
    die("Missing form data.");
}

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO Bookings (username, villa_id, check_in_date, check_out_date, time_in, time_out, total_amount, booking_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissssds", $username, $villa_id, $check_in_date, $check_out_date, $time_in, $time_out, $total_amount, $booking_status);

if ($stmt->execute()) {
    // Get the booking ID of the last inserted record
    $booking_id = $stmt->insert_id;
    // Redirect to schedule page with the booking ID as a query parameter
    header("Location: /Serenity-Place-Website/pages/payment.html?booking_id=$booking_id");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
