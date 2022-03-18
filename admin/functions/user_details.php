<?php
include './connect.php';

$id = $_GET['id'];

$sql = "SELECT t.*, r.room_number FROM tenants AS t
  INNER JOIN rooms AS r
  ON t.room_id = r.id
  WHERE t.id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

echo json_encode($row);
