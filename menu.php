<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine the profile/login link and display name based on the login status
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
    // Ensure the 'Category' session variable is set
    $loginProfile = "My Profile: " . $_SESSION['Username'];
    $logo = "glyphicon glyphicon-user";
    
    // Check if 'Category' is set in session before using it
    if (isset($_SESSION['Category']) && $_SESSION['Category'] != 1) {
        $link = "profile.php";  // Non-farmer profile link
    } else {
        $link = "../profileView.php";  // Farmer profile link
    }
} else {
    $loginProfile = "Login";
    $link = "../index.php";  // Login or home page
    $logo = "glyphicon glyphicon-log-in";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>FarmFresh Mango</title>
</head>
<body>
    <header id="header">
        <h1><a href="index.php">FarmFresh Mango</a></h1>
        <nav id="nav">
            <ul>
                <li><a href="../index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href="../myCart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
                <li><a href="<?= $link; ?>"><span class="<?= $logo; ?>"></span> <?= $loginProfile; ?></a></li>
                <li><a href="../market.php"><span class="glyphicon glyphicon-grain"></span> Digital Market</a></li>
                <li><a href="../blogView.php"><span class="glyphicon glyphicon-comment"></span> Blog</a></li>
                <li><a href="../faq.php"><span class="glyphicon glyphicon-question-sign"></span> FAQ</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
