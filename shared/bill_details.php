<?php
include "./../services/connect.php";

$id = $_GET['id'];
$sql = "SELECT b.*, r.*, t.first_name, t.last_name FROM bills AS b
INNER JOIN rooms AS r
ON b.room_id = r.id
INNER JOIN tenant_bills AS tb
ON tb.bill_id = b.id
INNER JOIN tenants AS t
ON tb.tenant_id = t.id
WHERE b.id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$total_amount = intval($row['room_charge']) + intval($row['electricity_bill']) + intval($row['water_bill']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <title>Billing Details</title>
  <link rel="stylesheet" href="./../admin/css/custom.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="./../admin/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<style>
@media print {
  button#print-btn {
    display: none !important;
  }
}
</style>

<body class="m-3">
  <div class="d-flex justify-content-between" style="width: 100%">
    <h2>Bill Details</h2>
    <button id="print-btn" class="btn btn-link" onclick="print()">Print</button>
  </div>
  <ul class="mt-2">
    <li>
      <h4 style="margin: 0;font-weight: light"><?=$row['first_name'] . " " . $row['last_name']?></h4>
    </li>
    <li>
      <h5 style="font-weight: light"><?=$row['room_name']?></h5>
    </li>
    <li> <em>Billing Date:
        <?=date("F d, Y", strtotime($row['start_period'])) . " - " . date("F d, Y", strtotime($row['end_period']))?></em>
    </li>

    <br>
  </ul>
  <h6>Amount Breakdown</h6>
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th style="width: 70%">Description</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Room Charge</td>
        <td>₱<?= $row['price'] ?>.00</td>
      </tr>
      <tr>
        <td>Electricity Bill</td>
        <td>₱<?= $row['electricity_bill'] ?>.00</td>
      </tr>
      <tr>
        <td>Water Bill</td>
        <td>₱<?= $row['water_bill'] ?>.00</td>
      </tr>
      <?php 
      $sql = "SELECT * FROM additional_charges WHERE bill_id = $id";
      $res = $conn->query($sql);
      if ($res->num_rows > 0) {
        $charges = $res->fetch_all(MYSQLI_ASSOC);
        foreach ($charges as $charge):
          $total_amount += intval($charge['charge']);
      ?>
      <tr>
        <td><?= $charge['name'] ?></td>
        <td>₱<?= $charge['charge'] ?>.00</td>
      </tr>
      <?php endforeach;
      }
       ?>
    </tbody>
  </table>
  <table class="table">
    <tbody>
      <tr>
        <td style="width: 70%">Total Amount</td>
        <td><strong>₱<?= $total_amount ?>.00</strong></td>
      </tr>
    </tbody>
  </table>
  <br>
  <small>For inquiries or concerns regarding this bill, please contact 09152341234.</small>
  <br />
  <small><em>Date Generated: <?= date("M d, Y") ?></em></small>

  <script src="./../js/jquery-3.3.1.min.js"></script>
  <script src="./../js/popper.min.js"></script>
  <script src="./../js/bootstrap.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
</body>

</html>