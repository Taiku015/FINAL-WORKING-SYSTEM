<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Add your stylesheet path -->
</head>
<body class="subpage">

<!-- Header -->
<header id="header" class="alt">
    <div class="logo">
        <a href="index.html">
            <img src="images/logo.jpg" alt="Logo" height="40px" width="40px">
        </a>
    </div>
    <a href="#menu"></a>
    <span>
        <font color="white"><b>MENU</b></font>
    </span>
</header>

<?php
    session_start(); // Ensure session_start() is called at the beginning of the script.

    // Determine login or profile link based on session status
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
        $loginProfile = "My Profile: " . htmlspecialchars($_SESSION['Username']); // Escape username to prevent XSS
        $link = "profileView.php";
    } else {
        $loginProfile = "Login";
        $link = "Login.php";
    }
?>

<!-- Navigation Menu -->
<nav id="menu">
    <ul class="links">
        <li><a href="<?php echo htmlspecialchars($link); ?>"><?php echo htmlspecialchars($loginProfile); ?></a></li>
        <li><a href="index.php">Home</a></li>
        <li><a href="events.html">Events</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="forum.html">Forum</a></li>
        <li><a href="boardMembers.html">Board Members</a></li>
        <li><a href="aboutUs.html">About Us</a></li> <!-- Corrected empty href -->
        <li><a href="#footer">Contact Us</a></li>
    </ul>
</nav>

</body>
</html>
