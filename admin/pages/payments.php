<?php
include_once './redirect.php';
include './../../services/connect.php';

$payments = [];
$sql = "SELECT bp.bill_id, bp.payment_id, p.*, b.reference_id AS bill_reference_id, r.room_name, t.first_name, t.last_name
  FROM bill_payments AS bp
  INNER JOIN payments AS p 
  ON bp.payment_id = p.id
  INNER JOIN bills AS b
  ON bp.bill_id = b.id
  INNER JOIN rooms AS r
  ON b.room_id = r.id
  INNER JOIN tenants AS t
  ON b.bill_to_tenant_id = t.id";
$res = $conn->query($sql);
$rows = $res->fetch_all(MYSQLI_ASSOC);
foreach ($rows as $row) {
  $payments[] = $row;
}
?>

<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
  <style>
  .add-payment-input {
    display: none;
  }
  </style>
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
                      <th>Tenant</th>
                      <th>Room</th>
                      <th>Bill Reference ID</th>
                      <th>Amount</th>
                      <th>Date of Payment</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($payments as $row): ?>
                    <tr>
                      <td class="align-middle"><?=$row['first_name']." ".$row['last_name']?></td>
                      <td class="align-middle"><?=$row['room_name']?></td>
                      <td class="align-middle"><?=$row['bill_reference_id']?></td>
                      <td class="align-middle">P<?=$row['amount']?>.00</td>
                      <td class="align-middle"><?=date('M d, Y', strtotime($row['date_added']))?></td>
                      <td class="align-middle">
                        <button data-id="<?=$row['id']?>" class="btn btn-link"
                          onclick="getPaymentDetails(this)">Details</button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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
                <div class="col-sm-12">
                  <label for="">Bill Reference ID</label>
                  <div class="d-flex align-items-end justify-content-between">
                    <input type="text" class="form-control mr-3" id="reference-input">
                    <button type="button" class="btn btn-primary" id="search-bill-btn">Search</button>
                  </div>
                  <small class="text-danger" id="reference-input-error"></small>
                </div>
              </div>
              <input type="hidden" id="bill_id">
              <div class="row mb-2 add-payment-input">
                <div class="col-sm-6">
                  <label for="">Room</label>
                  <input name="" id="room_name-add" class="form-control" readonly>
                </div>
                <div class="col-sm-6">
                  <label for="">Tenant</label>
                  <input name="" id="tenant_name-add" class="form-control" readonly>
                </div>
              </div>
              <div class="row mb-2 add-payment-input">
                <div class="col-sm-6">
                  <label for="">Amount</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="number" id="payment_amount-add" class="form-control">
                    <div class="input-group-append">
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>
                  <small class="text-danger" id="amount-error"></small>
                </div>
              </div>
              <div class="row mb-2 add-payment-input">
                <div class="col-sm-12">
                  <label for="">Remarks (Optional)</label>
                  <textarea class="form-control" id="remarks" rows="4" style="resize: none"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer add-payment-input">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- View Payment Modal -->
    <div id="view-payment-modal" tabindex="-1" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg" role='document'>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">View Payment Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-2">
              <div class="col-sm-12">
                <label for="">Bill Reference ID</label>
                <input type="text" class="form-control" id="bill_reference_id-view" readonly>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-6">
                <label for="">Room</label>
                <input name="" id="room_name-view" class="form-control" readonly>
              </div>
              <div class="col-sm-6">
                <label for="">Tenant</label>
                <input name="" id="tenant-view" class="form-control" readonly>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-12">
                <label>Payment Reference ID</label>
                <input type="text" class="form-control" readonly id='reference_id-view'>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-6">
                <label for="">Amount</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">₱</span>
                  </div>
                  <input type="number" id="amount-view" class="form-control" readonly>
                  <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                  </div>
                </div>
                <small class="text-danger" id="amount-error"></small>
              </div>
              <div class="col-sm-6">
                <label>Date of Payment</label>
                <input type="text" class="form-control" readonly id='date_added-view'>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-12">
                <label for="">Remarks</label>
                <textarea class="form-control" id="remarks-view" rows="4" style="resize: none" readonly></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include './../templates/scripts.php' ?>

    <script type="text/javascript">
    function showAddPaymentInputs(bool) {
      $('.add-payment-input').css('display', bool ? 'flex' : 'none')
    }

    function getPaymentDetails(el) {
      const id = el.getAttribute('data-id');

      $.get('./../functions/get_payment_details.php?id=' + id, function(res) {
        const data = JSON.parse(res);

        for (key in data) {
          $(`#${key}-view`).val(data[key]);
        }

        $('#view-payment-modal').modal('show')
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

      // Get bill details
      $('#search-bill-btn').click(function() {
        showAddPaymentInputs(false);
        const error_msg = $('#reference-input-error');
        error_msg.text('');
        const reference_id = $('#reference-input').val();
        $.get("./../functions/get_bill_details.php?reference_id=" + reference_id, function(resp) {
          const res = JSON.parse(resp);
          const {
            id,
            room_name,
            first_name,
            last_name
          } = res.data;

          if (res.status === 204) {
            error_msg.text('Reference ID does not exist.');
          } else {
            $('#bill_id').val(id);
            $('#room_name-add').val(room_name);
            $('#tenant_name-add').val(first_name + ' ' + last_name);
            showAddPaymentInputs(true);
          }
        });
      });

      // Add payment
      $("#add-payment-form").submit(function(e) {
        e.preventDefault();
        $('#amount-error').text('');

        const data = {
          bill_id: +$('#bill_id').val(),
          amount: $('#payment_amount-add').val(),
          remarks: $('#remarks').val()
        }

        $.post('./../functions/add_payment.php', data, function(resp) {
          console.log(resp);
          const res = JSON.parse(resp);

          if (res.errors.length > 0) {
            $('#amount-error').text(res.errors.amount)
          } else if (res.status === 200) {
            alert('Payment successfully added!');
            window.location.reload();
          }
        })
      })

    });
    </script>
</body>

</html>