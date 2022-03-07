<?php
include './connect.php';
session_start();

$errorObj = [
    'status' => 422,
    'message' => 'Invalid credentials!',
];

$username = $_POST['username'];
$password = $_POST['password'];

if (!isset($username) || !isset($password)) {
    echo json_encode($errorObj);
} else {
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (!password_verify($password, $row['password'])) {
            echo json_encode($errorObj);
        } else {
            unset($row['password']);
            $_SESSION['user'] = $row;
            echo json_encode([
                'status' => 200,
                'message' => 'Login successful!',
            ]);
        }
    } else {
        echo json_encode($errorObj);
    }
}
