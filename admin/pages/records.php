<?php
include_once './redirect.php';
include './../../services/connect.php';

$record = $_GET['record'] ?? 'payment-ledger';

$statuses = [
  "payment-ledger" => [0, "Payment Ledger"],
  "tenant-room-activity" => [1, "Tenant Room Activity"],
  "room-addition-deletion" => [2, "Room Addition/Deletion"],
  "tenant-addition" => [3, "Tenant Addition/Deletion"]
];

$records = [];

switch ($record) {
  case 'payment-ledger':
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
    break;
  case 'tenant-room-activity':
    $sql = "SELECT t.first_name, t.last_name, tr.*, r.room_name AS to_room_name FROM tenant_room_history AS tr
        INNER JOIN tenants AS t
        ON t.id = tr.tenant_id
        INNER JOIN rooms AS r
        ON r.id = tr.to_room_id
        ORDER BY date_added DESC";
    break;
  case 'room-addition-deletion':
    $sql = "SELECT * FROM rooms ORDER BY date_added DESC";
    break;
  case 'tenant-addition':
    $sql = "SELECT * FROM tenants ORDER BY date_added DESC";
    break;
  default:
    $sql = "SELECT *";
}

$res = $conn->query($sql);
$records = $res->fetch_all(MYSQLI_ASSOC);

if ($record === 'tenant-room-activity') {
  $row = $records;
  $records = [];
  foreach ($row as $r) {
    if (isset($r['from_room_id'])) {
      $id = $r['from_room_id'];
      $sql = "SELECT room_name FROM rooms WHERE id = $id";
      $name = $conn->query($sql)->fetch_assoc()['room_name'];
    } else {
      $name = "N/A";
    }
    $r['from_room_name'] = $name;
    $records[] = $r;
  }
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
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link <?= $record === 'payment-ledger' ? "active" : ""?>" href="./records.php">Payment
                  Ledger</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $record === 'tenant-room-activity' ? "active" : ""?>"
                  href="./records.php?record=tenant-room-activity">Tenant Room Activity</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $record === 'room-addition-deletion' ? "active" : ""?>"
                  href="./records.php?record=room-addition-deletion">Room Addition/Deletion</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $record === 'tenant-addition' ? "active" : ""?>"
                  href="./records.php?record=tenant-addition">Tenant Addition</a>
              </li>
            </ul>
            <?php 
            switch ($record) {
              case 'payment-ledger':
                include_once "./records/payment_ledger.php";
                break;
              case 'tenant-room-activity':
                include_once "./records/tenant_room_history.php";
                break;
              case 'room-addition-deletion':
                include_once "./records/room_addition_deletion.php";
                break;
              case 'tenant-addition':
                include_once "./records/tenant_addition_deletion.php";
                break;
            }
            ?>
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

    const downloadCsv = (data, filename, headers) => {
      let csvContent = `data:text/csv;charset=utf-8,${headers.join(',')}\n${data.map(e => e.join(",")).join("\n")}`;

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement('a');
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", filename);
      link.style.display = 'none';
      document.body.appendChild(link);
      link.click();
      document.remove(link);
    }

    $(document).ready(function() {
      $('#reservations-table').DataTable()

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

      $('.print-btn').on('click', function({
        currentTarget
      }) {
        const data = $('#reservations-table').dataTable().api().rows({
          page: 'current'
        }).data().toArray();

        if (data.length < 1) {
          alert('You cannot print empty records.')
          return;
        }

        const recordType = currentTarget.id.split('_')[1];

        const defaultHeaders = {
          'payment-ledger': ['Name', 'Payment Reference ID', 'Amount', 'Date', 'Year', 'Remarks'],
          'tenant-room-activity': ['Name', 'Previous Room', 'New Room', 'Move Date', 'Year', 'Remarks'],
          'room-addition-deletion': ['Room Name', 'Remarks', 'Date Added', 'Year'],
          'tenant-addition': ['Name', 'Remarks', 'Date Added', 'Year']
        };

        const fileName = `${recordType}.csv`;

        downloadCsv(data, fileName, defaultHeaders[recordType]);
      })
    });
    </script>
</body>

</html>