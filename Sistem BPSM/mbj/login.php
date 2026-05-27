<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Dummy credentials for demonstration
    $dummy_username = 'admin';
    $dummy_password = '4244';

    if ($username == $dummy_username && $password == $dummy_password) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
} else {
    $error = 'Invalid request method.';
}
?>
