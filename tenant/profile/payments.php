<?php 
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
  ON b.bill_to_tenant_id = t.id
  WHERE b.bill_to_tenant_id = '" . $user['id'] ."'";
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