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

// Get username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if the provided credentials exist in the database
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Credentials are valid, set session variable and redirect to admin.html
    $_SESSION['loggedin'] = true;
    header("Location: admin.html");
} else {
    // Invalid credentials, redirect back to login page with an error message
    $_SESSION['error'] = "Wrong username or password";
    header("Location: pages-login.html");
}

$conn->close();
?>