<?php 
$payments = [];
$room_id = $_SESSION['user']['room_id'];
$sql = "SELECT bp.bill_id, bp.payment_id, p.*, b.reference_id, b.room_id AS bill_reference_id, r.room_name
  FROM bill_payments AS bp
  INNER JOIN payments AS p 
  ON bp.payment_id = p.id
  INNER JOIN bills AS b
  ON bp.bill_id = b.id
  INNER JOIN rooms AS r
  ON b.room_id = r.id
  WHERE b.room_id = $room_id";
$res = $conn->query($sql);
$rows = $res->fetch_all(MYSQLI_ASSOC);
foreach ($rows as $row) {
  $payments[] = $row;
}
?>

<table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Reference ID</th>
            <th scope="col">Date of Payment</th>
            <th scope="col">Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment):  ?>
        <tr>
            <td><?= $payment['reference_id'] ?></td>
            <td scope="row">
                <?= date("F d, Y", strtotime($payment['date_added'])) ?>
            </td>
            <td>₱<?= $payment['amount'] ?>.00</td>

        </tr>
        <?php endforeach ?>
    </tbody>
</table>