<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ - FarmFresh Mango</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            padding: 20px;
        }
        .faq-section {
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .faq-header {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .faq-body {
            margin-top: 5px;
            color: #555;
        }
        .faq-title {
            cursor: pointer;
            font-weight: bold;
        }
        .faq-title:hover {
            color: #007bff;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Include Menu -->
<?php include 'menu.php'; ?>

<div class="container">
    <h2 class="text-center mb-4">Frequently Asked Questions</h2>

    <!-- FAQ 1 -->
    <div class="faq-section">
        <div class="faq-title" onclick="toggleFaq(1)">
            <span class="glyphicon glyphicon-plus"></span> What is FarmFresh Mango?
        </div>
        <div class="faq-body" id="faq-1" style="display: none;">
            FarmFresh Mango is an e-commerce platform dedicated to connecting mango farmers and buyers through a digital marketplace.
        </div>
    </div>

    <!-- FAQ 2 -->
    <div class="faq-section">
        <div class="faq-title" onclick="toggleFaq(2)">
            <span class="glyphicon glyphicon-plus"></span> How can I register as a farmer or buyer?
        </div>
        <div class="faq-body" id="faq-2" style="display: none;">
            Click the "Register" button on the homepage, fill out the form with your details, and select your category as either Farmer or Buyer.
        </div>
    </div>

    <!-- FAQ 3 -->
    <div class="faq-section">
        <div class="faq-title" onclick="toggleFaq(3)">
            <span class="glyphicon glyphicon-plus"></span> Is FarmFresh Mango free to use?
        </div>
        <div class="faq-body" id="faq-3" style="display: none;">
            Yes, registration and using the platform are free. However, transaction fees may apply based on the agreements between buyers and sellers.
        </div>
    </div>

    <!-- FAQ 4 -->
    <div class="faq-section">
        <div class="faq-title" onclick="toggleFaq(4)">
            <span class="glyphicon glyphicon-plus"></span> How can I contact support?
        </div>
        <div class="faq-body" id="faq-4" style="display: none;">
            You can contact us via email at <a href="mailto:fresh@mango.com">farmfreshmango@gmail.com</a> or call us at 0794385151.
        </div>
    </div>

</div>

<!-- Footer -->
<footer>
    <p>&copy; <?= date('Y'); ?> FarmFresh Mango. For Greater Future.</p>
</footer>

<script>
    function toggleFaq(id) {
        const faqBody = document.getElementById(`faq-${id}`);
        faqBody.style.display = faqBody.style.display === "none" ? "block" : "none";
    }
</script>

</body>
</html>
