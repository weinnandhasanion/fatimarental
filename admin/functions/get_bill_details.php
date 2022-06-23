<?php 
include "./../../services/connect.php";

$reference_id = $_GET['reference_id'];
$status = 200;

$sql = "SELECT b.*, r.room_name, t.first_name, t.last_name FROM bills AS b
  INNER JOIN rooms AS r
  ON b.room_id = r.id
  INNER JOIN tenants AS t
  ON b.bill_to_tenant_id = t.id
  WHERE reference_id = '$reference_id'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
  $data = $res->fetch_assoc();
} else {
  $data = null;
  $status = 204;
}

echo json_encode([
  'status' => $status,
  'data' => $data
])
?>