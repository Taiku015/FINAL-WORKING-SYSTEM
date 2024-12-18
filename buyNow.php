<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to log in first to place an order!";
    header("Location: Login/error.php");
    exit();
}

$bid = $_SESSION['id']; // Buyer ID from session
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0; 

if ($pid <= 0) {
    $_SESSION['message'] = "Invalid Product ID.";
    header('Location: Login/error.php');
    exit();
}

$productQuery = "SELECT * FROM fproduct WHERE pid = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("i", $pid);
$stmt->execute();
$productResult = $stmt->get_result();

if ($productResult->num_rows == 0) {
    $_SESSION['message'] = "Product not found.";
    header("Location: Login/error.php");
    exit();
}

$product = $productResult->fetch_assoc();
$product_name = $product['product'];
$product_price = $product['price'];
$stmt->close();

// Handle form submission for placing the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $city = htmlspecialchars(trim($_POST['city']));
    $mobile = htmlspecialchars(trim($_POST['mobile']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pincode = htmlspecialchars(trim($_POST['pincode']));
    $addr = htmlspecialchars(trim($_POST['addr']));
    
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $_SESSION['message'] = "Invalid mobile number.";
        header("Location: Login/error.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email address.";
        header("Location: Login/error.php");
        exit();
    }

    $insertOrderQuery = "INSERT INTO transaction (bid, pid, name, city, mobile, email, pincode, addr, product_name, price) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertOrderQuery);
    $insertStmt->bind_param("iisssssssd", $bid, $pid, $name, $city, $mobile, $email, $pincode, $addr, $product_name, $product_price);

    if ($insertStmt->execute()) {
        $_SESSION['message'] = "Order successfully placed! Thank you for shopping with FarmFresh Mango.";
        $insertStmt->close();
        header('Location: success.php');
        exit();
    } else {
        $_SESSION['message'] = "Failed to place order: " . $conn->error;
        header('Location: Login/error.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Buy Now</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900); 
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
            <h2>Confirm Your Order</h2>
            <p><strong>Product:</strong> <?= htmlspecialchars($product_name); ?></p>
            <p><strong>Price:</strong> Ksh <?= htmlspecialchars($product_price); ?></p>
            <form method="POST" action="buyNow.php?pid=<?= $pid; ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile Number:</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="pincode">Pincode:</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter your pincode" required>
                </div>
                <div class="form-group">
                    <label for="addr">Address:</label>
                    <textarea class="form-control" id="addr" name="addr" rows="3" placeholder="Enter your full address" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Confirm Order</button>
            </form>
        </div>
    </div>

</body>
</html>
