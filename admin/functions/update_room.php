<?php
include './../../services/connect.php';

$room_id = $_POST['id'];
$room_number = $_POST['room_number'];
$price = $_POST['price'];
$capacity = $_POST['capacity'];
$description = $_POST['description'];

$errors = [];
$status = 422;

if (!preg_match("/^[0-9]+$/", $room_number)) {
    $errors['room_number'] = 'Room number must contain only digits.';
} else {
    $sql = "SELECT * FROM rooms WHERE room_number = " . intval($room_number) . "
    AND id != $room_id";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $errors['room_number'] = 'Room number already exists.';
    }
}

if (!preg_match("/^[0-9]+$/", $price)) {
    $errors['price'] = 'Room price must contain only digits.';
}

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "UPDATE rooms
        SET room_number = " . intval($room_number) . ",
            price = " . intval($price) . ",
            capacity = " . intval($capacity) . ",
            `description` = '$description'
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
