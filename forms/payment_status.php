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

// Add payment_status column if it doesn't exist
$sql = "ALTER TABLE Bookings ADD COLUMN payment_status ENUM('paid', 'not_paid') DEFAULT 'not_paid'";
$conn->query($sql);

// Close the connection
$conn->close();
