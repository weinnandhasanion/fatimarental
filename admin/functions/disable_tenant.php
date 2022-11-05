<?php 
include "./../../services/connect.php";

$id = $_POST['id'];

$sql = "UPDATE tenants SET account_status = 1 WHERE id = '$id'";
$res = $conn->query($sql);

// GET TENANT
$sql = "SELECT room_id FROM tenants WHERE id = '$id'";
$room_id = $conn->query($sql)->fetch_assoc()['room_id'];

$sql = "INSERT INTO tenant_room_history
   (tenant_id, from_room_id, to_room_id, end_date) 
   VALUES ('$id', $room_id, null, NOW())";
$res = $conn->query($sql);

if ($res) {
  echo 200;
} else {
  echo 422;
}
?>