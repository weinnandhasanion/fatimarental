<?php
session_start();
include './../../services/connect.php';

$id = $_GET['id'];
$updateStatus = $_GET['status'] === 'approve' ? 1 : 2;
$admin_id = $_SESSION['user']['id'];
$status = 422;

$sql = "UPDATE reservations SET `status` = $updateStatus, admin_id = $admin_id WHERE id = $id";
$res = $conn->query($sql);
if ($res) {
    $status = 200;
}

echo json_encode([
    'status' => $status,
    'message' => $updateStatus === 1 ? "Reservation approved." : "Reservation rejected.",
]);
