<?php
session_start();
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profile: <?php echo $_SESSION['Username']; ?></title>

    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css" />

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    require 'menu.php'; // Include menu
    ?>

    <section id="profile" class="wrapper bg-img" data-bg="banner2.jpg">
        <div class="container">
            <div class="box">
                <header>
                    <div class="image-container">
                        <img src="<?php echo 'images/profileImages/' . $_SESSION['picName'] . '?' . mt_rand(); ?>" class="img-circle img-responsive" height="200px" alt="Profile Picture">
                    </div>
                    <h2><?php echo $_SESSION['Name']; ?></h2>
                    <h4><?php echo $_SESSION['Username']; ?></h4>

                    <!-- Profile Picture Update Form -->
                    <form method="post" action="Profile/updatePic.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="profilePic">Change Profile Picture:</label>
                            <input type="file" name="profilePic" id="profilePic" class="form-control" />
                        </div>
                        <div class="form-group">
                            <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                            <button type="submit" name="remove" class="btn btn-danger">Remove</button>
                        </div>
                    </form>
                </header>

                <!-- Profile Update Form -->
                <form method="post" action="Profile/updateProfile.php">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Full Name:</label>
                                <input type="text" name="name" id="name" value="<?php echo $_SESSION['Name']; ?>" placeholder="Full Name" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mobile">Mobile No:</label>
                                <input type="text" name="mobile" id="mobile" value="<?php echo $_SESSION['Mobile']; ?>" placeholder="Mobile No" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="uname">Username:</label>
                                <input type="text" name="uname" id="uname" value="<?php echo $_SESSION['Username']; ?>" placeholder="Username" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" id="email" value="<?php echo $_SESSION['Email']; ?>" placeholder="Email" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea name="address" id="address" placeholder="Address" rows="4" class="form-control"><?php echo $_SESSION['Addr']; ?></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- JS Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
