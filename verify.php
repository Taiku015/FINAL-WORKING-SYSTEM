<?php
session_start();

require '../db.php'; // Include database connection file

// Check if the email and hash are provided for verification
if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Sanitize the inputs
    $email = dataFilter($_GET['email']);
    $hash = dataFilter($_GET['hash']);

    // Query to check if the account exists and is inactive
    $sql = "SELECT * FROM mango_sellers WHERE Email = ? AND Hash = ? AND Active = '0'";
    $stmt = $conn->prepare($sql); // Use prepared statements to prevent SQL injection
    $stmt->bind_param("ss", $email, $hash); // Bind the parameters
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If no matching account found or already activated
        $_SESSION['message'] = "Account has already been activated or the URL is invalid!";
        header("location: error.php");
        exit();
    } else {
        // Activate the account
        $updateSql = "UPDATE mango_sellers SET Active = '1' WHERE Email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $email);
        $updateResult = $updateStmt->execute();

        if ($updateResult) {
            $_SESSION['message'] = "Your account has been activated! Welcome to Farmfresh Mango!";
            $_SESSION['Active'] = 1;
            header("location: success.php");
            exit();
        } else {
            $_SESSION['message'] = "There was an error activating your account. Please try again.";
            header("location: error.php");
            exit();
        }
    }
} else {
    // If email or hash is not provided
    $_SESSION['message'] = "Invalid credentials provided for account verification!";
    header("location: error.php");
    exit();
}

// Function to sanitize input data
function dataFilter($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
