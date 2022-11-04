<?php 
include "./../../services/connect.php";

$status = 200;
$res;

if (isset($_GET['bill_id'])) {
  $bill_id = $_GET['bill_id'];
  $sql = "SELECT b.*, r.room_name FROM bills AS b
    INNER JOIN rooms AS r
    ON b.room_id = r.id
    WHERE b.id = $bill_id";
  $res = $conn->query($sql);
} else {
  $reference_id = $_GET['reference_id'];
  $sql = "SELECT b.*, r.room_name FROM bills AS b
    INNER JOIN rooms AS r
    ON b.room_id = r.id
    WHERE b.reference_id = '$reference_id'";
  $res = $conn->query($sql);
}

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