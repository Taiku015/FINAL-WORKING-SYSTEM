<?php
session_start();

// Ensure the user is logged in as a buyer (category 0)
if (!isset($_SESSION['id']) || $_SESSION['Category'] !== 0) {
    // Redirect to login page if not logged in as buyer
    header("Location: login.php");
    exit();
}

require 'db.php';

// Get the buyer ID from session
$bid = $_SESSION['id'];

// Fetch all transactions made by the logged-in buyer
$transactionQuery = $conn->prepare("SELECT * FROM `transaction` WHERE `bid` = ?");
$transactionQuery->bind_param("i", $bid);
$transactionQuery->execute();
$transactionResult = $transactionQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Transactions - FarmFresh Mango</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Ensure background covers entire page */
        body {
            background-image: url('your-background-image.jpg'); /* Use your desired background image */
            background-size: cover; /* Ensure it covers the entire page */
            background-attachment: fixed; /* Keeps the background fixed during scrolling */
            background-position: center; /* Centers the background */
            background-repeat: no-repeat; /* Prevents background image repetition */
            min-height: 100vh; /* Ensures the body stretches to full height */
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 40px;
            background-color: #11ffee00;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .table thead {
            background-color: #4CAF50;
            color: white;
        }

        .table {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-back {
            margin-top: 20px;
            background-color: rgba(255, 255, 128, .5);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }

        .table img {
            max-width: 80px;
            border-radius: 5px;
        }

        /* Footer styles */
        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 40px; /* Ensure the footer is not sticking to the table */
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <h1 class="text-center mb-4">Your Transactions</h1>

            <?php if ($transactionResult->num_rows > 0): ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Buyer Name</th>
                            <th>City</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $transactionResult->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php
                                    // Get the product name from the fproduct table using the pid
                                    $productQuery = $conn->prepare("SELECT product FROM fproduct WHERE pid = ?");
                                    $productQuery->bind_param("i", $transaction['pid']);
                                    $productQuery->execute();
                                    $productResult = $productQuery->get_result();
                                    $product = $productResult->fetch_assoc();
                                    echo htmlspecialchars($product['product']);
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($transaction['name']) ?></td>
                                <td><?= htmlspecialchars($transaction['city']) ?></td>
                                <td><?= htmlspecialchars($transaction['mobile']) ?></td>
                                <td><?= htmlspecialchars($transaction['email']) ?></td>
                                <td><?= htmlspecialchars($transaction['addr']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">You haven't made any transactions yet.</p>
            <?php endif; ?>

            <div class="text-center">
                <a href="profile.php" class="btn-back">Back to Profile</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 FarmFresh Mango. All rights reserved.</p>
        <p>
            <a href="terms.php">Terms & Conditions</a> |
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close database connections
$transactionQuery->close();
$conn->close();
?>
