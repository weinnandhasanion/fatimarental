<?php
include './../../services/connect.php';
include_once './redirect.php';
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
                    <?php foreach ($rooms as $room) {?>
                    <tr>
                      <td class="align-middle"><?=$room['room_number']?></td>
                      <td class="align-middle text-truncate"><?=count($room['tenants'])?></td>
                      <td class="align-middle">
                        <?=count($room['tenants']) === intval($room['capacity']) ? 'Full' : 'Available'?>
                      </td>
                      <td class="align-middle"><?=$room['capacity']?></td>
                      <td class="align-middle">
                        <button class="btn btn-link btn-small" data-id="<?=$room['id']?>"
                          onclick="viewRoomDetails($(this).attr('data-id'))">Details</button>
                        <button class="btn btn-small btn-link">Update</button>
                        <button class="btn btn-small btn-link text-success">Bill</button>
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
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-6">
                  <label for="start_period">Start Period</label>
                  <input type="date" name="start_period" class="form-control" />
                </div>
                <div class="col-sm-6">
                  <label for="start_period">End Period</label>
                  <input type="date" name="end_period" class="form-control" />
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
    function renderAdditionalCharge(index) {
      return `
        <div class="row mb-2" id="addtl-charge-${index}">
          <div class="col-sm-5">
            <input type="text" class="form-control addtl-charge-name" name="name-${index}" placeholder="Name">
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
          </div>
          <button class="btn btn-sm btn-link text-danger" onclick="deleteCharge(${index})">Delete</button>
        </div>
      `;
    }

    function deleteCharge(index) {
      $(`#addtl-charge-${index}`).remove();
    }

    $(document).ready(function() {
      let addtlChargeIndex = 0;

      $('#add-charge').click(function() {
        $('#addtl-charges-cont').append(renderAdditionalCharge(addtlChargeIndex));
        addtlChargeIndex++;
      });

      $('#billing-table').DataTable();

      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });

      $('.more-button,.body-overlay').on('click', function() {
        $('#sidebar,.body-overlay').toggleClass('show-nav');
      });

      // Add bill
      $('#add-bill-form').submit(function(e) {
        e.preventDefault();

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
          console.log(data);
        });
      });
    });
    </script>
</body>

</html>