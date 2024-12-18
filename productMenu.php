<?php
session_start();
require 'db.php';

// Simulating roles for now (in a real system, fetch roles from the database after login)
$_SESSION['role'] = 'Buyer'; // or 'Farmer' for testing purposes.

$role = $_SESSION['role'] ?? 'Guest';

// Handling the search query
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FarmFresh Mango</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
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
        }
        .title {
            color: #ff9800;
            font-weight: bold;
        }
        .btn-search {
            background-color: #ff9800;
            color: white;
            border: none;
        }
        .btn-search:hover {
            background-color: #e68900;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <?php require 'menu.php'; ?>

    <section id="main" class="wrapper style1 align-center">
        <div class="container">
            <h2>Welcome to the FarmFresh Mango Digital Market</h2>
            <p>Find the freshest and highest-quality mango products here!</p>

            <!-- Search Form -->
            <form method="GET" action="" class="d-flex justify-content-center mt-4 mb-4">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control w-50" 
                    placeholder="Search for products..." 
                    value="<?php echo htmlspecialchars($searchQuery); ?>" 
                />
                <button type="submit" class="btn btn-search ms-2">Search</button>
            </form>
        </div>

        <section id="two" class="wrapper style2 align-center">
            <div class="container">
                <div class="row">
                    <?php
                    // Fetching products based on the search query or showing all
                    $sql = "SELECT * FROM fproduct WHERE pcat = 'Fruits'";
                    if (!empty($searchQuery)) {
                        $sql .= " AND product LIKE '%$searchQuery%'";
                    }
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0):
                        while ($row = $result->fetch_array()):
                            $picDestination = "images/productImages/" . $row['pimage'];
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($picDestination); ?>" class="card-img-top" alt="Product Image" height="220px">
                                <div class="card-body">
                                    <h5 class="title"><?php echo htmlspecialchars($row['product']); ?></h5>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['pcat']); ?></p>
                                    <p><strong>Price:</strong> <?php echo htmlspecialchars($row['price']) . " /-"; ?></p>
                                    <!-- Unified Features -->
                                    <a href="review.php?pid=<?php echo $row['pid']; ?>" class="btn btn-primary btn-sm">View Details</a>
                                    <a href="add_to_cart.php?pid=<?php echo $row['pid']; ?>" class="btn btn-warning btn-sm">Add to Cart</a>
                                </div>
                            </div>
                        </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <div class="alert alert-warning" role="alert">
                            No products found matching your search. Try again with a different keyword!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </section>

</body>
</html>
