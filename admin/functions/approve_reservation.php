<?php 
include "./../../services/connect.php";

$status = 422;

$reservation_id = $_POST['id'];

$sql = "SELECT tenant_id FROM reservations WHERE id = $reservation_id";
$tenant_id = $conn->query($sql)->fetch_assoc();

$sql = "UPDATE reservations SET `status` = 1 WHERE id = $reservation_id";
$res = $conn->query($sql);

if ($res) {
  $sql = "UPDATE tenants 
    SET `status` = 0,
      reservation_account_expiry_date = NULL
    WHERE id = '$tenant_id'";
  $res = $conn->query($sql);

  if ($res) $status = 200;
}

echo json_encode(['status' => $status]);
?>