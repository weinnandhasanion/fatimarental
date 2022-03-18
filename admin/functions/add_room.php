<?php
include './connect.php';

$file = $_FILES['file'];
$room_number = $_POST['room_number'];
$price = $_POST['price'];
$capacity = $_POST['capacity'];
$description = $_POST['description'];

$errors = [];
$status = 422;

if (!preg_match("/^[0-9]+$/", $room_number)) {
    $errors['room_number'] = 'Room number must contain only digits.';
} else {
    $sql = "SELECT * FROM rooms WHERE room_number = " . intval($room_number);
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        $errors['room_number'] = 'Room number already exists.';
    }
}

if (!preg_match("/^[0-9]+$/", $price)) {
    $errors['price'] = 'Room price must contain only digits.';
}

$fileArr = [];
foreach ($file['tmp_name'] as $i => $tmp) {
    $fileArr[] = [
        'tmp_name' => $tmp,
        'name' => $file['name'][$i],
    ];
}

$acceptedExts = ['jpg', 'jpeg', 'png'];
// Check if all files are image
foreach ($fileArr as $file) {
    $ext = explode('.', strtolower($file['name']));
    if (!in_array(end($ext), $acceptedExts)) {
        $errors['room_images'] = 'Only .jpg and .png files are accepted.';
    }
}

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "INSERT INTO rooms
        (`room_number`, `price`, `capacity`, `description`)
        VALUES (" . intval($room_number) . ", " . intval($price) . ", " . intval($capacity) . ", '$description')";
    $res = $conn->query($sql);
    if ($res) {
        $id = $conn->insert_id;
        $values = [];
        foreach ($fileArr as $file) {
            $values[] = "($id, '" . $file['name'] . "')";
        }
        $sql = "INSERT INTO room_images (`room_id`, `image_pathname`) VALUES " . implode(',', $values);
        $res = $conn->query($sql);

        if ($res) {
            foreach ($fileArr as $file) {
                move_uploaded_file($file['tmp_name'], './../../uploads/' . $file['name']);
            }
        }
        $status = 200;
    } else {
        $errors['error'] = 'Error in db';
    }
}

echo json_encode([
    'errors' => $errors,
    'status' => $status,
]);
