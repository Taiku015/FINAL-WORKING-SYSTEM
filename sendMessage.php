<?php
session_start();
require 'db.php';

// Ensure that product ID (pid) and farmer ID (fid) are provided
if (!isset($_GET['pid']) || !isset($_GET['fid'])) {
    die("Invalid Product ID or Farmer ID.");
}

$pid = intval($_GET['pid']);
$fid = intval($_GET['fid']);

// Fetch product details (optional, if you want to display product info here)
$productQuery = $conn->prepare("SELECT * FROM fproduct WHERE pid = ?");
$productQuery->bind_param("i", $pid);  // Correctly binding integer for pid
$productQuery->execute();
$productResult = $productQuery->get_result();

// Check if product exists
if ($productResult->num_rows === 0) {
    die("Product not found.");
}

$product = $productResult->fetch_assoc(); // Now this will be safely fetched

// Fetch farmer details
$farmerQuery = $conn->prepare("SELECT * FROM farmer WHERE fid = ?");
$farmerQuery->bind_param("i", $fid);  // Correctly binding integer for fid
$farmerQuery->execute();
$farmerResult = $farmerQuery->get_result();

// Check if farmer exists
if ($farmerResult->num_rows === 0) {
    die("Farmer not found.");
}

$farmer = $farmerResult->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $senderName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';  // Get user name or "Guest"
    $senderId = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // If user is logged in, use their ID
    $receiverId = $fid;
    $message = htmlspecialchars(trim($_POST['message']));
    $timestamp = date("Y-m-d H:i:s");

    // Insert message into the database
    $messageQuery = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message, date_sent, sender_name) VALUES (?, ?, ?, ?, ?)");
    $messageQuery->bind_param("iisss", $senderId, $receiverId, $message, $timestamp, $senderName); // Binding parameters correctly
    if (!$messageQuery->execute()) {
        echo "Error: " . $messageQuery->error;
    }
}

// Fetch previous messages
$senderId = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
$messagesQuery = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY date_sent DESC");
$messagesQuery->bind_param("iiii", $senderId, $fid, $fid, $senderId);  // Correctly binding integers for sender and receiver IDs
$messagesQuery->execute();
$messagesResult = $messagesQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango: Message Seller</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <h2>Message Seller</h2>

            <div class="row">
                <div class="col-12">
                    <h3>Product: <?= htmlspecialchars($product['product']) ?></h3>
                    <p><strong>Seller:</strong> <?= htmlspecialchars($farmer['fname']) ?></p>
                    <p><strong>Price:</strong> <?= htmlspecialchars($product['price']) ?> Ksh</p>
                </div>
            </div>

            <!-- Message Form -->
            <form method="POST">
                <div class="form-group">
                    <textarea class="form-control" name="message" rows="3" placeholder="Write your message..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Send Message</button>
            </form>

            <hr />

            <!-- Message History -->
            <h3>Message History</h3>
            <div class="message-list" style="max-height: 400px; overflow-y: scroll;">
                <?php
                if ($messagesResult->num_rows > 0) :
                    while ($message = $messagesResult->fetch_assoc()) :
                        $isSender = $message['sender_id'] == $senderId;
                ?>
                        <div class="message <?= $isSender ? 'sender-message' : 'receiver-message' ?>" style="border-bottom: 1px solid #ccc; padding: 10px;">
                            <strong><?= $isSender ? 'You' : htmlspecialchars($message['sender_name']) ?>:</strong>
                            <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($message['date_sent']) ?></small>
                        </div>
                <?php endwhile;
                else : ?>
                    <p>No messages yet. Start a conversation!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
