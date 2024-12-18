<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0) {
    $_SESSION['message'] = "You need to first login to access this page!";
    header("Location: Login/error.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/indexFooter.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>
    <noscript>
        <link rel="stylesheet" href="css/skel.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style-xlarge.css">
    </noscript>

    <style>
        /* Flexbox Layout to space items */
        #one .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #one .row .col-4 {
            text-align: center;
        }

        #one .col-4 img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <!-- Include Menu -->
    <?php require 'menu.php'; ?>

   <!-- Main Section -->
   <section id="one" class="wrapper style1 align-center" style="height: 600px;">
        <div class="container">
            <h2>Welcome to FarmFresh Mango</h2>
            <br><br>
            <div class="row">
                <!-- Profile Section (Left side) -->
                <section class="col-4">
                    <a href="profileView.php">
                        <img src="profileDefault.png" alt="Profile" class="img-fluid">
                    </a>
                    <p>Your Profile</p>
                </section>
                <!-- Products Section (Right side) -->
                <section class="col-4">
                    <a href="productMenu.php?n=1 $ productmenu.php?n=0">
                        <img src="product.png" alt="Our Products" class="img-fluid">
                    </a>
                    <p>Our Products</p>
                </section>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>




