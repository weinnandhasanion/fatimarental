<?php 
$room_id = $_SESSION['user']['room_id'];
// $sql = "SELECT b.*, r.room_name FROM tenant_bills AS tb
//   INNER JOIN bills AS b
//   ON b.id = tb.bill_id
//   INNER JOIN rooms AS r
//   ON r.id = b.room_id
//   WHERE tenant_id = '" . $user['id'] ."'";
$sql = "SELECT b.*, r.room_name FROM bills AS b
  INNER JOIN rooms AS r
  ON b.room_id = r.id
  WHERE b.room_id = $room_id";
$res = $conn->query($sql);
$bills = $res->fetch_all(MYSQLI_ASSOC);
?>

<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Billing Date</th>
            <th scope="col">Room</th>
            <th scope="col">Total Amount</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bills as $bill): 
      $bill['total'] = intval($bill['room_charge']) + intval($bill['electricity_bill']) + intval($bill['water_bill']);
      $sql = "SELECT SUM(charge) AS `sum` FROM additional_charges WHERE bill_id = " . $bill['id'];
      $res = $conn->query($sql);
      $bill['total'] += $res->fetch_assoc()['sum'];
      ?>
        <tr>
            <td scope="row">
                <?= date("F d, Y", strtotime($bill['start_period']))." - ".date("F d, Y", strtotime($bill['end_period'])) ?>
            </td>
            <td><?= $bill['room_name'] ?></td>
            <td>₱<?= $bill['total'] ?>.00</td>
            <td><button class="btn btn-link" data-id="<?=$bill['id']?>"
                    onclick="window.open('./../shared/bill_details.php?id=' + this.getAttribute('data-id'))">View</button>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>