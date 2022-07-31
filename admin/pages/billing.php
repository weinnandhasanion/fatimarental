<?php
include './../../services/connect.php';
include_once './redirect.php';

$bills = [];
$sql = "SELECT b.*, r.room_name FROM bills AS b
  INNER JOIN rooms AS r
  ON b.room_id = r.id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
  $rows = $res->fetch_all(MYSQLI_ASSOC);
  foreach ($rows as $i => $row) {
    $sql = "SELECT sum(p.amount) AS total from payments as p 
      inner join bill_payments as bp on bp.payment_id = p.id 
      inner join bills as b on b.id = bp.bill_id
      WHERE bp.bill_id = " . $row['id'];
    $res = $conn->query($sql);
    $amount = $res->fetch_assoc();
    $row['amount_paid'] = $amount['total'] ? intval($amount['total']) : 0;
    $bills[] = $row;
  }
} 
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
          <div class="col-sm-12">
            <div class="card" style="min-height: 485px">
              <div class="card-header card-header-text d-flex justify-content-start align-items-center">
                <h4 class="card-title flex-grow-1">Billing</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#add-bill-modal">New Bill
                  &#43;</button>
              </div>
              <div class="card-content table-responsive">
                <table class="table table-hover" id='billing-table'>
                  <thead class="text-primary">
                    <tr>
                      <th>Room</th>
                      <th>Period (MM/DD/YY)</th>
                      <th>Reference ID</th>
                      <th>Balance</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bills as $bill) {?>
                    <tr>
                      <td class="align-middle text-truncate"><?=$bill['room_name']?></td>
                      <td class="align-middle">
                        <?=date("m/d/y", strtotime($bill['start_period']))?> -
                        <?=date("m/d/y", strtotime($bill['end_period']))?>
                      </td>
                      <td class="align-middle"><?= $bill['reference_id'] ?></td>
                      <td class="align-middle"><?php 
                      echo $bill['amount_paid'] >= $bill['total_amount'] ? "Paid" : "P". (intval($bill['total_amount']) - $bill['amount_paid']) .".00"
                      ?></td>
                      <td class="align-middle">
                        <?php if($bill['total_amount'] > $bill['amount_paid']) {  ?>
                        <button class="btn btn-link btn-small text-success" data-id="<?=$bill['id']?>"
                          onclick="window.location.href='./payments.php?bill_id=<?=$bill['id']?>'">Pay</button>
                        <?php } ?>
                        <button class="btn btn-link btn-small" data-id="<?=$bill['id']?>"
                          onclick="viewBillDetails($(this).attr('data-id'))">Details</button>
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

    <!-- Add Bill Modal -->
    <div id="add-bill-modal" tabindex="-1" class="modal fade" role="dialog">
      <form id="add-bill-form" enctype="multipart/form-data">
        <div class="modal-dialog modal-lg" role='document'>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Bill</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Room</label>
                  <select class="form-control" type="text" name="room_name" required>
                    <option value="">Select a room...</option>
                    <?php
                    $rooms = [];
                    $sql = "SELECT id, room_name FROM rooms";
                    $res = $conn->query($sql); 
                    while ($row = $res->fetch_assoc()) {
                      $sql = "SELECT * FROM tenants WHERE room_id = " . $row['id'];
                      $resNew = $conn->query($sql);
                      if ($resNew->num_rows > 0) {
                        $rooms[] = $row;
                      }
                    } 
                    foreach ($rooms as $room) {?>
                    <option value="<?=$room['id']?>"><?=$room['room_name']?></option>
                    <?php }?>
                  </select>
                  <small id='room_name-error' class='text-danger'></small>
                </div>
                <div class="col-sm-6">
                  <label for="price">Room Charge</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="text" name="room_charge" class="form-control" readonly>
                    <div class="input-group-append">
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>
                  <small class="text-danger" id="price-error"></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label for="capacity">Water Bill</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="text" name="water_bill" class="form-control">
                    <div class="input-group-append">
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>
                  <small id='water_bill-error' class='text-danger error-text'></small>
                </div>
                <div class="col-sm-6">
                  <label for="capacity">Electricity Bill</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">₱</span>
                    </div>
                    <input type="text" name="electricity_bill" class="form-control">
                    <div class="input-group-append">
                      <span class="input-group-text">.00</span>
                    </div>
                  </div>
                  <small id='electricity_bill-error' class='text-danger error-text'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label for="start_period">Start Period</label>
                  <input type="date" name="start_period" class="form-control" />
                  <small id='start_period-error' class='text-danger error-text'></small>
                </div>
                <div class="col-sm-6">
                  <label for="start_period">End Period</label>
                  <input type="date" name="end_period" class="form-control" />
                  <small id='end_period-error' class='text-danger error-text'></small>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label>Bill To</label>
                  <select class="form-control" type="text" name="bill_to" required disabled>
                    <option value="">Select a tenant...</option>
                  </select>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12" style='display: flex; flex-direction: column; align-items: flex-start'>
                  <label for="">Additional Charges</label>
                  <button type='button' class="btn btn-sm btn-secondary" id="add-charge">New &#43;</button>
                </div>
              </div>
              <div id="addtl-charges-cont"></div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <?php include './../templates/scripts.php'?>

    <script type="text/javascript">
    let addtlChargeIndex = 0;

    function deleteCharge(index) {
      $(`#addtl-charge-${index}`).remove();
      addtlChargeIndex--;
      $('.charge-cont').each(function(idx, elem) {
        elem.setAttribute('id', `addtl-charge-${idx}`)
      });
      $('.addtl-charge-name').each(function(idx, elem) {
        elem.setAttribute('name', `name-${idx}`)
      });
      $('.name-error-cont').each(function(idx, elem) {
        elem.setAttribute('id', `name-${idx}-error`)
      });
      $('.charge-error-cont').each(function(idx, elem) {
        elem.setAttribute('id', `charge-${idx}-error`)
      });
      $('.addtl-charge-charge').each(function(idx, elem) {
        elem.setAttribute('name', `charge-${idx}`)
      });
      $('.delete-charge-btn').each(function(idx, elem) {
        elem.setAttribute('onclick', `deleteCharge(${idx})`)
      });
    }

    $(document).ready(function() {
      if (window.location.search) openAddBillModal();

      function openAddBillModal() {
        const id = parseInt(window.location.search.substring(window.location.search.indexOf('=') + 1))
        $('select[name=room_name]').val(id);
        $.get('./../functions/room_price.php?id=' + id, function(res) {
          $('input[name=room_charge]').val(res);
        });
        renderBillToOptions($('select[name=bill_to]'), id);
        $('select[name=bill_to]').removeAttr('disabled');
        $('#add-bill-modal').modal('show');
      }

      $('#add-charge').click(function() {
        $('#addtl-charges-cont').append(renderAdditionalCharge(addtlChargeIndex));
        addtlChargeIndex++;
      });

      function renderAdditionalCharge(index) {
        return `
        <div class="row mb-2 charge-cont" id="addtl-charge-${index}">
          <div class="col-sm-5">
            <input type="text" class="form-control addtl-charge-name" name="name-${index}" placeholder="Name">
            <small id='name-${index}-error' class='text-danger name-error-cont error-text'></small>
          </div>
          <div class="col-sm-5">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">₱</span>
              </div>
              <input type="text" name="charge-${index}" class="form-control addtl-charge-charge" placeholder="Charge">
              <div class="input-group-append">
                <span class="input-group-text">.00</span>
              </div>
            </div>
            <small id='charge-${index}-error' class='text-danger charge-error-cont error-text'></small>
          </div>
          <button type="button" class="btn btn-sm btn-link text-danger delete-charge-btn" onclick="deleteCharge(${index})">Delete</button>
        </div>
      `;
      }

      $('#billing-table').DataTable();

      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });

      $('.more-button,.body-overlay').on('click', function() {
        $('#sidebar,.body-overlay').toggleClass('show-nav');
      });

      // When choosing room, update room charge input field
      $('select[name=room_name]').change(function(e) {
        const val = $(this).val();
        const billToSelect = $('select[name=bill_to]');
        renderBillToOptions(billToSelect, val);
        val === '' ? billToSelect.attr('disabled', 'disabled') : billToSelect.removeAttr('disabled');
        $.get("./../functions/room_price.php?id=" + $(this).val(), function(res) {
          $('input[name=room_charge]').val(res);
        });
      });

      function renderBillToOptions(elem, val) {
        if (val === '') {
          elem.html('<option value="">Select a tenant...</option>')
          return;
        }

        $.get("./../functions/get_room_tenants.php?id=" + val, function(res) {
          const data = JSON.parse(res);
          const defaultOption = elem.html().trim();
          const options = data.map(d => `<option value="${d.id}">${d.first_name} ${d.last_name}</option>`).join(
            ' ');
          elem.html(`${defaultOption} ${options}`);
        });
      }

      // Add bill
      $('#add-bill-form').submit(function(e) {
        e.preventDefault();

        $('.error-text').each(function(_, el) {
          if (el.innerText !== '') el.innerText = '';
        });

        let addtlCharges = [];
        $('.addtl-charge-name').each(function(idx, el) {
          addtlCharges = [...addtlCharges, {
            [`name-${idx}`]: el.value,
            [`charge-${idx}`]: $(`input[name=charge-${idx}]`).val()
          }];
        });

        const data = {
          room_id: $('select[name=room_name]').val(),
          room_charge: $('input[name=room_charge]').val(),
          water_bill: $('input[name=water_bill]').val(),
          electricity_bill: $('input[name=electricity_bill]').val(),
          start_period: $('input[name=start_period]').val(),
          end_period: $('input[name=end_period]').val(),
          bill_to: $('select[name=bill_to]').val(),
          additional_charges: addtlCharges
        };

        $.post('./../functions/add_bill.php', data, function(data) {
          console.log(data);
          let res = JSON.parse(data);

          if (res.status !== 200) {
            for (key in res.errors) {
              $(`#${key}-error`).text(res.errors[key]);
            }
          } else {
            alert('Successfully billed room.');
            window.location.href = './billing.php';
          }
        });
      });
    });

    function viewBillDetails(id) {
      window.open("./../../shared/bill_details.php?id=" + id, "_blank");
    }
    </script>
</body>

</html>