<?php
session_start();
require 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to log in first!";
    header("Location: Login/error.php");
    exit();
}

// Check if cartId is provided in the URL and is valid
if (isset($_GET['cartId']) && filter_var($_GET['cartId'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    $cartId = intval($_GET['cartId']); // Convert to integer

    // Prepare the delete query
    $deleteQuery = "DELETE FROM mycart WHERE cartId = ?";
    $deleteStmt = $conn->prepare($deleteQuery);

    if ($deleteStmt) {
        $deleteStmt->bind_param("i", $cartId); // Bind the cartId
        if ($deleteStmt->execute()) { // Execute the query
            $_SESSION['message'] = "Product removed from your cart successfully.";
        } else {
            $_SESSION['message'] = "Failed to remove product: " . $deleteStmt->error;
        }
        $deleteStmt->close(); // Close the statement
    } else {
        $_SESSION['message'] = "Error preparing the statement: " . $conn->error;
    }
} else {
    $_SESSION['message'] = "Invalid request or product ID.";
}

// Redirect back to the cart page
header("Location: myCart.php");
exit();
?>

