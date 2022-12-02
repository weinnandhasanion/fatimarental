<?php
// Uncomment this and comment lines
// 9-12 if you are running in local (MAKE SURE TO NOT COMMIT!)
// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "fatimadb";

$servername = "127.0.0.1:3306";
$username = "u630200923_fatimauser";
$password = "Fatima123";
$database = "u630200923_fatimarental";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}