<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Serenity Place Admin Dashboard</title>
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
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

  <!-- Template Main CSS File -->
  <link href="../assets/css/admin.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header-main" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="../index.html">The Serenity Place</a></h1>
      <nav id="navbar" class="navbar">
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header><!-- End Header -->

  <main id="main" class="main">
    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">
            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card-admin info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Sales <span>| Today</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <h6>145</h6>
                      <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Sales Card -->
            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card-admin info-card revenue-card">
                <div class="card-body">
                  <h5 class="card-title">Revenue <span>| This Month</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <h6>$3,264</h6>
                      <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">
              <div class="card-admin info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Customers <span>| This Year</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="ps-3">
                      <h6>1244</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Customers Card -->
            <!-- Calendar Card -->
            <div class="col-12">
              <div class="card-admin">
                <!-- Calendar Body -->
                <div class="card-body">
                  <h5 class="card-title">Booking Calendar</h5>
                  <div id="calendar-container">
                    <div id="calendar"></div>
                  </div>
                </div>
              </div>
            </div><!-- End Calendar Card -->
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card-admin recent-sales overflow-auto">
                <a href="admintable.php" class="stretched-link">
                  <div class="card-body">
                    <h5 class="card-title">Recent Bookings</h5>
                    <table class="table table-borderless datatable">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Duration</th>
                          <th scope="col">Price</th>
                          <th scope="col">Date</th>
                          <th scope="col">Status</th>
                        </tr>
                      </thead>
                      <tbody id="recent-bookings-body">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "accounts";

                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $stmt = $conn->prepare("SELECT booking_id, username, check_in_date, check_out_date, time_in, time_out, total_amount, created_at, booking_status FROM Bookings ORDER BY created_at DESC LIMIT 5");
                            $stmt->execute();

                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if ($stmt->rowCount() > 0) {
                                foreach ($result as $row) {
                                    // Calculate duration
                                    $check_in = new DateTime($row['check_in_date'] . ' ' . $row['time_in']);
                                    $check_out = new DateTime($row['check_out_date'] . ' ' . $row['time_out']);
                                    $interval = $check_in->diff($check_out);
                                    $duration = $interval->format('%a days %h hours');
                                    if ($interval->d == 0) {
                                        $duration = $interval->format('%h hours');
                                    } elseif ($interval->h == 0) {
                                        $duration = $interval->format('%a days');
                                    }

                                    $statusClass = ($row['booking_status'] === 'confirmed') ? 'success' : 'warning';

                                    echo '<tr>';
                                    echo '<th scope="row"><a href="#">#' . $row['booking_id'] . '</a></th>';
                                    echo '<td>' . $row['username'] . '</td>';
                                    echo '<td>' . $duration . '</td>';
                                    echo '<td>$' . $row['total_amount'] . '</td>';
                                    echo '<td>' . $row['created_at'] . '</td>';
                                    echo '<td><span class="badge bg-' . $statusClass . '">' . $row['booking_status'] . '</span></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6">No recent bookings found.</td></tr>';
                            }
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }

                        $conn = null;
                        ?>
                      </tbody>
                    </table>
                  </div>
                </a>
              </div>
            </div>
            <!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">
          <!-- Recent Activity -->
          <div class="card-admin">
            <div class="card-body">
              <h5 class="card-title">Recent Activity</h5>

              <div class="activity">
                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                  </div>
                </div><!-- End activity item-->
                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div><!-- End activity item-->
                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div><!-- End activity item-->
                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                  </div>
                </div><!-- End activity item-->
                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div><!-- End activity item-->
                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div><!-- End activity item-->
              </div>
            </div>
          </div><!-- End Recent Activity -->
        </div><!-- End Right side columns -->
      </div>
    </section>
  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/admin/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/admin/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/admin/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/admin/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/admin/quill/quill.min.js"></script>
  <script src="../assets/vendor/admin/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/admin/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/admin/php-email-form/validate.js"></script>

  <!-- FullCalendar JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="../assets/js/admin.js"></script>

  <!-- FullCalendar Integration -->
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
      
      // Fetch and display recent bookings
      function fetchRecentBookings() {
        $.ajax({
          url: 'fetch_recent_bookings.php',
          success: function(data) {
            var bookingsBody = $('#recent-bookings-body');
            bookingsBody.html(data); // Directly insert the HTML
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
          }
        });
      }

      // Fetch recent bookings on page load
      fetchRecentBookings();
    });
  </script>
</body>
</html>
