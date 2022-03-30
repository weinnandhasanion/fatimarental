<?php
include './../../services/connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM rooms WHERE id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$row['tenants'] = 'N/A';
$row['room_images'] = [];

// Get tenants
$sql = "SELECT first_name, last_name FROM tenants WHERE room_id = $id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
  $arr = [];
  foreach ($res->fetch_all(MYSQLI_ASSOC) as $tenant) {
    $arr[] = $tenant['first_name']." ".$tenant['last_name'];
  }
  $row['tenants'] = implode(',', $arr);
}

// Get room images
$sql = "SELECT * FROM room_images WHERE room_id = $id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
  foreach ($res->fetch_all(MYSQLI_ASSOC) as $img) {
    $row['room_images'][] = $img;
  }
}

echo json_encode($row);