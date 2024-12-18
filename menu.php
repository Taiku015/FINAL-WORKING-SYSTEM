<?php
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
    $loginProfile = "My Profile: " . htmlspecialchars($_SESSION['Username']); // Sanitize the username
    $logo = "glyphicon glyphicon-user";
    
    // Check user category (1 = Farmer, 0 = Buyer)
    if (isset($_SESSION['Category'])) {
        if ($_SESSION['Category'] == 1) { // Farmer
            $link = "profileView.php"; // Profile page for farmers
        } else { // Non-farmers (Buyers)
            $link = "Login/profile.php"; // Adjusted profile link for non-farmers (Buyers)
        }
    } else {
        // If category is not set, default to login page
        $link = "index.php";
        $loginProfile = "Login";
        $logo = "glyphicon glyphicon-log-in";
    }
} else {
    // If not logged in
    $loginProfile = "Login";
    $link = "index.php"; // Login page or home page
    $logo = "glyphicon glyphicon-log-in";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>
        nav#nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        nav#nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav#nav ul li a {
            color: hwb(152deg 0% 58% / 70%);
            text-decoration: none;
            font-size: 1.2em;
        }

        nav#nav ul li a:hover {
            color: #00a400;
        }
    </style>
</head>
<body>
    <header id="header">
        <h1><a href="index.php">FarmFresh Mango</a></h1>
        <nav id="nav">
            <ul>
                <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href="myCart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
                <li><a href="<?= $link; ?>"><span class="<?= $logo; ?>"></span> <?= $loginProfile; ?></a></li>
                <li><a href="market.php"><span class="glyphicon glyphicon-grain"></span> Digital Market</a></li>
                <li><a href="blogView.php"><span class="glyphicon glyphicon-comment"></span> Blog</a></li>
                <li><a href="faq.php"><span class="glyphicon glyphicon-question-sign"></span> FAQ</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
