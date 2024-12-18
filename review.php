<?php
session_start();
require 'db.php';

// Ensure the session variable 'role' is set correctly (0 for buyer, 1 for farmer)
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null; // Default to null if not set

if (!isset($_GET['pid']) || empty($_GET['pid'])) {
    die("Invalid Product ID.");
}

$pid = intval($_GET['pid']);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $rating = intval($_POST['rating']);
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Anonymous'; // Default to 'Anonymous' if not logged in

    // Insert review into database
    $reviewQuery = $conn->prepare("INSERT INTO review (pid, comment, rating, name) VALUES (?, ?, ?, ?)");
    $reviewQuery->bind_param("isis", $pid, $comment, $rating, $name);

    if ($reviewQuery->execute()) {
        $successMessage = "Review submitted successfully!";
    } else {
        $errorMessage = "Failed to submit review. Please try again.";
    }
}

// Fetch product details
$productQuery = $conn->prepare("SELECT * FROM fproduct WHERE pid = ?");
$productQuery->bind_param("i", $pid);
$productQuery->execute();
$productResult = $productQuery->get_result();

if ($productResult->num_rows === 0) {
    die("Product not found.");
}

$product = $productResult->fetch_assoc();

// Fetch farmer details
$farmerQuery = $conn->prepare("SELECT * FROM farmer WHERE fid = ?");
$farmerQuery->bind_param("i", $product['fid']);
$farmerQuery->execute();
$farmerResult = $farmerQuery->get_result();

if ($farmerResult->num_rows === 0) {
    die("Farmer not found.");
}

$farmer = $farmerResult->fetch_assoc();
$picDestination = "images/productImages/" . htmlspecialchars($product['pimage']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Product</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="Blog/commentBox.css" />
</head>

<body>
    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <img class="image fit" src="<?= $picDestination ?>" alt="<?= htmlspecialchars($product['product']) ?>" />
                </div>
                <div class="col-12 col-sm-6">
                    <p style="font-size: 2rem; font-weight: bold;"><?= htmlspecialchars($product['product']) ?></p>
                    <p style="font-size: 1.5rem;">Product Owner: <?= htmlspecialchars($farmer['fname']) ?></p>
                    <p style="font-size: 1.5rem;">Address: <?= htmlspecialchars($farmer['faddress']) ?></p>
                    <p style="font-size: 1.5rem;">Price: <?= htmlspecialchars($product['price']) ?> Ksh For 1Kg</p>
                    <p style="font-size: 1.5rem;">Quantity Available: <?= htmlspecialchars($product['quantity']) ?> Kg</p>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-12" style="font-size: 1.2rem;">
                    <?= nl2br(htmlspecialchars($product['pinfo'])) ?>
                </div>
            </div>
        </div>

        <br /><br />

        <!-- Buy Now Section -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-4 mb-3">
                    <?php if ($role !== 1): // Only disable for farmers (role = 1) ?>
                        <a href="buyNow.php?pid=<?= $pid ?>" class="btn btn-success btn-lg w-100">Buy Now</a>
                    <?php else: ?>
                        <button class="btn btn-success w-100" disabled>Buy Now</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="container">
            <h2>Product Reviews</h2>
            <div class="row">
                <?php
                $reviewQuery = $conn->prepare("SELECT * FROM review WHERE pid = ?");
                $reviewQuery->bind_param("i", $pid);
                $reviewQuery->execute();
                $reviewsResult = $reviewQuery->get_result();

                if ($reviewsResult->num_rows > 0) :
                    while ($review = $reviewsResult->fetch_assoc()) :
                ?>
                        <div class="col-12 mb-3">
                            <div class="review-box p-3" style="border: 1px solid #ddd; border-radius: 10px;">
                                <em><?= htmlspecialchars($review['comment']) ?></em>
                                <br />
                                <strong>Rating: <?= htmlspecialchars($review['rating']) ?> / 10</strong>
                                <br />
                                <small class="text-muted">From: <?= htmlspecialchars($review['name']) ?></small>
                            </div>
                        </div>
                    <?php endwhile;
                else : ?>
                    <p>No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Review Input Section -->
        <div class="container">
            <p style="font-size: 1.2rem;">Rate this product:</p>
            <?php if (isset($successMessage)) : ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
            <?php elseif (isset($errorMessage)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="row">
                    <div class="col-sm-7">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Write a review" required></textarea>
                    </div>
                    <div class="col-sm-5">
                        <br />
                        <label for="rating">Rating:</label>
                        <input type="number" class="form-control" name="rating" min="1" max="10" value="5" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <br />
                        <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
