<?php
include_once './redirect.php';
include './../../services/connect.php';

$sql = "SELECT * FROM reservations";
$res = $conn->query($sql);
$reservations = [];
if ($res) {
  $reserviations = $res->fetch_all(MYSQLI_ASSOC);
}
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
  <div class="wrapper">
    <div class="body-overlay"></div>

    <?php include './../templates/nav.php' ?>

    <!-- Page Content  -->
    <div id="content">

      <?php include './../templates/topnav.php'?>

      <div class="main-content">
        <div class="row ">
          <div class="col-sm-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                <h4 class="card-title flex-grow-1">Reservations</h4>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover" id='rooms-table'>
                  <thead class="text-primary">
                    <tr>
                      <th>Name</th>
                      <th>Email Address</th>
                      <th>Move Date</th>
                      <th>Message</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($reservations as $res) {?>
                    <tr>
                      <td class="align-middle"><?=$res['name']?></td>
                      <td class="align-middle">
                        <?=$res['email_address']?>
                      </td>
                      <td class="align-middle"><?=date("F d, Y", strtotime($res['move_date']))?></td>
                      <td class="align-middle"><?=$res['message']?></td>
                      <td class="align-middle">
                        <button class="btn btn-link btn-small" data-id="<?=$room['id']?>"
                          onclick="viewRoomDetails($(this).attr('data-id'))">Details</button>
                        <button class="btn btn-small btn-link" data-id="<?=$room['id']?>"
                          onclick="updateRoom($(this).attr('data-id'))">Update</button>
                        <button class="btn btn-small btn-link text-success"
                          onclick="window.location.href = `./billing.php?room_id=<?=$room['id']?>`">Bill</button>
                      </td>
                    </tr>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include './../templates/scripts.php' ?>

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

    });
    </script>
</body>

</html>