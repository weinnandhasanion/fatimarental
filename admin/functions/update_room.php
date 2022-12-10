<?php
include './../../services/connect.php';

$room_id = $_POST['id'];
$room_name = $_POST['room_name'];
$price = $_POST['price'];
$capacity = $_POST['capacity'];
$description = $_POST['description'];
$roomStatus = $_POST['status'];

$errors = [];
$status = 422;

$sql = "SELECT * FROM rooms WHERE room_name = '" . $room_name . "' AND id != $room_id";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
    $errors['room_name'] = 'Room name already exists.';
}

if (!preg_match("/^[0-9]+$/", $price)) {
    $errors['price'] = 'Room price must contain only digits.';
}

if ($roomStatus === "2") {
    $sql = "SELECT * FROM tenants WHERE room_id = $room_id AND account_status = 0";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $errors['status'] = "Cannot set status to under maintenance. Room still has tenants.";
    }
}

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "UPDATE rooms
        SET room_name = '" . $room_name . "',
            price = " . intval($price) . ",
            capacity = " . intval($capacity) . ",
            `description` = '$description',
            `status` = ". intval($roomStatus) ."
        WHERE id = $room_id";
    $res = $conn->query($sql);
    if ($res) {
        $status = 200;
    } else {
        $errors['error'] = 'Error in db';
    }
}

echo json_encode([
    'errors' => $errors,
    'status' => $status,
]);