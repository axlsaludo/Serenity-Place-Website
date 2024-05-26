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
$payment_successful = $cc_name && $cc_number && $cc_expiration && $cc_cvv;

// Update booking status to 'confirmed' and payment status to 'paid'
if ($payment_successful) {
    $sql = "UPDATE Bookings SET booking_status = 'confirmed', payment_status = 'paid' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
} else {
    $sql = "UPDATE Bookings SET booking_status = 'pending', payment_status = 'not_paid' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
}

if ($stmt->execute()) {
    if ($payment_successful) {
        header("Location: /Serenity-Place-Website/pages/thankyou.html");
    } else {
        echo "Payment failed. Please try again.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Payment - Serenity Place</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  
  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header-main" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="../index.html">The Serenity Place</a></h1>
    </div>
  </header><!-- End Header -->

  <!-- ======= Breadcrumbs ======= -->
  <section class="breadcrumbs">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
      </div>
    </div>
  </section><!-- End Breadcrumbs -->

  <div class="section-title">
    <h2>Payment</h2>
    <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
  </div>

  <main id="main">
     <section class="inner-page">
      <div class="container">
        <h3>Complete Your Payment</h3>
        <p>Booking ID: <span id="booking-id"></span></p>
        <form action="payment.php" method="POST">
          <input type="hidden" name="booking_id" id="booking_id_input">
          <div class="mb-3">
            <label for="cc-name" class="form-label">Name on card</label>
            <input type="text" class="form-control" id="cc-name" name="cc-name" required>
            <small class="text-muted">Full name as displayed on card</small>
          </div>
          <div class="mb-3">
            <label for="cc-number" class="form-label">Credit card number</label>
            <input type="text" class="form-control" id="cc-number" name="cc-number" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="cc-expiration" class="form-label">Expiration</label>
              <input type="text" class="form-control" id="cc-expiration" name="cc-expiration" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="cc-cvv" class="form-label">CVV</label>
              <input type="text" class="form-control" id="cc-cvv" name="cc-cvv" required>
            </div>
          </div>
          <hr class="mb-4">
          <button class="w-100 btn btn-primary btn-lg" type="submit">Submit Payment</button>
        </form>
      </div>
    </section>
  </main><!-- End #main -->
 <!-- ======= Footer ======= -->
 <footer id="footer">
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6 footer-contact">
          <h3>The Serenity Place</h3>
          <p>
            Dao Street, San Isidro <br>
            Lipa City, Batangas<br>
            Philippines<br><br>
            <strong>Phone:</strong> +63 923 346 6306<br>
            <strong>Email:</strong> theserenityplacelipa@gmail.com<br>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer><!-- End Footer -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const urlParams = new URLSearchParams(window.location.search);
      const bookingId = urlParams.get('booking_id');
      document.getElementById('booking-id').innerText = bookingId;
      document.getElementById('booking_id_input').value = bookingId;
    });
  </script>
</body>
</html>
