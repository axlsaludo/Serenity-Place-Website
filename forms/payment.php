<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    echo "Access denied. You must log in first.";
    header("Location: login.html");
    exit();
}

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

// Get booking ID from query parameter
$booking_id = $_GET['booking_id'];

// Retrieve booking details from database
$sql = "SELECT * FROM Bookings WHERE booking_id = $booking_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
    echo "Booking not found.";
    exit();
}

// Replace with your actual PayMongo API keys
$secret_key = 'sk_test_xxxxxxx';
$public_key = 'pk_test_xxxxxxx';

// Get payment details from POST request
$name = $_POST['cc-name'];
$number = $_POST['cc-number'];
$expiry = $_POST['cc-expiration'];
$cvv = $_POST['cc-cvv'];
$amount = $booking['total_amount'];

// Create Payment Intent
$intent_data = array(
    'data' => array(
        'attributes' => array(
            'amount' => $amount * 100, // Amount in centavos
            'payment_method_allowed' => array('card'),
            'payment_method_options' => array(
                'card' => array(
                    'request_three_d_secure' => 'any'
                )
            ),
            'currency' => 'PHP' // Hard-coded currency
        )
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.paymongo.com/v1/payment_intents');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($intent_data));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$response_data = json_decode($response, true);
$intent_id = $response_data['data']['id'];
$client_key = $response_data['data']['attributes']['client_key'];

// Confirm Payment Intent
$confirm_data = array(
    'data' => array(
        'attributes' => array(
            'payment_method' => array(
                'type' => 'card',
                'billing' => array(
                    'address' => array(
                        'line1' => 'test',
                        'city' => 'test',
                        'country' => 'PH'
                    ),
                    'name' => $name,
                    'phone' => '1234567890',
                    'email' => 'test@example.com'
                ),
                'details' => array(
                    'card_number' => $number,
                    'exp_month' => substr($expiry, 0, 2),
                    'exp_year' => substr($expiry, -2),
                    'cvc' => $cvv
                )
            )
        )
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/payment_intents/$intent_id/attach");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($confirm_data));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ':' . '');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $client_key));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$response_data = json_decode($response, true);
$status = $response_data['data']['attributes']['status'];

if ($status == 'succeeded') {
    // Payment successful, redirect to success page
    header("Location: payment_success.html");
} else {
    // Payment failed, redirect to failure page
    header("Location: payment_failure.html");
}
?>
