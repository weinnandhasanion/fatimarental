<?php
session_start();
unset($_SESSION['user']);
echo 'Logout successful.';
?>
