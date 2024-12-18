<?php
session_start();
require '../db.php';

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You have to log in to view this page!";
    header("Location: ../Login/error.php");
    exit();
}

// Check if the form is submitted and handle input safely
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use ternary operators to check for existence of keys and provide defaults
    $name = isset($_POST['name']) ? dataFilter($_POST['name']) : '';
    $mobile = isset($_POST['mobile']) ? dataFilter($_POST['mobile']) : '';
    $user = isset($_POST['uname']) ? dataFilter($_POST['uname']) : '';
    $email = isset($_POST['email']) ? dataFilter($_POST['email']) : '';
    $address = isset($_POST['address']) ? dataFilter($_POST['address']) : '';
    $rating = isset($_POST['rating']) ? dataFilter($_POST['rating']) : 0;

    // Update session variables
    $_SESSION['Email'] = $email;
    $_SESSION['Name'] = $name;
    $_SESSION['Username'] = $user;
    $_SESSION['MobileNo'] = $mobile;
    $_SESSION['Address'] = $address;
    $_SESSION['Rating'] = $rating;

    // Get the user ID from the session
    $id = $_SESSION['id'];

    // Adjusted SQL query based on the new table structure
    $sql = "UPDATE members 
            SET username = ?, email = ?, mobile = ?, address = ?, rating = ? 
            WHERE id = ?";

    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters to the query
        mysqli_stmt_bind_param($stmt, "ssssdi", $user, $email, $mobile, $address, $rating, $id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Profile update was successful
            $_SESSION['message'] = "Profile updated successfully!";
            header("Location: ../profileView.php");
            exit();
        } else {
            // Error during profile update
            $_SESSION['message'] = "There was an error updating your profile! Please try again.";
            header("Location: ../Login/error.php");
            exit();
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Error preparing the statement
        $_SESSION['message'] = "There was an error preparing the query. Please try again!";
        header("Location: ../Login/error.php");
        exit();
    }
}

// Function to sanitize input data
function dataFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
