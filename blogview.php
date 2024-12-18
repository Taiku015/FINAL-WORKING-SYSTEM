<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "You need to log in to access this page!";
    header("Location: Login/error.php");
    exit();
}

// Handle new blog submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['blogTitle']) && !empty($_POST['blogContent'])) {
    $blogTitle = htmlspecialchars(trim($_POST['blogTitle']));
    $blogContent = htmlspecialchars(trim($_POST['blogContent']));
    $blogUser = $_SESSION['Username'];
    $blogTime = date("Y-m-d H:i:s");

    $sql = "INSERT INTO blogdata (blogTitle, blogContent, blogUser, blogTime, likes)
            VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $blogTitle, $blogContent, $blogUser, $blogTime);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle likes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['likeBlogId'])) {
    $blogId = intval($_POST['likeBlogId']);
    $sql = "UPDATE blogdata SET likes = likes + 1 WHERE blogId = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch blogs
$sql = "SELECT * FROM blogdata ORDER BY blogId DESC";
$blogs = $conn->query($sql);

// Fetch comments count for each blog
function fetchCommentsCount($conn, $blogId)
{
    $sql = "SELECT COUNT(*) as count FROM blogfeedback WHERE blogId = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    } else {
        return 0;
    }
}

// Function to format date for JavaScript
function formatDateForJS($date)
{
    return date('c', strtotime($date)); // ISO 8601 format for JS
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FarmFresh Mango: Blogs</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        // Function to update timestamps in real time
        function updateTimestamps() {
            const elements = document.querySelectorAll('.timestamp');
            elements.forEach((el) => {
                const date = new Date(el.getAttribute('data-timestamp'));
                const now = new Date();
                const diff = Math.floor((now - date) / 1000);  // Time difference in seconds

                let displayTime;
                if (diff < 60) {
                    displayTime = diff + " seconds ago";
                } else if (diff < 3600) {
                    displayTime = Math.floor(diff / 60) + " minutes ago";
                } else if (diff < 86400) {
                    displayTime = Math.floor(diff / 3600) + " hours ago";
                } else {
                    displayTime = Math.floor(diff / 86400) + " days ago";
                }
                el.textContent = displayTime;
            });
        }

        // Update timestamps every 10 seconds
        setInterval(updateTimestamps, 10000);

        // Update timestamps on page load
        document.addEventListener('DOMContentLoaded', updateTimestamps);
    </script>
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .box h3 {
            color: #ff9800;
            font-weight: bold;
        }
        .timestamp {
            color: #777;
            font-style: italic;
        }
        .create-blog {
            background-color: #ff9800;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .btn-like {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-like:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <section id="main" class="wrapper">
        <div class="container">
            <header>
                <h2>FarmFresh Mango Blogs</h2>
                <p>Read and share your thoughts on mango-related topics!</p>
            </header>

            <!-- Blog Creation Form -->
            <div class="create-blog">
                <h4>Create a New Blog</h4>
                <form method="post">
                    <input type="text" name="blogTitle" class="form-control" placeholder="Blog Title" required>
                    <textarea name="blogContent" class="form-control" rows="6" placeholder="Write your blog here..." required></textarea>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="Announcements">Announcements</option>
                        <option value="General">General</option>
                        <option value="Educational">Educational</option>
                        <option value="Health Benefits">Health Benefits</option>
                        <option value="Fun Facts">Fun Facts</option>
                    </select>
                    <button type="submit" class="btn btn-warning">Submit Blog</button>
                </form>
            </div>

            <!-- Display Blogs -->
            <?php if ($blogs->num_rows > 0): ?>
                <?php while ($row = $blogs->fetch_assoc()) : ?>
                    <div class="box">
                        <h3><?= htmlspecialchars($row['blogTitle']); ?></h3>
                        <blockquote><?= nl2br(htmlspecialchars($row['blogContent'])); ?></blockquote>
                        <p>
                            <b><?= htmlspecialchars($row['blogUser']); ?></b> — 
                            <span class="timestamp" data-timestamp="<?= formatDateForJS($row['blogTime']); ?>">
                                <?= date('g:i a, M j', strtotime($row['blogTime'])); ?>
                            </span>
                        </p>

                        <!-- Like Button -->
                        <form method="post" style="display: inline-block; margin-right: 15px;">
                            <input type="hidden" name="likeBlogId" value="<?= htmlspecialchars($row['blogId']); ?>">
                            <button type="submit" class="btn-like">👍 Like</button>
                            <span><?= htmlspecialchars($row['likes']); ?> Likes</span>
                        </form>
                        <p class="comments-count"><?= fetchCommentsCount($conn, $row['blogId']); ?> Comments</p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No blogs available yet. Be the first to write one!</p>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>
