<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Schedules - Serenity Place Admin Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/admin/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/admin/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../admin/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/admin.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
  <!-- Start Header -->
  <header id="header-main" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="../index.html">The Serenity Place</a></h1>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto" href="..forms/admin.php">Admin Home</a></li>
          <li><a class="nav-link scrollto" href="../logout.php">Logout</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header>
  <!-- End Header -->

<main id="main" class="main">
  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card-admin">
          <div class="card-body">
            <h5 class="card-title">Schedules</h5>
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th>Booking ID</th>
                  <th>Name</th>
                  <th>Date of Booking</th>
                  <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                  <th data-type="date" data-format="YYYY/DD/MM">End Date</th>
                  <th>No of Adults</th>
                  <th>No of Children</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "accounts";

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $conn->prepare("SELECT booking_id, username, check_in_date, check_out_date, time_in, time_out, total_amount, created_at, booking_status FROM Bookings ORDER BY created_at DESC");
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($stmt->rowCount() > 0) {
                        foreach ($result as $row) {
                            $statusClass = ($row['booking_status'] === 'confirmed') ? 'success' : (($row['booking_status'] === 'cancelled') ? 'danger' : 'warning');

                            echo '<tr>';
                            echo '<td>' . $row['booking_id'] . '</td>';
                            echo '<td>' . $row['username'] . '</td>';
                            echo '<td>' . $row['created_at'] . '</td>';
                            echo '<td>' . $row['check_in_date'] . '</td>';
                            echo '<td>' . $row['check_out_date'] . '</td>';
                            echo '<td>' . $row['time_in'] . '</td>';
                            echo '<td>' . $row['time_out'] . '</td>';
                            echo '<td><span class="badge bg-' . $statusClass . '">' . $row['booking_status'] . '</span></td>';
                            echo '<td>';
                            echo '<button type="button" class="btn btn-success btn-confirm" data-id="' . $row['booking_id'] . '">Confirm</button>';
                            echo '<button type="button" class="btn btn-danger btn-cancel" data-id="' . $row['booking_id'] . '">Cancel</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="9">No bookings found.</td></tr>';
                    }
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                $conn = null;
                ?>
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
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

<!-- Vendor JS Files -->
<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/vendor/admin/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/vendor/chart.js/chart.umd.js"></script>
<script src="../assets/vendor/echarts/echarts.min.js"></script>
<script src="../assets/vendor/quill/quill.js"></script>
<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/vendor/tinymce/tinymce.min.js"></script>
<script src="../admin/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../assets/js/admin.js"></script>

<!-- DataTables Integration -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var table = new simpleDatatables.DataTable(".datatable", {
      perPage: 10,
      perPageSelect: [5, 10, 15, 20],
      labels: {
        placeholder: "Search...",
        perPage: "Show {select} entries per page",
        noRows: "No entries found",
        info: "Showing {start} to {end} of {rows} entries"
      }
    });

    document.querySelectorAll('.btn-confirm').forEach(function(button) {
      button.addEventListener('click', function() {
        var bookingId = this.getAttribute('data-id');
        updateBookingStatus(bookingId, 'confirmed');
      });
    });

    document.querySelectorAll('.btn-cancel').forEach(function(button) {
      button.addEventListener('click', function() {
        var bookingId = this.getAttribute('data-id');
        updateBookingStatus(bookingId, 'cancelled');
      });
    });

    function updateBookingStatus(bookingId, status) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'update_booking_status.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          location.reload(); // Reload the page to see the changes
        } else {
          alert('Error updating booking status');
        }
      };
      xhr.send('booking_id=' + bookingId + '&status=' + status);
    }
  });
</script>

</body>

</html>
  