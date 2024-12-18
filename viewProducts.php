<?php
session_start();

// Ensure the user is logged in as a farmer (category 1)
if (!isset($_SESSION['id']) || $_SESSION['Category'] !== 1) {
    // Redirect to login page if not logged in as farmer
    header("Location: login.php");
    exit();
}

require 'db.php';

// Get the farmer ID from session
$fid = $_SESSION['id'];

// Fetch all products uploaded by the logged-in farmer
$productQuery = $conn->prepare("SELECT * FROM fproduct WHERE fid = ?");
$productQuery->bind_param("i", $fid);
$productQuery->execute();
$productResult = $productQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: View Products</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        h1 {
            color: #4CAF50;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table {
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .table th {
            background-color: #4CAF50;
            color: #fff;
        }

        .table td {
            color: #333;
        }

        .table img {
            border-radius: 5px;
        }

        .btn {
            margin: 5px;
        }

        .btn-warning {
            background-color: #FF9800;
            border-color: #FF9800;
        }

        .btn-danger {
            background-color: #F44336;
            border-color: #F44336;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <h1>Your Uploaded Products</h1>
            <br />

            <?php if ($productResult->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price (Ksh)</th>
                            <th>Quantity</th>
                            <th>Product Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $productResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['product']) ?></td>
                                <td><?= htmlspecialchars($product['price']) ?> Ksh</td>
                                <td><?= htmlspecialchars($product['quantity']) ?></td>
                                <td><img src="images/productImages/<?= htmlspecialchars($product['pimage']) ?>" width="100" alt="<?= htmlspecialchars($product['product']) ?>"></td>
                                <td>
                                    <a href="updateProduct.php?pid=<?= $product['pid'] ?>" class="btn btn-warning btn-sm">Update</a>
                                    <a href="deleteProduct.php?pid=<?= $product['pid'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-info">You have not uploaded any products yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close database connections
$productQuery->close();
$conn->close();
?>
