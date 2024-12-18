<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>FarmFresh Mango: Logout</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Bootstrap CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/skel.min.js"></script>
    <script src="../js/init.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/skel.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/style-xlarge.css" />
</head>

<body>
    <?php
    // Safely include the menu
    if (file_exists('menu.php')) {
        include 'menu.php';
    } else {
        echo '<p>Error loading menu. Please try again later.</p>';
    }
    ?>

    <!-- Logout Confirmation Banner -->
    <section id="banner">
        <div class="container">
            <header class="major">
                <h2>Thank You for Visiting!</h2>
                <center>
                    <p>You have been successfully logged out.</p>
                    <div class="6u 12u$(xsmall)">
                        <br />
                        <a href="../index.php" class="button special">HOME</a>
                    </div>
                </center>
            </header>
        </div>
    </section>

    <!-- Additional Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.scrolly.min.js"></script>
    <script src="../assets/js/jquery.scrollex.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
