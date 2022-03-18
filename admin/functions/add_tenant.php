<?php
include './connect.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$middle_initial = $_POST['middle_initial'];
$room_id = intval($_POST['room_number']);
$birthdate = $_POST['birthdate'];
$gender = intval($_POST['gender']);
$email_address = $_POST['email_address'];
$contact_number = $_POST['contact_number'];
$address = $_POST['address'];
$file = $_FILES['file'];

$errors = [];
$status = 422;

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

// Check if file is image
$acceptedExts = ['jpg', 'jpeg', 'png'];
$ext = explode('.', strtolower($file['name']));
if (!in_array(end($ext), $acceptedExts)) {
    $errors['valid_id'] ='Only .jpg and .png files are accepted.';
} 

// If no errors are encountered, proceed to storing in database
if (count($errors) === 0) {
    $sql = "INSERT INTO tenants
      (`first_name`, `last_name`, `middle_initial`, `room_id`, `birthdate`, `gender`, `email_address`, `contact_number`, `address`, `valid_id`, `password`)
      VALUES ('$first_name', '$last_name', '$middle_initial', '$room_id', '$birthdate', '$gender', '$email_address', '$contact_number', '$address', '" . $file["name"] . "', 'fatima123')";
    $res = mysqli_query($conn, $sql);
    if ($res) {
      move_uploaded_file($file['tmp_name'], './../../uploads/' . $file['name']);
      $status = 200;
    } else {
      $errors['error'] = 'Error in db';
    }
}

echo json_encode([
    'errors' => $errors,
    'status' => $status,
]);
