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

// Create users table if it doesn't exist
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($tableCheckQuery) === FALSE) {
    die("Error creating table: " . $conn->error);
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
if ($conn->query($sql) === TRUE) {
    echo "Table Bookings created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if the provided credentials exist in the database
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Credentials are valid, set session variable
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    // Redirect based on username and password
    if ($username == 'admin' && $password == 'admin') {
        header("Location: admin.html");
    } else {
        header("Location: villas.html");
    }
} else {
    // Invalid credentials, redirect back to login page with an error message
    $_SESSION['error'] = "Wrong username or password";
    header("Location: login.html");
}

$conn->close();
?>
