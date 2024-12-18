<?php
    session_start();

    require '../db.php';

    // Check if the email and hash are provided for verification
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']))
    {
        $email = dataFilter($_GET['email']);
        $hash = dataFilter($_GET['hash']);

        // Query to check if the account is inactive
        $sql = "SELECT * FROM mango_sellers WHERE Email='$email' AND Hash='$hash' AND Active='0'";
        $result = mysqli_query($conn, $sql) or die($mysqli->error());

        if ($result->num_rows == 0)
        {
            $_SESSION['message'] = "Account has already been activated or the URL is invalid!";
            header("location: error.php");
        }
        else
        {
            $_SESSION['message'] = "Your account has been activated! Welcome to Farmfresh Mango!";
            
            // Update the account status to active
            $sql = "UPDATE mango_sellers SET Active='1' WHERE Email='$email'";
            $result = mysqli_query($conn, $sql) or die($mysqli->error());
            
            $_SESSION['Active'] = 1;

            // Redirect to success page
            header("location: success.php");
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid credentials provided for account verification!";
        header("location: error.php");
    }

    // Function to sanitize input data
    function dataFilter($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
