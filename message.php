<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = 'localhost';
$db = 'farmfresh_mango';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission logic
if (isset($_POST['send_message'])) {
    $sender_id = $_POST['sender_id'];
    $recipient_id = $_POST['recipient_id'];
    $message = $_POST['message'];
    $message_type = $_POST['message_type'];

    // Check if fields are filled
    if (!empty($sender_id) && !empty($recipient_id) && !empty($message) && !empty($message_type)) {
        // Prepare the SQL query to insert message
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message, message_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $sender_id, $recipient_id, $message, $message_type);
        
        if ($stmt->execute()) {
            $success_message = "Message sent successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Mango - Messaging</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        #app {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
        }

        h1, h2 {
            text-align: center;
            color: #3b3b3b;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        form label {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #555;
        }

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            background-color: #f1f1f1;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .message strong {
            display: block;
            color: #2f7c2f;
            font-weight: 500;
        }

        .message em {
            font-size: 0.9rem;
            color: #999;
        }

        .message p {
            margin: 5px 0;
        }

        .success-message, .error-message {
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }

        .messages {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 30px;
        }

        footer {
            text-align: center;
            padding: 0;
            background-color: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>FarmFresh Mango Group Messaging</h1>

        <!-- Show success or error message -->
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?= $success_message ?></p>
        <?php elseif (isset($error_message)): ?>
            <p class="error-message"><?= $error_message ?></p>
        <?php endif; ?>

        <!-- Messaging Form -->
        <form id="messageForm" method="POST" action="">
            <label for="sender_id">Your ID (User):</label>
            <input type="number" id="sender_id" name="sender_id" placeholder="Enter your ID" required>

            <label for="recipient_id">Recipient ID (User):</label>
            <input type="number" id="recipient_id" name="recipient_id" placeholder="Enter recipient ID" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" placeholder="Type your message here..." required></textarea>

            <label for="message_type">You are:</label>
            <select id="message_type" name="message_type" required>
                <option value="buyer">Buyer</option>
                <option value="farmer">Farmer</option>
            </select>

            <button type="submit" name="send_message">Send Message</button>
        </form>

        <h2>Message History</h2>

        <!-- Display messages -->
        <div class="messages">
            <?php
                // Fetch and display messages
                $sql = "SELECT * FROM messages ORDER BY date_sent ASC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='message'>";
                    echo "<strong>{$row['sender_id']} ({$row['message_type']})</strong>";
                    echo "<p>{$row['message']}</p>";
                    echo "<em>Sent on: {$row['date_sent']}</em>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FarmFresh Mango. For Greater Future.</p>
    </footer>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
