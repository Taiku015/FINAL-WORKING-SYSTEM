<?php
session_start();
require 'db.php';

// Ensure the farmer is logged in (check if 'id' exists in the session)
if (!isset($_SESSION['id'])) {
    $_SESSION['message'] = "You must be logged in to upload a product!";
    header("Location: login.php");
    exit;
}

// Function to sanitize input data
function dataFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if all necessary fields are set
    if (isset($_POST['type'], $_POST['pname'], $_POST['pinfo'], $_POST['price'])) {
        // Sanitize input data
        $productType = dataFilter($_POST['type']);
        $productName = dataFilter($_POST['pname']);
        $productInfo = $_POST['pinfo'];  // Comes from CKEditor
        $productPrice = dataFilter($_POST['price']);
        $fid = $_SESSION['id'];  // Farmer ID from session

        // Insert product details into the database
        $sql = "INSERT INTO fproduct (fid, product, pcat, pinfo, price)
                VALUES ('$fid', '$productName', '$productType', '$productInfo', '$productPrice')";

        $result = mysqli_query($conn, $sql);

        // Check if insertion is successful
        if (!$result) {
            $_SESSION['message'] = "Unable to upload Product!!!";
            header("Location: Login/error.php");
            exit;
        } else {
            $_SESSION['message'] = "Product added successfully!";
        }

        // Handle product image upload
        if (isset($_FILES['productPic']) && $_FILES['productPic']['error'] == 0) {
            $pic = $_FILES['productPic'];
            $picName = $pic['name'];
            $picTmpName = $pic['tmp_name'];
            $picSize = $pic['size'];
            $picError = $pic['error'];
            $picType = $pic['type'];
            $picExt = explode('.', $picName);
            $picActualExt = strtolower(end($picExt));

            // Allowed image types
            $allowed = array('jpg', 'jpeg', 'png');

            // Check if file extension is allowed
            if (in_array($picActualExt, $allowed)) {
                if ($picError === 0) {
                    // Generate a unique name for the image file
                    $picNameNew = $productName . $fid . "." . $picActualExt;
                    $_SESSION['productPicName'] = $picNameNew;
                    $_SESSION['productPicExt'] = $picActualExt;

                    // Define the upload directory
                    $picDestination = "images/productImages/" . $picNameNew;

                    // Move the uploaded file to the destination folder
                    if (move_uploaded_file($picTmpName, $picDestination)) {
                        // Update the product record with the image information
                        $sqlUpdate = "UPDATE fproduct SET picStatus=1, pimage='$picNameNew' WHERE product='$productName' AND fid='$fid'";

                        if (mysqli_query($conn, $sqlUpdate)) {
                            $_SESSION['message'] = "Product image uploaded successfully!";
                            header("Location: market.php");
                        } else {
                            $_SESSION['message'] = "There was an error updating the product image. Please try again!";
                            header("Location: Login/error.php");
                        }
                    } else {
                        $_SESSION['message'] = "There was an error in uploading your product image. Please try again!";
                        header("Location: Login/error.php");
                    }
                } else {
                    $_SESSION['message'] = "There was an error uploading your product image. Please try again!";
                    header("Location: Login/error.php");
                }
            } else {
                $_SESSION['message'] = "You cannot upload files with this extension. Only JPG, JPEG, and PNG are allowed.";
                header("Location: Login/error.php");
            }
        } else {
            $_SESSION['message'] = "No image file selected or there was an error uploading the image.";
            header("Location: Login/error.php");
        }
    } else {
        $_SESSION['message'] = "Please fill out all the product details.";
        header("Location: Login/error.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FarmFresh Mango</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
</head>
<body>
    <?php require 'menu.php'; ?>

    <!-- Product Upload Section -->
    <section id="one" class="wrapper style1 align-center">
        <div class="container">
            <form method="POST" action="uploadProduct.php" enctype="multipart/form-data">
                <h2>Enter Product Information</h2>
                <center>
                    <input type="file" name="productPic" accept=".jpg,.jpeg,.png" required />
                    <br /><br />
                </center>

                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" name="pname" id="pname" placeholder="Product Name" required style="background-color:white;color: black;" />
                    </div>
                    <div class="col-sm-6">
                        <select name="type" id="type" required style="background-color:white;color: black;">
                            <option value="Fruits" selected>Fruits</option>
                        </select>
                    </div>
                </div>

                <br>
                <div>
                    <textarea name="pinfo" id="pinfo" rows="6" placeholder="Enter product information here..." required></textarea>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" name="price" id="price" placeholder="Price (in Ksh)" required style="background-color:white;color: black;" />
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="quantity" id="quantity" placeholder="Quantity (in kg)" required style="background-color:white;color: black;" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-success btn-block">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        CKEDITOR.replace('pinfo');
    </script>
</body>
</html>
