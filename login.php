<?php
session_start();

// Function to sanitize user input
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Retrieve POST data
$username = sanitizeInput($_POST['uname']);
$password = $_POST['pass'];
$category = intval(sanitizeInput($_POST['category'])); // Ensure $category is an integer

require '../db.php'; // Include database connection

// Validate category value
if (!in_array($category, [0, 1])) {
    $_SESSION['message'] = "Invalid category selection!";
    header("location: error.php");
    exit;
}

// Set table and column names based on user category
if ($category == 1) {
    $table = "farmer";
    $usernameColumn = "fusername";
    $passwordColumn = "fpassword";
} else {
    $table = "buyer";
    $usernameColumn = "busername";
    $passwordColumn = "bpassword";
}

// Prepare and execute the SQL query to fetch user data
$sql = "SELECT * FROM $table WHERE $usernameColumn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists in the database
if ($result->num_rows === 0) {
    $_SESSION['message'] = "Invalid username or password!";
    header("location: error.php");
    exit;
}

// Fetch user data
$user = $result->fetch_assoc();

// Verify the password entered by the user
if (password_verify($password, $user[$passwordColumn])) {
    // Set session variables based on category
    if ($category == 1) { // Farmer
        $_SESSION['id'] = $user['fid'];
        $_SESSION['Hash'] = $user['fhash'];
        $_SESSION['Password'] = $user['fpassword'];
        $_SESSION['Email'] = $user['femail'];
        $_SESSION['Name'] = $user['fname'];  // Name for farmer
        $_SESSION['Username'] = $user['fusername'];
        $_SESSION['Mobile'] = $user['fmobile'];
        $_SESSION['Addr'] = $user['faddress'];
        $_SESSION['Active'] = $user['factive'];
        $_SESSION['picStatus'] = $user['picStatus'];
        $_SESSION['picExt'] = $user['picExt'];
        $_SESSION['Category'] = 1;
        $_SESSION['Rating'] = 0;

        // Profile picture handling
        if ($_SESSION['picStatus'] == 0) {
            $_SESSION['picId'] = 0;
            $_SESSION['picName'] = "profile0.png";
        } else {
            $_SESSION['picId'] = $_SESSION['id'];
            $_SESSION['picName'] = "profile" . $_SESSION['picId'] . "." . $_SESSION['picExt'];
        }
    } else { // Buyer
        $_SESSION['id'] = $user['bid'];
        $_SESSION['Hash'] = $user['bhash'];
        $_SESSION['Password'] = $user['bpassword'];
        $_SESSION['Email'] = $user['bemail'];
        $_SESSION['Name'] = $user['bname'];  // Name for buyer
        $_SESSION['Username'] = $user['busername'];
        $_SESSION['Mobile'] = $user['bmobile'];
        $_SESSION['Addr'] = $user['baddress'];
        $_SESSION['Active'] = $user['bactive'];
        $_SESSION['Category'] = 0;
    }

    // Mark user as logged in
    $_SESSION['logged_in'] = true;

    // Redirect to profile page
    header("location: profile.php");
} else {
    $_SESSION['message'] = "Invalid username or password!";
    header("location: error.php");
}

// Close database connections
$stmt->close();
$conn->close();
?>
