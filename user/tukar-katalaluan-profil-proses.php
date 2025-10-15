<?php
session_start();
include '../db-connect.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];
$repeatNewPassword = $_POST['repeatNewPassword'];

// Check if new passwords match
if ($newPassword !== $repeatNewPassword) {
    header("Location: kemaskini-profil.php?id=$user_id&status=password_mismatch");
    exit();
}

// Fetch the current password from the database
$sql = "SELECT userPass FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if current password is correct
if ($user['userPass'] !== $currentPassword) {
    header("Location: kemaskini-profil.php?id=$user_id&status=incorrect_password");
    exit();
}

// Update the password
$sql = "UPDATE users SET userPass = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newPassword, $user_id);

if ($stmt->execute()) {
    header("Location: kemaskini-profil.php?id=$user_id&status=success");
} else {
    header("Location: kemaskini-profil.php?id=$user_id&status=failed");
}
exit();
?>
