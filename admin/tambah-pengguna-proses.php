<?php
include '../db-connect.php';

$userName = $_POST['userName'];
$userFullname = $_POST['userFullname'];
$userPass = $_POST['userPass'];
$userEmail = $_POST['userEmail'];
$userPosition = $_POST['userPosition'];
$userDepartment = $_POST['userDepartment'];
$userUnit = $_POST['userUnit'];
$userType = $_POST['userType'];

$sql = "INSERT INTO users (userName, userFullname, userPass, userEmail, userPosition, userDepartment, userUnit, userType) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $userName, $userFullname, $userPass, $userEmail, $userPosition, $userDepartment, $userUnit, $userType);

if ($stmt->execute()) {
    header("Location: laman-pengguna.php?message=Aset berjaya ditambah");
} else {
    header("Location: laman-pengguna.php?error=Gagal untuk tambah aset");
}

$stmt->close();
$conn->close();
?>
