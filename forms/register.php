<?php
// Start session
session_start();

// Establish database connection
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = ""; // default password for XAMPP
$dbname = "accounts"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$createDbQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($createDbQuery) === TRUE) {
    // Select the created database
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// Check if the 'users' table exists, if not, create it
$checkTableQuery = "SHOW TABLES LIKE 'users'";
$tableExists = $conn->query($checkTableQuery)->num_rows > 0;
if (!$tableExists) {
    $createTableQuery = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        username VARCHAR(30) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    if (!$conn->query($createTableQuery)) {
        die("Error creating table: " . $conn->error);
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent creation of an admin account
    if (strtolower($username) == 'admin') {
        $_SESSION['error'] = "The username 'admin' is not allowed.";
        header("Location: ../pages/register.html");
        exit();
    }

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = "An account with this username or email already exists.";
        header("Location: ../pages/register.html");
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO users (name, email, username, password) VALUES ('$name', '$email', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../pages/login.html"); // Correct path to login.html
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
