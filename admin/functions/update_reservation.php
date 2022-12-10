<?php
session_start();
include './../../services/connect.php';
include './send_message.php';

$id = $_GET['id'];
$updateStatus = $_GET['status'] === 'approve' ? 1 : 2;
$admin_id = $_SESSION['user']['id'];
$status = 422;

$sql = "UPDATE reservations SET `status` = $updateStatus, admin_id = $admin_id WHERE id = $id";
$res = $conn->query($sql);

if ($res) {
    $sql2 = "SELECT contact_number FROM reservations WHERE id = $id LIMIT 1";
    $contact_number = $conn->query($sql2)->fetch_assoc()['contact_number'];
    $verb = $updateStatus === 1 ? "accepted" : "rejected";

    if (!empty($contact_number)) {
        sendMessage($contact_number, "Your reservation has been $verb. Thank you.");
    }

    $status = 200;
}

echo json_encode([
    'status' => $status,
    'message' => $updateStatus === 1 ? "Reservation approved." : "Reservation rejected.",
]);