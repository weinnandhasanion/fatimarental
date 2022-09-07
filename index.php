<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./tenant/index.php");
}

// Check if user in session has user_type property.
// If user_type property exists, it means that the user
// in the session is a admin. If not, the user in
// the session is an tenant.
if (array_key_exists('user_type', $_SESSION['user'])) {
    switch ($_SESSION['user']['user_type']) {
        default:
            header("Location: ./admin/index.php");
    }
} else {
    header("Location: ./tenant/index.php");
}