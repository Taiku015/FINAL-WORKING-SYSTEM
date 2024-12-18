<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FarmFresh Mango</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="login.css"/>
    <link rel="stylesheet" href="indexfooter.css" />
    <style>
        /* Custom Styles */
        .modal-content {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background-color:#d9d7d6;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .close {
            cursor: pointer;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .wrapper {
            padding: 50px 0;
            background-color: hsla(50, 33%, 25%, .75);
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .banner-btns .col-6 {
            padding: 10px;
        }
        .btn-block {
            padding: 15px 25px;
            font-size: 18px;
            border-radius: 8px;
        }
        /* Middle Section Style */
        #banner {
            background-color: hsla(50, 33%, 25%, .75);
            color: white;
            padding: 80px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        #banner h2 {
            font-size: 36px;
            font-weight: bold;
        }
        #banner p {
            font-size: 20px;
        }
        #banner .row {
            margin-top: 30px;
        }
        #one {
            background-color: #f8f9fa;
            padding: 40px 20px;
            border-radius: 8px;
        }
        #one header h2 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        #one p {
            font-size: 18px;
            color: #555;
        }
        .footer-distributed {
            background-color: #f5f5c1;
            color: black;
            padding: 40px 20px;
            margin-top: 40px;
            border-radius: 8px;
        }
        .footer-distributed h1 {
            font-size: 28px;
        }
        .footer-distributed p {
            font-size: 16px;
            line-height: 1.6;
        }
        .footer-distributed a {
            color: #007bff;
            text-decoration: none;
        }
        .footer-distributed a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php require 'menu.php'; ?>

    <!-- Banner -->
    <section id="banner" class="wrapper">
        <div class="container text-center">
            <h2>FarmFresh Mango</h2>
            <p>Your Product, Our Market</p>
            <br><br>
            <div class="row justify-content-center banner-btns">
                <div class="col-6 col-sm-4">
                    <button class="btn btn-primary btn-block" onclick="openModal('id01')">LOGIN</button>
                </div>
                <div class="col-6 col-sm-4">
                    <button class="btn btn-secondary btn-block" onclick="openModal('id02')">REGISTER</button>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="one" class="wrapper style1 align-center">
        <div class="container">
            <header>
                <h2>FarmFresh Mango</h2>
            </header>
            <div class="row">
                <div class="col-md-4">
                    <p>Explore the new way of marketing</p>
                </div>
                <div class="col-md-4">
                    <p>Build Your Future With Us</p>
                </div>
                <div class="col-md-4">
                    <p>Register with us</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-distributed text-center">
        <h1>About Us</h1>
        <p>FarmFresh Mango is an e-commerce trading platform for fresh mango fruits.</p>
        <p>FarmFresh Mango Culture Farm, Makueni</p>
        <p>Phone: 0794385151</p>
        <p>Email: <a href="mailto:farmfreshmango@gmail.com">farmfreshmango@gmail.com</a></p>
    </footer>

    <!-- Login Modal -->
    <div id="id01" class="modal">
        <div class="modal-content animate">
            <span class="close" onclick="closeModal('id01')">&times;</span>
            <h3>Login</h3>
            <form action="Login/login.php" method="POST">
                <div class="form-group">
                    <label for="uname">Username</label>
                    <input type="text" name="uname" id="uname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" name="pass" id="pass" class="form-control" required>
                </div>
                <div class="form-check">
                    <input type="radio" id="farmer" name="category" value="1" class="form-check-input" checked>
                    <label for="farmer" class="form-check-label">Farmer</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="buyer" name="category" value="0" class="form-check-input">
                    <label for="buyer" class="form-check-label">Buyer</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="id02" class="modal">
        <div class="modal-content animate">
            <span class="close" onclick="closeModal('id02')">&times;</span>
            <h3>Sign Up</h3>
            <form action="Login/signUp.php" method="POST">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="uname">Username</label>
                    <input type="text" name="uname" id="uname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="addr">Address</label>
                    <input type="text" name="addr" id="addr" class="form-control" required>
                </div>
                <div class="form-check">
                    <input type="radio" id="farmerReg" name="category" value="1" class="form-check-input" checked>
                    <label for="farmerReg" class="form-check-label">Farmer</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="buyerReg" name="category" value="0" class="form-check-input">
                    <label for="buyerReg" class="form-check-label">Buyer</label>
                </div>
                <button type="submit" class="btn btn-success btn-block">Register</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onclick = function (event) {
            const modals = ['id01', 'id02'];
            modals.forEach((modalId) => {
                if (event.target === document.getElementById(modalId)) {
                    closeModal(modalId);
                }
            });
        }
    </script>
</body>
</html>
