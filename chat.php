<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1;
$user_id = $is_logged_in ? $_SESSION['user_id'] : null; // If logged in, get user ID
$user_type = $is_logged_in ? $_SESSION['user_type'] : 'guest'; // If logged in, get user type, else 'guest'

// If the user is a guest, assign a temporary guest ID (could be a random string or just "guest")
if (!$is_logged_in) {
    // Generate a random guest ID (You can replace this with a more sophisticated method)
    $user_id = 'guest_' . uniqid();
    $user_type = 'guest';
}

// Get the receiver's ID from the URL or POST data
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : $_POST['receiver_id']; 

// Connect to the database
include('dbh.php'); 

// Handle message sending (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);  // Get the message from the form
    $message_type = $user_type == 'buyer' ? 'buyer' : ($user_type == 'farmer' ? 'farmer' : 'guest');  // Set message type based on user type

    // Insert the message into the database
    $sql = "INSERT INTO messages (sender_id, recipient_id, message, message_type) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $receiver_id, $message, $message_type]);

    // Redirect back to the chat page after sending the message
    header("Location: chat.php?receiver_id=" . $receiver_id);
    exit;
}

// Fetch messages between the logged-in user/guest and the receiver
$sql = "SELECT * FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY date_sent ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $receiver_id, $receiver_id, $user_id]);
$messages = $stmt->fetchAll();

// Fetch receiver's info
$sql_receiver = "SELECT username, name FROM " . ($user_type == 'buyer' ? 'farmer' : ($user_type == 'guest' ? 'guest_users' : 'buyer')) . " WHERE id = ?";
$stmt_receiver = $pdo->prepare($sql_receiver);
$stmt_receiver->execute([$receiver_id]);
$receiver = $stmt_receiver->fetch();
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Chat with <?php echo htmlspecialchars($receiver['name']); ?></title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <?php include 'menu.php'; ?>

    <!-- Chat Section -->
    <div class="container mt-4">
        <h2>Chat with <?php echo htmlspecialchars($receiver['name']); ?></h2>
        <div class="chat-box">
            <!-- Display chat messages -->
            <div class="messages">
                <?php foreach ($messages as $message) : ?>
                    <div class="message <?php echo $message['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                        <p><strong><?php echo $message['sender_id'] == $user_id ? 'You' : $receiver['name']; ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                        <small><?php echo $message['date_sent']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Message input form -->
            <form action="chat.php" method="post" class="message-form">
                <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                <textarea name="message" rows="3" class="form-control" placeholder="Type your message here..." required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Send Message</button>
            </form>
        </div>
    </div>

    <script>
        // Scroll to the bottom of the chat on load
        $(document).ready(function() {
            $('.messages').scrollTop($('.messages')[0].scrollHeight);
        });
    </script>
</body>

</html>


<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    header("Location: Login/error.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$receiver_id = $_POST['receiver_id']; // Get the receiver's ID from the form
$message = trim($_POST['message']); // Get the message from the form

// Connect to the database
include('dbh.php');

// Insert the message into the database
$sql = "INSERT INTO messages (sender_id, recipient_id, message, message_type) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $receiver_id, $message, 'buyer']);

// Redirect back to the chat page
header("Location: chat.php?receiver_id=" . $receiver_id);
exit;
?>
