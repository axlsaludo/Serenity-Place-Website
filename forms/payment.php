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

// Select the database
$conn->select_db($dbname);

// Get form data
$booking_id = $_POST['booking_id'];
$cc_name = $_POST['cc-name'];
$cc_number = $_POST['cc-number'];
$cc_expiration = $_POST['cc-expiration'];
$cc_cvv = $_POST['cc-cvv'];

// Simulate payment processing (In reality, integrate with a payment gateway here)

// Update booking status to 'confirmed'
$sql = "UPDATE Bookings SET booking_status = 'confirmed' WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);

if ($stmt->execute()) {
    echo "Payment successful! Your booking is confirmed.";
    // Redirect to a confirmation page if needed
    // header("Location: ../pages/confirmation.html");
    // exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
