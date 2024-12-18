<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
    exit(); 
} else {
    // Fetch user details from the session
    $email = htmlspecialchars($_SESSION['Email']);
    $name = htmlspecialchars($_SESSION['Name']);
    $user = htmlspecialchars($_SESSION['Username']);
    $mobile = htmlspecialchars($_SESSION['Mobile']);
    $active = $_SESSION['Active'];

    // Ensure 'Category' is set in session
    if (isset($_SESSION['Category'])) {
        $category = $_SESSION['Category'];
    } else {
        $category = 0; // Assuming 0 is for an unknown user type (e.g., buyer)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FarmFresh Mango</title>

    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/skel.min.js"></script>
    <script src="../js/skel-layers.min.js"></script>
    <script src="../js/init.js"></script>

    <link rel="stylesheet" href="../css/skel.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/style-xlarge.css" />
</head>
<body>
    <?php require 'menu.php'; // Include menu at the top ?>

    <section id="banner" class="wrapper">
        <div class="container">
            <header class="major">
                <h2>Welcome</h2>
            </header>

            <p>
                <?php
                    // Display session message (error or success)
                    if (isset($_SESSION['message'])) {
                        echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
                        unset($_SESSION['message']);
                    }
                ?>
            </p>

            <?php
                // Check if the user's account is verified
                if (!$active) {
                    echo "<div class='alert alert-warning'>
                            Account is not verified! Please confirm your email by clicking on the email link!
                          </div>";
                }
            ?>

            <h2><?php echo $name; ?></h2>
            <p><?php echo $email; ?></p>

            <?php if ($category == 1): // Farmer ?>
                <div class="row uniform">
                    <div class="6u 12u$(xsmall)">
                        <a href="../profileView.php" class="button special">My Profile</a>
                    </div>
                    <div class="6u 12u$(xsmall)">
                        <a href="logout.php" class="button special">LOG OUT</a>
                    </div>
                </div>
            <?php else: // Buyer ?>
                <div class="row uniform">
                    <div class="6u 12u$(xsmall)">
                        <a href="../market.php" class="button special">Digital Market</a>
                    </div>
                    <div class="6u 12u$(xsmall)">
                        <a href="logout.php" class="button special">LOG OUT</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer Section (Optional) -->
    <footer id="footer">
        <div class="container">
            <p>&copy; 2024 FarmFresh Mango. For Greater Future</p>
        </div>
    </footer>

</body>
</html>
