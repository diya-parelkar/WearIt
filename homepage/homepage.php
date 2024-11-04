<?php
// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "fashion_website_wpl";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product data
$sql = "SELECT id, description, price, image, category FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="../Navbar/navbar.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include '../Navbar/navbar.php'; ?>
    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while($product = $result->fetch_assoc()) {
                ?>
                <div class="product-card">
                    <img src="../Seller/Images/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['description']); ?></h3>
                        <p>Price: ₹<?php echo htmlspecialchars($product['price']); ?></p>
                        <a href="product_details/product_details.php?id=<?php echo $product['id']; ?>" class="view-details">View Details</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
