<?php
session_start();

// Ensure the user is logged in as a farmer (category 1)
if (!isset($_SESSION['id']) || $_SESSION['Category'] !== 1) {
    // Redirect to login page if not logged in as farmer
    header("Location: login.php");
    exit();
}

// Include database connection
require 'db.php';

// Get the product ID from the URL
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

// If no valid product ID is provided, redirect to the product list
if ($pid <= 0) {
    header("Location: viewProducts.php");
    exit();
}

// Get the farmer ID from the session
$fid = $_SESSION['id'];

// Fetch product details for the given product ID
$productQuery = $conn->prepare("SELECT * FROM fproduct WHERE pid = ? AND fid = ?");
$productQuery->bind_param("ii", $pid, $fid);
$productQuery->execute();
$productResult = $productQuery->get_result();

// If no product is found, redirect to product list
if ($productResult->num_rows == 0) {
    header("Location: viewProducts.php");
    exit();
}

// Fetch the product details
$product = $productResult->fetch_assoc();

// Handle form submission to update product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $productName = $_POST['product'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Validate the form inputs
    if (empty($productName) || empty($price) || empty($quantity)) {
        $errorMessage = "All fields are required!";
    } else {
        // Update the product in the database
        $updateQuery = $conn->prepare("UPDATE fproduct SET product = ?, price = ?, quantity = ? WHERE pid = ? AND fid = ?");
        $updateQuery->bind_param("ssiii", $productName, $price, $quantity, $pid, $fid);
        $updateQuery->execute();

        // Check if the update was successful
        if ($updateQuery->affected_rows > 0) {
            $_SESSION['message'] = "Product updated successfully!";
            header("Location: viewProducts.php");
            exit();
        } else {
            $errorMessage = "Failed to update product!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Update Product</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <h1>Update Product</h1>
            <br />

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <form action="updateProduct.php?pid=<?= $pid ?>" method="POST">
                <div class="form-group">
                    <label for="product">Product Name</label>
                    <input type="text" class="form-control" id="product" name="product" value="<?= htmlspecialchars($product['product']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (Ksh)</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>
                </div>
                <br />
                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connections
$productQuery->close();
$conn->close();
?>
