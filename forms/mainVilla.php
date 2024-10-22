<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Villa 1 - Serenity Place</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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

  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

  <!-- FullCalendar JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <style>
    /* Custom CSS for mobile view */
    @media (max-width: 767px) {
      #calendar-reservation-container {
        display: flex;
        flex-direction: column;
      }

      #calendar-container, #reservation-container {
        width: 100%;
      }
    }

    @media (min-width: 768px) {
      #calendar-reservation-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
      }

      #calendar-container, #reservation-container {
        width: 48%;
      }
    }

    .time-container,
    .guests {
      display: flex;
      justify-content: space-between;
    }

    .time-input,
    .guests-input {
      width: 48%;
    }

    .note {
      margin-top: 20px;
      margin-bottom: 20px;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #f9f9f9;
      font-size: small;
    }

    .note h6 {
      color: #333;
    }

    .note p {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
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

  // Create database if it doesn't exist
  $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
  if ($conn->query($sql) !== TRUE) {
      die("Error creating database: " . $conn->error);
  }

  // Select the database
  $conn->select_db($dbname);

  // Create Bookings table if it doesn't exist
  $sql = "CREATE TABLE IF NOT EXISTS Bookings (
      booking_id INT PRIMARY KEY AUTO_INCREMENT,
      username VARCHAR(50) NOT NULL,
      villa_id INT NOT NULL,
      check_in_date DATE NOT NULL,
      check_out_date DATE NOT NULL,
      time_in TIME NOT NULL,
      time_out TIME NOT NULL,
      adults INT NOT NULL,
      children INT NOT NULL,
      total_amount DECIMAL(10, 2) NOT NULL,
      booking_status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'pending',
      payment_status ENUM('paid', 'not_paid') DEFAULT 'not_paid',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )";
  if ($conn->query($sql) !== TRUE) {
      die("Error creating Bookings table: " . $conn->error);
  }

  // Check if user is logged in
  if (!isset($_SESSION['loggedin'])) {
      header("Location: ../pages/login.html");
      exit(); 
  }

  // Process form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $villa_id = $_POST['villa_id'] ?? null;
    $check_in_date = $_POST['check_in_date'] ?? null;
    $check_out_date = $_POST['check_out_date'] ?? null;
    $time_in = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? null;
    $adults = $_POST['adults'] ?? 0;
    $children = $_POST['children'] ?? 0;
    $total_amount = $_POST['total_amount'] ?? null;
    $booking_status = 'pending'; // Default status
    $username = $_SESSION['username'] ?? null;

    // Validate form data
    if (!$villa_id || !$check_in_date || !$check_out_date || !$time_in || !$time_out || !$total_amount || !$username) {
        die("Missing form data.");
    }

    // Insert booking into database
    $stmt = $conn->prepare("INSERT INTO Bookings (username, villa_id, check_in_date, check_out_date, time_in, time_out, adults, children, total_amount, booking_status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'not_paid')");
    $stmt->bind_param("sissssddds", $username, $villa_id, $check_in_date, $check_out_date, $time_in, $time_out, $adults, $children, $total_amount, $booking_status);

    if ($stmt->execute()) {
        // Get the booking ID of the last inserted record
        $booking_id = $stmt->insert_id;
        // Redirect to payment page with the booking ID as a query parameter
        header("Location: /Serenity-Place-Website/pages/payment.html?booking_id=$booking_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
  }

  $conn->close();
  ?>

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

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <!-- Breadcrumb content -->
    </section><!-- End Breadcrumbs -->

    <!-- ======= Villas Container ======= -->
    <div class="card">
      <div class="card-body">
        <!-- Slides with captions -->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="../assets/img/villas/main-1.png" class="d-block w-100" alt="test">
              <div class="carousel-caption d-none d-md-block">
                <h5>First slide label</h5>
                <p>Some representative placeholder content for the first slide.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../assets/img/villas/main-2.png" class="d-block w-100" alt="test">
              <div class="carousel-caption d-none d-md-block">
                <h5>Second slide label</h5>
                <p>Some representative placeholder content for the second slide.</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="../assets/img/villas/main-3.png" class="d-block w-100" alt="test">
              <div class="carousel-caption d-none d-md-block">
                <h5>Third slide label</h5>
                <p>Some representative placeholder content for the third slide.</p>
              </div>
            </div>
            <div class="carousel-item">
               <img src="../assets/img/villas/main-4.png" class="d-block w-100" alt="test">
                <div class="carousel-caption d-none d-md-block">
                <h5>Fourth slide label</h5>
                <p>Some representative placeholder content for the third slide.</p>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div><!-- End Slides with captions -->
      </div>
    </div>

    <!-- Calendar View -->
    <div class="container mt-5">
      <div id="calendar-reservation-container">
        <div id="calendar-container">
          <div id="calendar"></div>
        </div>
        <div id="reservation-container">
          <div class="reservation-form">
            <h2>Make a Reservation</h2>
            <!-- Reservation Form -->
            <form id="reservationForm" method="POST">
                <!-- Static Villa ID -->
                <input type="hidden" id="villa_id" name="villa_id" value="1"> 
            
                <!-- Important Note -->
                <div class="note">
                  <h6>Important Note for Villa Guests</h6>
                  <p>Please be advised that there is a minimum payment requirement for this villa. Additionally, the minimum duration of stay is 5 hours.</p>
                  <p>The computed rate for a 5-hour stay is [insert computed value here].</p>
                  <p>We kindly remind you to be responsible for your pets during your stay. Please ensure to clean up after your pets, including their waste.</p>
                  <p>For any additional inquiries or concerns, such as accommodating extra guests, please feel free to contact us at the number provided in the Bottom of the Pages.</p>
                  <p>Thank you for choosing our villa. We hope you have a pleasant and enjoyable stay!</p>
                </div>
  
            
                <!-- Date Selection -->
                <label for="reservationStartDate">Arrival Date:</label>
                <input type="text" id="reservationStartDate" name="check_in_date" readonly>
            
                <label for="reservationEndDate">Departure Date:</label>
                <input type="text" id="reservationEndDate" name="check_out_date" readonly>
            
                <hr>
            
                <!-- Time Selection -->
                <div class="time-container">
                    <div class="time-input">
                        <label for="startTime">Start Time:</label>
                        <input type="time" id="startTime" name="time_in">
                    </div>
                    <div class="time-input">
                        <label for="endTime">End Time:</label>
                        <input type="time" id="endTime" name="time_out">
                    </div>
                </div>
            
                <hr>
            
                <!-- Guest Information -->
                <div class="guests">
                  <!-- Adults Counter -->
                  <div class="guests-input">
                      <label for="adults">Adults:</label>
                      <input type="number" id="adults" name="adults" min="0" max="4" value="0">
                  </div>
                  <!-- Children Counter -->
                  <div class="guests-input">
                      <label for="children">Children:</label>
                      <input type="number" id="children" name="children" min="0" value="0">
                  </div>
                </div>
            
                <!-- Total Amount -->
                <div class="total-amount">
                    <label for="total_amount">Total Amount:</label>
                    <input type="number" id="total_amount" name="total_amount" min="1" readonly>
                </div>
            
                <button type="submit">Book</button>
            </form>
            
        </div>
      </div>
    </div>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>The Serenity Place</span></strong>. All Rights Reserved
      </div>
    </div>
  </footer><!-- End Footer -->

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

  <script>
    $(document).ready(function() {
      // Initialize FullCalendar
      var calendar = $('#calendar').fullCalendar({
        defaultView: 'month',
        selectable: true,
        select: function(start, end) {
          $('#reservationStartDate').val(start.format('YYYY-MM-DD'));
          $('#reservationEndDate').val(end.format('YYYY-MM-DD'));
        },
        minDate: new Date(), // Gray out past dates
        events: function(start, end, timezone, callback) {
          $.ajax({
            url: 'fetch_bookings.php',
            dataType: 'json',
            success: function(data) {
              var events = [];
              $(data).each(function() {
                events.push({
                  id: $(this).attr('id'),
                  title: $(this).attr('title'),
                  start: $(this).attr('start'),
                  end: $(this).attr('end'),
                  description: $(this).attr('description')
                });
              });
              callback(events);
            }
          });
        },
        eventRender: function(event, element) {
          element.qtip({
            content: event.description
          });
        }
      });

      // Calculate price based on duration
      function calculatePrice(startTime, endTime) {
        var duration = moment.duration(moment(endTime, 'HH:mm').diff(moment(startTime, 'HH:mm')));
        var hours = duration.asHours();

        // Minimum schedule is 5 hours
        if (hours < 5) {
          hours = 5;
        }

        // Price is $2500 for 8 hours
        var pricePerHour = 2500 / 8;
        var price = pricePerHour * hours;
        return price.toFixed(2);
      }

      // Update price when start time, end time, adults, or children change
      $('#startTime, #endTime, #adults, #children').change(function() {
        var adults = parseInt($('#adults').val());
        var children = parseInt($('#children').val());

        // Limit number of children based on number of adults
        var maxChildren = 4 - adults;
        if (children > maxChildren) {
            children = maxChildren;
            $('#children').val(maxChildren);
        }

        // Calculate total guests (including adults and children)
        var totalGuests = adults + children;

        // Calculate price based on duration and total guests
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        var price = calculatePrice(startTime, endTime);
        $('#total_amount').val(price);
      });
    });
  </script>
</body>
</html>
