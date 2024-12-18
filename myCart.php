<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to log in first to view your cart!";
    header("Location: Login/error.php");
    exit();
}

$bid = $_SESSION['id']; 
$sql = "
    SELECT 
        c.cartId,
        p.product,
        p.price,
        c.pid
    FROM 
        mycart c
    JOIN 
        fproduct p ON c.pid = p.pid
    WHERE 
        c.bid = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bid);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total price
$totalPrice = 0;
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $totalPrice += $row['price'];
    }
}

// Save cart items to session for the checkout page
$_SESSION['cart_items'] = $items;
$_SESSION['total_price'] = $totalPrice;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffffff, #f0f8ff);
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #28a745;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .btn-danger {
            background-color: #ff4d4d;
            border-color: #ff4d4d;
        }

        .btn-danger:hover {
            background-color: #e60000;
            border-color: #e60000;
        }

        .btn-primary, .btn-success {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }

        .total-section span {
            font-weight: bold;
            color: #28a745;
        }

        .empty-cart {
            text-align: center;
            color: #777;
            font-size: 18px;
        }

        .continue-btn {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Cart</h1>
        <?php if (!empty($items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price (Ksh)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product']); ?></td>
                            <td><?= htmlspecialchars($item['price']); ?> Ksh</td>
                            <td>
                                <a href="removeFromCart.php?cartId=<?= $item['cartId']; ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-section">
                Total Price: <span><?= $totalPrice; ?> Ksh</span>
            </div>
            <div class="continue-btn">
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                <a href="productMenu.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <p class="empty-cart">Your cart is empty.</p>
            <div class="continue-btn">
                <a href="productMenu.php" class="btn btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>