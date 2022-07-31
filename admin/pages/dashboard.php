<?php
include './../../services/connect.php';
include_once './redirect.php';

// Get users
$users = [];
$usersQuery = "SELECT t.*, r.room_name FROM tenants AS t
  INNER JOIN rooms AS r
  ON t.room_id = r.id
  LIMIT 5";
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
                <p class="category"><strong>Tenants</strong></p>
                <h3 class="card-title"><?php
                $sql = "SELECT * FROM tenants";
                $res = $conn->query($sql);
                echo $res->num_rows;
                ?></h3>
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
                <h3 class="card-title">
                  <?php 
                  $sql = "SELECT * FROM reservations";
                  $res = $conn->query($sql);
                  echo $res->num_rows;
                  ?>
                </h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">local_offer</i> Monthly Reservations
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
                <p class="category"><strong>Total Revenue</strong></p>
                <h3 class="card-title">
                  <?php 
                  $sql = "SELECT SUM(amount) AS `sum` FROM payments";
                  $res = $conn->query($sql);
                  $row = $res->fetch_all(MYSQLI_ASSOC);
                  echo !empty($row['sum']) ? "₱" . $row['sum'] . ".00" : "₱0.00";
                  ?>
                </h3>
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
                <p class="category"><strong>Vacant Rooms</strong></p>
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
                <h4 class="card-title">New tenants as of <?= date("F d, Y", strtotime(date(DATE_ISO8601)))?>
                </h4>
                <p class="category">
                  <?php 
                  $sql = "SELECT * FROM tenants WHERE `status` = 0 AND account_status = 0 ORDER BY date_added DESC LIMIT 1";
                  $res = $conn->query($sql);
                  if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    echo "Newest tenant registered on " . date("F d, Y", strtotime($row['date_added']));
                  } else {
                    echo "No tenants";
                  }
                ?></p>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover">
                  <thead class="text-primary">
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Room</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
foreach ($users as $user) {
    ?>
                    <tr>
                      <td><?=$user['id']?></td>
                      <td><?=$user['first_name'] . " " . $user['middle_initial'] . " " . $user['last_name']?></td>
                      <td><?=$user['room_name']?></td>
                    </tr>
                    <?php
}
?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
<!-- 
          <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text">
                <h4 class="card-title">Recent Activity</h4>
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
          </div> -->



        </div>



      </div>
    </div>







    <?php include './../templates/scripts.php'?>



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
          $.get("./../../services/logout.php", function(message) {
            alert(message);
            window.location = './../login.php';
          });
        }
      });
    });
    </script>
</body>

</html>