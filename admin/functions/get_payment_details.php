<?php 
include "./../../services/connect.php";

$id = $_GET['id'];

$sql = "SELECT p.*, b.reference_id AS bill_reference_id,
  r.room_name
  FROM payments AS p
  INNER JOIN bill_payments AS bp
  ON p.id = bp.payment_id
  INNER JOIN bills AS b
  ON bp.bill_id = b.id
  INNER JOIN rooms AS r
  ON r.id = b.room_id
  WHERE p.id = " . intval($id);
$res = $conn->query($sql);
if ($res) {
  $row = $res->fetch_assoc();
  $row['tenant'] = $row['first_name'] ." ".$row['last_name'];
  $row['date_added'] = date('F d, Y', strtotime($row['date_added']));
  echo json_encode($row);
}
?>