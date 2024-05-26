<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "accounts";

    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE Bookings SET booking_status = :status WHERE booking_id = :booking_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':booking_id', $bookingId);
        $stmt->execute();

        echo "Booking status updated successfully.";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}
?>
