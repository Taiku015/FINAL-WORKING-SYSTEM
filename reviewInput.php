<?php
    session_start();
    require 'db.php';

    if (!isset($_POST['rating'], $_POST['comment'], $_SESSION['Name'])) {
        die("Invalid input. Please ensure all fields are filled out.");
    }

    $rating = intval($_POST['rating']); 
    $review = mysqli_real_escape_string($conn, trim($_POST['comment']));
    $name = mysqli_real_escape_string($conn, $_SESSION['Name']);
    $pid = intval($_GET['pid']); 
    if ($rating < 0 || $rating > 10) {
        die("Rating must be between 0 and 10.");
    }

    $sql = "INSERT INTO review (pid, name, rating, comment) VALUES ('$pid', '$name', '$rating', '$review')";

    if (mysqli_query($conn, $sql)) {
        header("Location: product.php?pid=" . $pid);
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
?> 