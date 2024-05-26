<?php
// Start session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// Get logged-in user's username from session
$logged_in_username = $_SESSION['username']; // Assuming username is stored in session

// Fetch user email and name based on username
$sql = "SELECT email, name FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_in_username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_email = $row['email'];
    $user_name = $row['name'];
} else {
    die("Invalid username.");
}
$stmt->close();

// Simulate payment processing (In reality, integrate with a payment gateway here)
$payment_successful = $cc_name && $cc_number && $cc_expiration && $cc_cvv;

// Generate a unique confirmation code
$confirmation_code = uniqid('booking_', true);

// Update booking status to 'pending' and payment status to 'paid'
if ($payment_successful) {
    $sql = "UPDATE Bookings SET payment_status = 'paid', booking_status = 'pending', confirmation_code = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $confirmation_code, $booking_id);
} else {
    $sql = "UPDATE Bookings SET payment_status = 'not_paid' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
}

if ($stmt->execute()) {
    if ($payment_successful) {
        // Send confirmation code to the user via email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'henriquetrinid@gmail.com'; // SMTP username
            $mail->Password = 'jgds pzuw guno rzzw'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('henriquetrinid@gmail.com', 'Serenity Place');
            $mail->addAddress($user_email, $user_name); // Add a recipient, use actual user email and name

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Booking Confirmation - Serenity Place';
            $mail->Body    = "Dear $user_name,<br><br>Your payment was successful! Your booking is pending confirmation by the hotel. Your confirmation code is: <strong>$confirmation_code</strong><br><br>Please keep this confirmation code for your records. We will notify you once your booking is confirmed.<br><br>Best Regards,<br>Serenity Place";

            $mail->send();
            echo 'Confirmation email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect to thank you page
        $_SESSION['confirmation_code'] = $confirmation_code;
        header("Location: /Serenity-Place-Website/pages/thankyou.html");
        exit(); // Ensure the script stops executing after the redirect
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
  <!-- Start Header -->
  <header id="header-main" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="../index.html">The Serenity Place</a></h1>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto" href="../villas.html">Villas</a></li>
          <li><a class="nav-link scrollto" href="../logout.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header>
  <!-- End Header -->

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
              Dao Street, Capital City <br>
              CA 11200, United States <br><br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> info@example.com<br>
            </p>
          </div>
          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="../index.html">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="../about.html">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="../services.html">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="../terms.html">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="../privacy.html">Privacy policy</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div>
          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Join Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="container d-md-flex py-4">
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>The Serenity Place</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/ninestars-free-bootstrap-3-theme-for-creative/ -->
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="bx bx-chevron-up"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
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
