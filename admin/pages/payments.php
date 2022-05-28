<?php
include_once './redirect.php';
include './../../services/connect.php';

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
        <div class="col-md-12">
          <div class="row ">
            <div class="card" style="min-height: 485px;">
              <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                <h4 class="card-title flex-grow-1">Payments</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#add-payment-modal">Add &#43;</button>

              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover" id='payments-table'>
                  <thead class="text-primary">
                    <tr>
                      <th style="width: 200px">Name</th>
                      <th>Room</th>
                      <th>Date of Payment</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <tr>
                      <td class="align-middle"></td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Reservation Details Modal -->
    <div id="details-modal" tabindex="-1" class="modal fade" role="dialog">
      <form id="details-form" method="POST">
        <div class="modal-dialog" role='document'>
          <div class="modal-content" style="max-width:600px">
            <div class="modal-header">
              <h5 class="modal-title">Reservation Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="details-id" />
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Name</label>
                  <input readonly class="form-control" type="text" name="name" id="name" required>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Email Address</label>
                  <input readonly class="form-control" type="text" name="email_address" id="email_address" required>
                </div>
                <div class="col-sm-6">
                  <label>Contact Number</label>
                  <input readonly class="form-control" type="text" name="contact_number" id="contact_number" required>
                </div>

              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Date Created</label>
                  <input readonly class="form-control" type="text" name="date_added" id="date_added" required>
                </div>
                <div class="col-sm-6">
                  <label>Move Date</label>
                  <input readonly class="form-control" type="text" name="move_date" id="move_date" required>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label>Message</label>
                  <textarea readonly class='form-control' name="" id="message" rows="5" style="resize: none"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="approve" class="btn btn-success">Approve</button>
              <button type="button" id="reject" class="btn btn-danger">Reject</button>
            </div>
          </div>
        </div>
      </form>
    </div>


    <!-- Add Payment Modal -->
    <div id="add-payment-modal" tabindex="-1" class="modal fade" role="dialog">
      <form id="add-payment-form" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg" role='document'>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Payment</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label for="">Room</label>
                  <select name="" id="" class="form-control">
                    <option value="">Select a room...</option>

                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="">Tenant</label>
                  <select name="" id="" class="form-control">
                    <option value="">Select a tenant...</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label for="">Amount</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" name="electricity_bill" class="form-control">
                    <div class="input-group-append">
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                  <label for="">Remarks (Optional)</label>
                  <textarea class="form-control" id="" rows="4" style="resize: none"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <?php include './../templates/scripts.php' ?>

    <script type="text/javascript">
    function viewReservationDetails(id) {
      $.get("./../functions/reservation_details.php?id=" + id, function(resp) {
        const data = JSON.parse(resp);
        $('#details-id').val(data.id);
        for (key in data) {
          $(`#${key}`).val(data[key]);
        }
        $('#details-modal').modal('show');

        $('#approve').click(function() {
          updateReservation(id, 'approve');
        })
        $('#reject').click(function() {
          updateReservation(id, 'reject');
        })
      });
    }

    function updateReservation(id, status) {
      $.get("./../functions/update_reservation.php?id=" + id + "&status=" + status, function(resp) {
        console.log(resp);
        const res = JSON.parse(resp);
        if (res.status === 200) {
          alert(res.message);
          window.location.reload();
        }
      });
    }

    $(document).ready(function() {
      $('#payments-table').DataTable()

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