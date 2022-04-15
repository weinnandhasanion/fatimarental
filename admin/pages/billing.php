<?php
include './../../services/connect.php';
include_once './redirect.php';

$sql = "SELECT b.*, r.room_number FROM bills AS b 
  INNER JOIN rooms AS r
  ON b.room_id = r.id";
$res = $conn->query($sql);
$bills = $res->fetch_all(MYSQLI_ASSOC);
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
        <div class="row ">
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
                      <th>Bill ID</th>
                      <th>Room</th>
                      <th>Start Period</th>
                      <th>End Period</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($bills as $bill) {?>
                    <tr>
                      <td class="align-middle"><?=$bill['id']?></td>
                      <td class="align-middle text-truncate"><?=$bill['room_number']?></td>
                      <td class="align-middle">
                        <?=date("M d, Y", strtotime($bill['start_period']))?>
                      </td>
                      <td class="align-middle">
                        <?=date("M d, Y", strtotime($bill['end_period']))?>
                      </td>
                      <td class="align-middle">Paid</td>
                      <td class="align-middle">
                        <button class="btn btn-link btn-small" data-id="<?=$bill['id']?>"
                          onclick="viewRoomDetails($(this).attr('data-id'))">Details</button>
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
                  <label>Room Number</label>
                  <select class="form-control" type="text" name="room_number" required>
                    <option value="">Select a room...</option>
                    <?php
$sql = "SELECT id, room_number FROM rooms";
$res = $conn->query($sql);
foreach ($res->fetch_all(MYSQLI_ASSOC) as $row) {?>
                    <option value="<?=$row['id']?>"><?=$row['room_number']?></option>
                    <?php }?>
                  </select>
                  <small id='room_number-error' class='text-danger'></small>
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
      $('select[name=room_number]').change(function(e) {
        $.get("./../functions/room_price.php?id=" + $(this).val(), function(res) {
          $('input[name=room_charge]').val(res);
        });
      });

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
          room_id: $('select[name=room_number]').val(),
          room_charge: $('input[name=room_charge]').val(),
          water_bill: $('input[name=water_bill]').val(),
          electricity_bill: $('input[name=electricity_bill]').val(),
          start_period: $('input[name=start_period]').val(),
          end_period: $('input[name=end_period]').val(),
          additional_charges: addtlCharges
        };

        $.post('./../functions/add_bill.php', data, function(data) {
          let res = JSON.parse(data);

          if (res.status !== 200) {
            for (key in res.errors) {
              $(`#${key}-error`).text(res.errors[key]);
            }
          } else {
            alert('Successfully billed room.');
            window.location.reload();
          }
        });
      });
    });
    </script>
</body>

</html>