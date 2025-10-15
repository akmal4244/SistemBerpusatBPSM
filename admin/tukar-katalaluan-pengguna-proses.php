<?php
session_start();
include '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $newPassword = $_POST['newPassword'];
    $repeatNewPassword = $_POST['repeatNewPassword'];

    // Validate passwords
    if ($newPassword !== $repeatNewPassword) {
        header("Location: kemaskini-pengguna.php?id=$user_id&status=validation_failed");
        exit();
    }

    // Update password in the database
    $sql = "UPDATE users SET userPass = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newPassword, $user_id);

    if ($stmt->execute()) {
        header("Location: kemaskini-pengguna.php?id=$user_id&status=success");
    } else {
        header("Location: kemaskini-pengguna.php?id=$user_id&status=failed");
    }

    $stmt->close();
    $conn->close();
}
?>
