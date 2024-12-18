<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You need to log in to access this page!";
    header("Location: Login/error.php");
    exit();
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>FarmFresh Mango: Write a Blog</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
</head>
<body class="subpage">

    <!-- Include Navigation -->
    <?php include 'menu.php'; ?>

    <form method="post" action="Blog/blogSubmit.php">
        <section id="main" class="wrapper">
            <div class="inner">
                <div class="container" style="width: 70%;">
                    <div class="row uniform">
                        <div class="9u 12u$(small)">
                            <!-- Placeholder for additional options if needed -->
                        </div>
                        <div class="3u 12u$(small)">
                            <a href="blogview.php" class="button special fit">View Blogs</a>
                        </div>
                    </div>
                    <br>
                    <div class="box">
                        <div class="row uniform">
                            <!-- Blog Title -->
                            <div class="12u$">
                                <input type="text" name="blogTitle" id="blogTitle" placeholder="Enter Blog Title" required>
                            </div>

                            <!-- Blog Content -->
                            <div class="12u$">
                                <textarea name="blogContent" id="blogContent" rows="10" placeholder="Write your blog content here..." required></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="12u$">
                                <center>
                                    <input type="submit" name="submit" class="button special" value="Submit">
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <!-- Initialize CKEditor -->
    <script> update 
        CKEDITOR.replace('blogContent');
    </script>

    <!-- Additional Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>