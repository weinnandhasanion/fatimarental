<?php
include './../../services/connect.php';

$file = [];
$room_name = $_POST['room_name'];
$price = $_POST['price'];
$capacity = $_POST['capacity'];
$description = $_POST['description'];

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
}

$errors = [];
$status = 422;

$sql = "SELECT * FROM rooms WHERE room_name = '" . $room_name . "'";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
    $errors['room_name'] = 'Room name already exists.';
}

if (!preg_match("/^[0-9]+$/", $price)) {
    $errors['price'] = 'Room price must contain only digits.';
}

$fileArr = [];
if (isset($_FILES['file'])) {
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
}

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "INSERT INTO rooms
        (`room_name`, `price`, `capacity`, `description`)
        VALUES ('" . $room_name . "', " . intval($price) . ", " . intval($capacity) . ", '$description')";
    $res = $conn->query($sql);
    if ($res) {
        if (isset($_FILES['file'])) {
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
        }
        $status = 200;
    }
}

echo json_encode([
    'errors' => $errors,
    'status' => $status,
]);
