<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ./tenant/index.php");
}

if (array_key_exists('user_type', $_SESSION['user'])) {
    switch ($_SESSION['user']['user_type']) {
        default:
            header("Location: ./admin/index.php");
    }
} else {
    header("Location: ./tenant/index.php");
}
