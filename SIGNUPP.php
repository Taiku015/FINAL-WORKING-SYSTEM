<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = dataFilter($_POST['name']);
    $mobile = dataFilter($_POST['mobile']);
    $user = dataFilter($_POST['uname']);
    $email = dataFilter($_POST['email']);
    
    // Check if password is set, otherwise set it to an empty string
    $pass = isset($_POST['pass']) ? dataFilter(password_hash($_POST['pass'], PASSWORD_BCRYPT)) : '';
    $hash = dataFilter(md5(rand(0, 1000)));
    $category = dataFilter($_POST['category']);
    $addr = dataFilter($_POST['addr']);

    // Store session variables
    $_SESSION['Email'] = $email;
    $_SESSION['Name'] = $name;
    $_SESSION['Password'] = $pass;
    $_SESSION['Username'] = $user;
    $_SESSION['Mobile'] = $mobile;
    $_SESSION['Category'] = $category;
    $_SESSION['Hash'] = $hash;
    $_SESSION['Addr'] = $addr;
    $_SESSION['Rating'] = 0;
}

require '../db.php';

// Check if mobile number is valid (10 digits)
$length = strlen($mobile);
if ($length != 10) {
    $_SESSION['message'] = "Invalid Mobile Number !!!";
    header("location: error.php");
    die();
}

if ($category == 1) {
    // Check if the email already exists in the farmer table
    $sql = "SELECT * FROM farmer WHERE femail='$email'";
    $result = mysqli_query($conn, $sql) or die($mysqli->error());

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "User with this email already exists!";
        header("location: error.php");
    } else {
        // Insert new farmer into the database (removed fhash column)
        $sql = "INSERT INTO farmer (fname, fusername, fpassword, fmobile, femail, faddress)
                VALUES ('$name','$user','$pass','$mobile','$email','$addr')";

        if (mysqli_query($conn, $sql)) {
            // Set session variables for logged-in user
            $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;
            $_SESSION['picStatus'] = 0;
            $_SESSION['picExt'] = "png";

            // Get user ID after insertion
            $sql = "SELECT * FROM farmer WHERE fusername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = $result->fetch_assoc();
            $_SESSION['id'] = $User['fid'];

            // Set default profile picture
            if ($_SESSION['picStatus'] == 0) {
                $_SESSION['picId'] = 0;
                $_SESSION['picName'] = "profile0.png";
            } else {
                $_SESSION['picId'] = $_SESSION['id'];
                $_SESSION['picName'] = "profile".$_SESSION['picId'].".".$_SESSION['picExt'];
            }

            // Send confirmation message to email
            $_SESSION['message'] = "Confirmation link has been sent to $email, please verify your account by clicking on the link in the message!";
            $to = $email;
            $subject = "Account Verification (Farmfresh Mango)";
            $message_body = "
            Hello $user,

            Thank you for signing up!

            Please click this link to activate your account:

            http://localhost/AgroCulture/Login/verify.php?email=".$email."&hash=".$hash;

            // Uncomment to send email
            //$check = mail($to, $subject, $message_body);

            header("location: profile.php");
        } else {
            $_SESSION['message'] = "Registration failed!";
            header("location: error.php");
        }
    }
} else {
    // Similar logic for buyers (not fully included here)
}

function dataFilter($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
