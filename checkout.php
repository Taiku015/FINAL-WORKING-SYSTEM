<?php
session_start();
require 'db.php'; // Ensure DB connection

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to log in first to access this page!";
    header("Location: Login/error.php");
    exit();
}

// Ensure session variables 'id' and 'role' are set
if (!isset($_SESSION['id'])) {
    $_SESSION['message'] = "Session ID or role is missing. Please log in again.";
    header("Location: Login/error.php");
    exit();
}

$bid = $_SESSION['id']; // User ID from session

// SQL query to fetch transaction details for the logged-in user (both buyer and farmer)
$query = "SELECT t.*, p.product AS product_name, p.price 
          FROM transaction t 
          JOIN fproduct p ON t.pid = p.pid
          WHERE t.bid = ? LIMIT 1";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("i", $bid);

// Execute the query
if (!$stmt->execute()) {
    die('Execute error: ' . $stmt->error);
}

$result = $stmt->get_result();

// Check if there is any order
if ($result->num_rows === 0) {
    $_SESSION['message'] = "No recent orders found.";
    header("Location: Login/error.php");
    exit();
}

$order = $result->fetch_assoc();
$pid = $order['pid'];
$product_name = $order['product_name'];
$price = $order['price'];
$name = $order['name'];
$city = $order['city'];
$mobile = $order['mobile'];
$email = $order['email'];
$pincode = $order['pincode'];
$addr = $order['addr'];

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Checkout</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); /* Gradient background */
            padding-top: 50px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .transaction-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border: 1px solid #eee;
        }

        .transaction-form h2 {
            text-align: center;
            color: #ff9900;
            font-size: 32px;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: none;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #ff9900;
            box-shadow: 0 0 5px rgba(255, 153, 0, 0.8);
        }

        .btn-primary {
            background-color: #ff9900;
            border-color: #ff9900;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 18px;
            width: 100%;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #e68900;
            border-color: #e68900;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        /* Hover and Focus Effects */
        .form-group input:focus, .form-group textarea:focus {
            border-color: #ff9900;
            box-shadow: 0 0 5px rgba(255, 153, 0, 0.8);
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .transaction-form {
                padding: 20px;
            }
        }

    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="transaction-form">
            <h2>Transaction Details</h2>
            <form method="POST" action="buyNow.php?pid=<?= $pid; ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($city); ?>" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile Number:</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile" value="<?= htmlspecialchars($mobile); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="pincode">Pincode:</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" value="<?= htmlspecialchars($pincode); ?>" required>
                </div>
                <div class="form-group">
                    <label for="addr">Address:</label>
                    <textarea class="form-control" id="addr" name="addr" rows="3" required><?= htmlspecialchars($addr); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Confirm Order</button>
            </form>
        </div>
    </div>

</body>
</html>
