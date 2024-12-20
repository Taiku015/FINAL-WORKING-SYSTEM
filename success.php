<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>FarmFresh Mango - Success</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/skel.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/style-xlarge.css" />
</head>

<body>
    <?php require 'menu.php'; // Include the navigation menu ?>

    <section id="banner" class="wrapper">
        <div class="container">
            <header class="major">
                <h2>Success</h2>
            </header>
            <p>
                <?php
                // Check and display the session message securely
                if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                    echo htmlspecialchars($_SESSION['message']); // Sanitize output to prevent XSS
                } else {
                    // Redirect to the home page if no message is set
                    header("Location: ../index.php");
                    exit(); // Stop further script execution
                }
                ?>
            </p>
            <br />
            <a href="../index.php" class="button special">Go to Home</a>
        </div>
    </section>

    <?php
    // Clear the session message after displaying it
    unset($_SESSION['message']);
    ?>
</body>
</html>
