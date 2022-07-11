<?php 
include './../../services/connect.php';

$id = $_GET['id'];

$sql = "SELECT re.*, t.first_name, t.last_name, t.email_address, t.contact_number, t.reservation_account_expiry_date, ro.room_name FROM reservations AS re
  INNER JOIN rooms AS ro
  ON re.room_id = ro.id
  INNER JOIN tenants AS t
  ON re.tenant_id = t.id
  WHERE re.id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

$row['move_date'] = date("F d, Y", strtotime($row['move_date']));
$row['date_added'] = date("F d, Y", strtotime($row['date_added']));
$row['reservation_account_expiry_date'] = date("F d, Y", strtotime($row['reservation_account_expiry_date']));
$row['name'] = $row['first_name']." ".$row['last_name'];

echo json_encode($row);
?>