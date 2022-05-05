<?php
include './../../services/connect.php';

$id = $_GET['id'];
$files = [];
$status = 422;
$fileArr = [];

if (isset($_FILES['file'])) {
    $files = $_FILES['file'];
}
foreach ($files['tmp_name'] as $i => $tmp) {
    $fileArr[] = [
        'tmp_name' => $tmp,
        'name' => $files['name'][$i],
    ];
}
$values = [];
foreach ($fileArr as $file) {
    $values[] = "($id, '" . $file['name'] . "')";
}
$sql = "INSERT INTO room_images (room_id, image_pathname)
  VALUES " . implode(", ", $values);
$res = $conn->query($sql);
if ($res) {
    if ($res) {
        foreach ($fileArr as $file) {
            move_uploaded_file($file['tmp_name'], './../../uploads/' . $file['name']);
        }
    }
    $status = 200;
}

echo json_encode(['status' => $status]);
