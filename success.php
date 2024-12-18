<?php
session_start();
require 'db.php'; // Include your DB connection

// Check if the user is logged in, otherwise redirect to login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to log in first!";
    header("Location: Login/error.php");
    exit();
}

// Get the success message from the session
$successMessage = isset($_SESSION['message']) ? $_SESSION['message'] : "Your transaction was successful!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Order Success</title>
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

        .success-message {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border: 1px solid #eee;
        }

        .success-message h2 {
            text-align: center;
            color: #ff9900;
            font-size: 32px;
            margin-bottom: 30px;
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
    </style>
</head>
<body>

    <?php include 'menu.php'; ?> <!-- Include your navigation menu -->

    <div class="container">
        <div class="success-message">
            <h2>Transaction Successful</h2>
            <p class="text-center"><?php echo $successMessage; ?></p>
            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
            <a href="myTransactions.php" class="btn btn-primary" style="margin-top: 10px;">My Transactions</a>
        </div>
    </div>

</body>
</html>

<?php
// Clear the success message after displaying it
unset($_SESSION['message']);
?>
