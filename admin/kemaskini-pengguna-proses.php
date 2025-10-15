<?php
include '../db-connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user_id = $_POST['user_id'];
    $userFullname = $_POST['userFullname'];
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    $userPosition = $_POST['userPosition'];
    $userDepartment = $_POST['userDepartment'];
    $userUnit = $_POST['userUnit'];
    $userType = $_POST['userType'];
    $userStatus = isset($_POST['userStatus']) ? 1 : 0;

    // Prepare the SQL statement to update the user's profile
    $sql = "UPDATE users SET userName = ?, userFullname = ?, userEmail = ?, userPosition = ?, userDepartment = ?, userUnit = ?, userType = ?, userStatus = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $userName, $userFullname, $userEmail, $userPosition, $userDepartment, $userUnit, $userType, $userStatus, $user_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {

        // Redirect to the profile page with a success message
        header("Location: kemaskini-pengguna.php?id=$user_id&status=success");
    } else {
        // Redirect back with a failed status
        header("Location: kemaskini-pengguna.php?id=$user_id&status=failed");
    }

    $stmt->close();
    $conn->close();
} else {
    // If the form was not submitted, redirect back to the profile page
    header("Location: kemaskini-pengguna.php");
    exit();
}
?>
