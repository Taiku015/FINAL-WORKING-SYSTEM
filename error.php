<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>FarmFresh Mango - Login Error</title>
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
    <?php require 'menu.php'; ?>

    <section id="banner" class="wrapper">
        <div class="container">
            <header class="major">
                <h2>Login Error</h2>
            </header>
            <p>
                <?php
                if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                    echo htmlspecialchars($_SESSION['message']);
                } else {
                    echo "An unexpected error occurred. Please try again.";
                }
                ?>
            </p><br />
            <a href="<?= isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '../index.php' ?>" 
               class="button special">Retry</a>
        </div>
    </section>

    <?php $_SESSION['message'] = ""; ?>

</body>
</html>
