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

// Create users table if it doesn't exist and add status column if it doesn't exist
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('online', 'offline') DEFAULT 'offline'
)";
if ($conn->query($tableCheckQuery) === FALSE) {
    die("Error creating table: " . $conn->error);
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

    // Update user status to 'online'
    $updateStatusQuery = "UPDATE users SET status='online' WHERE username='$username'";
    $conn->query($updateStatusQuery);

    // Redirect based on username and password
    if ($username == 'admin' && $password == 'admin') {
        header("Location: ../forms/admin.php");
    } else {
        header("Location: ../pages/villas.html");
    }
} else {
    // Invalid credentials, redirect back to login page with an error message
    $_SESSION['error'] = "Wrong username or password";
    header("Location: ../pages/login.html?error=1");
}

$conn->close();

?>