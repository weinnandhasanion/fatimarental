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
    if ($updateStatus === 1) {
        $sql = "SELECT tenant_id FROM reservations WHERE id = $id";
        $tenant_id = $conn->query($sql)->fetch_assoc()['tenant_id'];
        $sql = "UPDATE tenants
        SET `status` = 0,
          reservation_account_expiry_date = NULL,
          `password` = '".password_hash('fatima123', PASSWORD_DEFAULT)."'
        WHERE id = '$tenant_id'";
        $res = $conn->query($sql);
        
        $sql = "SELECT contact_number FROM tenants WHERE id = '$tenant_id'";
        $contact_number = $conn->query($sql)->fetch_assoc();

        if (!empty($contact_number)) sendMessage($contact_number, 'Your reservation has been accepted. Thank you.');

        if ($res) {
            $status = 200;
        }
    } else {
        $sql = "SELECT tenant_id FROM reservations WHERE id = $id";
        $tenant_id = $conn->query($sql)->fetch_assoc()['tenant_id'];
        $sql = "UPDATE tenants
        SET `status` = 2,
            account_status = 1,
            reservation_account_expiry_date = NULL
        WHERE id = '$tenant_id'";
        $res = $conn->query($sql);

        $sql = "SELECT contact_number FROM tenants WHERE id = '$tenant_id'";
        $contact_number = $conn->query($sql)->fetch_assoc();

        if (!empty($contact_number)) sendMessage($contact_number, 'Your reservation has been rejected. Thank you.');

        if ($res) {
            $status = 200;
        }
    }
}

echo json_encode([
    'status' => $status,
    'message' => $updateStatus === 1 ? "Reservation approved." : "Reservation rejected.",
]);
