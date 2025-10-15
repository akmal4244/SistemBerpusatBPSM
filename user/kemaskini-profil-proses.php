<?php
include '../db-connect.php';
session_start(); // Start the session

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

    // Prepare the SQL statement to update the user's profile
    $sql = "UPDATE users SET userFullname = ?, userName = ?, userEmail = ?, userPosition = ?, userDepartment = ?, userUnit = ?, userType = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $userFullname, $userName, $userEmail, $userPosition, $userDepartment, $userUnit, $userType, $user_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Update the session with the new full name
        $_SESSION['userFullname'] = $userFullname;

        // Redirect to the profile page with a success message
        header("Location: kemaskini-profil.php?id=$user_id&status=success");
    } else {
        // Redirect back with a failed status
        header("Location: kemaskini-profil.php?id=$user_id&status=failed");
    }

    $stmt->close();
    $conn->close();
} else {
    // If the form was not submitted, redirect back to the profile page
    header("Location: kemaskini-profil.php");
    exit();
}
?>
