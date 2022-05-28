<?php
include './../../services/connect.php';

$id = $_GET['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_initial = $_POST['middle_initial'];
$room_id = $_POST['room_name'];
$birthdate = $_POST['birthdate'];
$gender = intval($_POST['gender']);
$email_address = $_POST['email_address'];
$contact_number = $_POST['contact_number'];
$address = $_POST['address'];

$sql = "SELECT * FROM tenants WHERE id = $id";
$res = $conn->query($sql);
$userObj = $res->fetch_all(MYSQLI_ASSOC)[0];

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check if file is image
    $acceptedExts = ['jpg', 'jpeg', 'png'];
    $ext = explode('.', strtolower($file['name']));
    if (!in_array(end($ext), $acceptedExts)) {
        $errors['valid_id'] = 'Only .jpg and .png files are accepted.';
    }
}

$errors = [];
$status = 422;

if (!preg_match("/[^0-9]/", $first_name)) {
    $errors['first_name'] = 'Name should not contain any digits.';
}

if (!preg_match("/[^0-9]/", $middle_initial)) {
    $errors['middle_initial'] = 'Name should not contain any digits.';
}

if (!preg_match("/[^0-9]/", $last_name)) {
    $errors['last_name'] = 'Name should not contain any digits.';
}

// Check if birthday is greater than current date
if (strtotime($birthdate) > time()) {
    $errors['birthdate'] = 'Birthdate must be before current date.';
}

// Check if email exists
$sql = "SELECT * FROM tenants WHERE email_address = '$email_address'";
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) > 1) {
    $errors['email_address'] = 'Email address is already taken.';
}

// Check if contact number if in +639XXXXXXXXX or 09XXXXXXXXX format
if (isset($contact_number) && !preg_match("/^(09|\+639)\d{9}$/", $contact_number)) {
    $errors['contact_number'] = 'Please enter a valid phone number.';
}

if (intval($userObj['room_id']) !== $room_id) {
    // Check if room has available occupancy
    $sql = "SELECT capacity FROM rooms WHERE id = $room_id";
    $res = $conn->query($sql);
    $capacity = intval($res->fetch_assoc()['capacity']);
    $sql = "SELECT * FROM tenants WHERE room_id = $room_id";
    $res = $conn->query($sql);
    if ($res->num_rows === $capacity) {
        $errors['room_name'] = 'Room is already full.';
    }
}

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "UPDATE tenants
        SET `first_name` = '$first_name',
        `last_name` = '$last_name',
        `middle_initial` = '$middle_initial',
        `room_id` = '$room_id',
        `birthdate` = '$birthdate',
        `gender` = '$gender',
        `email_address` = '$email_address',
        `contact_number` = '$contact_number',
        `address` = '$address'
        WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        if (isset($_FILES['file'])) {
            $sql = "UPDATE tenants SET `valid_id` = '" . $file["name"] . "'";
            $res = $conn->query($sql);

            if ($res) {
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
