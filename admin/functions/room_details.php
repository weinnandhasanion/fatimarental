<?php
include './../../services/connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM rooms WHERE id = $id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
$row['tenants'] = 'N/A';
$row['room_images'] = [];
$row['reserved_tenants'] = 0;

$sql = "SELECT COUNT(*) AS reserved_tenants FROM tenants WHERE account_status = 0 AND room_id = $id AND `status` = 1";
$res = $conn->query($sql);

$row['reserved_tenants'] = $res->fetch_assoc()['reserved_tenants'];

// Get tenants
$sql = "SELECT first_name, last_name FROM tenants WHERE room_id = $id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
    $arr = [];
    foreach ($res->fetch_all(MYSQLI_ASSOC) as $tenant) {
        $arr[] = $tenant['first_name'] . " " . $tenant['last_name'];
    }
    $row['tenants'] = implode(', ', $arr);
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
