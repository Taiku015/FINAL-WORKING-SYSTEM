<?php
session_start();
require 'db.php';

// Check if necessary fields are set (rating, comment, and user name in session)
if (!isset($_POST['rating'], $_POST['comment'], $_SESSION['Name'])) {
    die("Invalid input. Please ensure all fields are filled out.");
}

$rating = intval($_POST['rating']);
$review = trim($_POST['comment']);
$name = mysqli_real_escape_string($conn, $_SESSION['Name']);
$pid = intval($_GET['pid']);

// Validate the rating (should be between 1 and 10)
if ($rating < 1 || $rating > 10) {
    die("Rating must be between 1 and 10.");
}

// Validate the comment (ensure it's not empty)
if (empty($review)) {
    die("Comment cannot be empty.");
}

// Prepare the SQL query to insert the review into the database
$sql = "INSERT INTO review (pid, name, rating, comment) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "isis", $pid, $name, $rating, $review);
    
    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Set success message in session
        $_SESSION['message'] = "Thank you for your review!";
    } else {
        // Set error message if query execution fails
        $_SESSION['message'] = "Error submitting your review. Please try again.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = "Error preparing your review. Please try again.";
}

// Close the connection
mysqli_close($conn);

// Redirect back to the product page with success or error message
header("Location: product.php?pid=" . $pid);
exit;
?>
