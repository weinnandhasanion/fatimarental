<?php
include './../functions/connect.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./..");
}

// Get users
$users = [];
$usersQuery = "SELECT t.*, r.room_number FROM tenants AS t
  INNER JOIN rooms AS r
  ON t.room_id = r.id";
$usersRes = mysqli_query($conn, $usersQuery);
$users = mysqli_fetch_all($usersRes, MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>


<body>




  <div class="wrapper">


    <div class="body-overlay"></div>
    <?php include './../templates/nav.php'?>
    <!-- Page Content  -->
    <div id="content">

    <?php include './../templates/topnav.php'?>


      <div class="main-content">

        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header">
                <div class="icon icon-warning">
                  <span class="material-icons">equalizer</span>
                </div>
              </div>
              <div class="card-content">
                <p class="category"><strong>Visits</strong></p>
                <h3 class="card-title">340</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons text-info">info</i>
                  <a href="#pablo">See detailed report</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header">
                <div class="icon icon-rose">
                  <span class="material-icons">library_books</span>

                </div>
              </div>
              <div class="card-content">
                <p class="category"><strong>Reservations</strong></p>
                <h3 class="card-title">10</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">local_offer</i> Weekly Reserve
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header">
                <div class="icon icon-success">
                  <span class="material-icons">
                    content_copy
                  </span>

                </div>
              </div>
              <div class="card-content">
                <p class="category"><strong>Revenue</strong></p>
                <h3 class="card-title">₱23,100</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">date_range</i> Total Payments
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header">
                <div class="icon icon-info">

                  <span class="material-icons">
                    follow_the_signs
                  </span>
                </div>
              </div>
              <div class="card-content">
                <p class="category"><strong>Total Tenant Paid</strong></p>
                <h3 class="card-title">45</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">update</i> Just Updated
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row ">
          <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Tenant Status</h4>
                <p class="category">New Tentat Rent on 21th December, 2020</p>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover">
                  <thead class="text-primary">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Payment Status</th>
                      <th>Room Number</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($users as $user) {
                    ?>
                    <tr>
                      <td><?=$user['id']?></td>
                      <td><?=$user['first_name'] . " " . $user['middle_initial'] . " " . $user['last_name']?></td>
                      <td>₱3,000</td>
                      <td><?=$user['room_number']?></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Monthly Satus</h4>
              </div>
              <div class="card-content">
                <div class="streamline">
                  <div class="sl-item sl-primary">
                    <div class="sl-content">
                      <small class="text-muted">5 mins ago</small>
                      <p>Michael has just Paid room 5 good for 2</p>
                    </div>
                  </div>
                  <div class="sl-item sl-danger">
                    <div class="sl-content">
                      <small class="text-muted">25 mins ago</small>
                      <p>Michael has just Paid room 5 good for 2</p>
                    </div>
                    <div class="sl-item sl-success">
                      <div class="sl-content">
                        <small class="text-muted">40 mins ago</small>
                        <p>Michael has just Paid room 5 good for 2</p>
                      </div>
                    </div>
                    <div class="sl-item">
                      <div class="sl-content">
                        <small class="text-muted">45 minutes ago</small>
                        <p>Michael has just Paid room 5 good for 2</p>
                      </div>
                    </div>
                    <div class="sl-item sl-warning">
                      <div class="sl-content">
                        <small class="text-muted">55 mins ago</small>
                        <p>Michael has just Paid room 5 good for 2</p>
                      </div>
                    </div>
                    <div class="sl-item">
                      <div class="sl-content">
                        <small class="text-muted">60 minutes ago</small>
                        <p>Michael has just Paid room 5 good for 2</p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>



        </div>



      </div>
    </div>








    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./../js/jquery-3.3.1.slim.min.js"></script>
    <script src="./../js/popper.min.js"></script>
    <script src="./../js/bootstrap.min.js"></script>
    <script src="./../js/jquery-3.3.1.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });

      $('.more-button,.body-overlay').on('click', function() {
        $('#sidebar,.body-overlay').toggleClass('show-nav');
      });

      $('.sidebar-link').click(function(e) {
        if ($(this).hasClass('active')) {
          e.preventDefault();
          return;
        }
        $('.sidebar-link').removeClass('active');
        $(this).addClass('active');

      });

      $('#logout-link').click(function() {
        let x = confirm("Do you want to logout?");
        if (x) {
          $.get("./../functions/logout.php", function(message) {
            alert(message);
            window.location = './login.php';
          });
        }
      });
    });
    </script>
</body>

</html>