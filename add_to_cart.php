<?php
session_start();
require 'db.php'; // Database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check session and input validation
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die("Session ID (Buyer ID) is missing or invalid.");
}

if (!isset($_GET['pid']) || !filter_var($_GET['pid'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    die("Product ID is missing or invalid.");
}

$bid = $_SESSION['id'];
$pid = intval($_GET['pid']);

// Test database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if product is already in the cart
$checkQuery = "SELECT * FROM mycart WHERE bid = ? AND pid = ?";
$checkStmt = $conn->prepare($checkQuery);

if (!$checkStmt) {
    die("Error preparing SELECT query: " . $conn->error);
}

$checkStmt->bind_param("ii",$bid, $pid);
if (!$checkStmt->execute()) {
    die("Error executing SELECT query: " . $checkStmt->error);
}

$result = $checkStmt->get_result();
if ($result->num_rows > 0) {
    die("Product already exists in the cart.");
}
$checkStmt->close();

// Insert product into cart
$insertQuery = "INSERT INTO mycart (bid, pid) VALUES (?, ?)";
$insertStmt = $conn->prepare($insertQuery);

if (!$insertStmt) {
    die("Error preparing INSERT query: " . $conn->error);
}

$insertStmt->bind_param("ii", $bid, $pid);
if (!$insertStmt->execute()) {
    die("Error executing INSERT query: " . $insertStmt->error);
}

echo "Product added to cart successfully.";
$insertStmt->close();
$conn->close();

// Redirect to cart page
header("Location: myCart.php");
exit();
?>