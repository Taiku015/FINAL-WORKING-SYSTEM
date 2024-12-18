<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You have to log in to view this page!";
    header("Location: Login/error.php");
    exit();
}

// Fallbacks for session variables
$username = isset($_SESSION['Username']) ? htmlspecialchars($_SESSION['Username']) : 'Guest';
$name = isset($_SESSION['Name']) ? htmlspecialchars($_SESSION['Name']) : 'Unknown User';
$rating = isset($_SESSION['Rating']) ? htmlspecialchars($_SESSION['Rating']) : 'Not Rated';
$mobile = isset($_SESSION['Mobile']) ? htmlspecialchars($_SESSION['Mobile']) : 'Not Provided';
$email = isset($_SESSION['Email']) ? htmlspecialchars($_SESSION['Email']) : 'Not Provided';
$addr = isset($_SESSION['Addr']) ? htmlspecialchars($_SESSION['Addr']) : 'Not Provided';
$category = isset($_SESSION['Category']) ? $_SESSION['Category'] : 0; // 1 = Farmer, 0 = Buyer

// Profile picture handling
$profileImage = 'images/defaultProfile.png'; // Default image if the user's profile image is not found

// Check if the session contains a valid profile image
if (isset($_SESSION['picName']) && file_exists('images/profileImages/' . $_SESSION['picName'])) {
    $profileImage = 'images/profileImages/' . htmlspecialchars($_SESSION['picName']) . '?' . mt_rand();
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Profile: <?php echo $username; ?></title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ffcc00, #ff9900);
            padding-top: 50px;
            color: #333;
        }

        .profile-section {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-image {
            border-radius: 50%;
            margin: 20px 0;
            transition: transform 0.3s ease-in-out;
        }

        .profile-image:hover {
            transform: scale(1.1);
        }

        .profile-buttons .btn {
            padding: 10px 20px;
            margin: 10px;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
        }

        .btn-warning {
            background-color: #ff9900;
            border-color: #ff7f00;
        }

        .btn-warning:hover {
            background-color: #ff7f00;
            border-color: #ff9900;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #218838;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #c82333;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #dc3545;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #007bff;
        }

        .profile-details {
            margin: 20px 0;
            text-align: left;
        }

        .welcome-section {
            text-align: center;
            margin: 30px 0;
        }

        /* Custom Badge Styles */
        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navigation Menu -->
    <?php require 'menu.php'; ?>

    <!-- Profile Section -->
    <div class="profile-section">
        <img src="<?php echo $profileImage; ?>" alt="Profile Picture" class="profile-image" height="150">
        <h2><?php echo $name; ?></h2>
        <h4><?php echo $username; ?></h4>

        <!-- Role Indicator with Badge -->
        <p><strong>Role:</strong> 
            <?php 
            if ($category == 1) {
                echo '<span class="badge badge-success">Farmer</span>';  
            } else {
                echo '<span class="badge badge-primary">Buyer</span>';   
            }
            ?>
        </p>

        <div class="profile-details">
            <p><strong>Ratings:</strong> <?php echo $rating; ?></p>
            <p><strong>Mobile No:</strong> <?php echo $mobile; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Address:</strong> <?php echo $addr; ?></p>
        </div>

        <!-- Action Buttons -->
        <div class="profile-buttons">
            <a href="message.php" class="btn btn-warning">Send Message</a>
            <a href="profileEdit.php" class="btn btn-primary">Edit Profile</a>

            <?php if ($category == 1): // If the user is a Farmer ?>
                <a href="uploadProduct.php" class="btn btn-success">Upload Product</a>
            <?php else: // If the user is a Buyer ?>
                <button class="btn btn-success" disabled>Upload Product</button>
            <?php endif; ?>

            <a href="Login/logout.php" class="btn btn-danger">Log Out</a>

            <!-- Conditional Button based on User Type -->
            <?php if ($category == 1): // Farmer ?>
                <a href="viewProducts.php" class="btn btn-info">View Products</a>
            <?php else: // Buyer ?>
                <a href="myCart.php" class="btn btn-info">View Cart</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="welcome-section">
        <h2>Welcome to FarmFresh Mango, <?php echo $name; ?>!</h2>
        <p><?php echo $category == 1 ? "Manage your products and orders here." : "Browse and shop fresh mangoes from top farmers."; ?></p>
    </div>

</body>

</html>
