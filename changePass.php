<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $user = dataFilter($_POST['uname']);
    $currPass = $_POST['currPass'];
    $newPass = $_POST['newPass'];
    $conNewPass = $_POST['conNewPass'];
    $newHash = dataFilter(md5(rand(0, 1000)));

    // Check if username exists
    $sql = "SELECT * FROM members WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Invalid User Credentials!";
        header("location: ../Login/error.php");
        exit;
    }

    $User = $result->fetch_assoc();

    // Verify current password
    if (!password_verify($currPass, $User['Password'])) {
        $_SESSION['message'] = "Invalid current User Credentials!";
        header("location: ../Login/error.php");
        exit;
    }

    // Check if new password matches confirmation
    if ($newPass !== $conNewPass) {
        $_SESSION['message'] = "New passwords do not match!";
        header("location: ../Login/error.php");
        exit;
    }

    // Hash the new password
    $hashedNewPass = password_hash($newPass, PASSWORD_BCRYPT);
    $currHash = $_SESSION['Hash'];

    // Update the password in the database
    $sql = "UPDATE members SET Password = ?, Hash = ? WHERE Hash = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $hashedNewPass, $newHash, $currHash);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Password changed successfully!";
        header("location: ../Login/success.php");
        exit;
    } else {
        $_SESSION['message'] = "Error occurred while changing the password. Please try again!";
        header("location: ../Login/error.php");
        exit;
    }
}

// Function to sanitize user input
function dataFilter($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

