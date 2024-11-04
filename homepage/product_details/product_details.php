<?php
session_start(); // Start session for cart and wishlist

$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "fashion_website_wpl";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is present in the URL and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details based on product ID
    $result = $conn->query("SELECT * FROM product WHERE id = $product_id");

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Define sizes and their corresponding quantity fields
        $sizes = ['S', 'M', 'L', 'XL'];
        $size_quantities = [
            'S' => $product['S_quantity'] ?? 0,
            'M' => $product['M_quantity'] ?? 0,
            'L' => $product['L_quantity'] ?? 0,
            'XL' => $product['XL_quantity'] ?? 0
        ];
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Invalid product ID.";
    exit();
}

// Initialize cart and wishlist in session if not set
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['description']); ?></title>
    <link rel="stylesheet" href="product_details.css">
    <link rel="stylesheet" href="../../Navbar/navbar.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include '../../Navbar/navbar.php'; ?>
    <div class="container">
        <div class="product-detail-container">
            <div class="product-image">
                <img src="../../Seller/Images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['description']); ?>">
            </div>
            <div class="product-info">
                <p class='title'><?php echo htmlspecialchars($product['description']); ?></p>
                <h3 class="product-price">Price: <span>₹<?php echo htmlspecialchars($product['price']); ?></span></h3>
                <p class="seller-name"><strong>Seller:</strong> <?php echo htmlspecialchars($product['seller_name']); ?></p>

                <!-- Available Colors Swatches -->
                <div class="available-colors">
                    <strong>Available Colors:</strong>
                    <div class="color-swatches">
                        <?php
                        // Assuming colors are stored as JSON in the database
                        $colors = json_decode($product['colors'], true); // true for associative array
                        
                        // Check if json_decode was successful
                        if (json_last_error() === JSON_ERROR_NONE && is_array($colors)) {
                            foreach ($colors as $color) {
                                echo "<span class='color-swatch' style='background-color: " . htmlspecialchars(trim($color)) . ";' title='" . htmlspecialchars(trim($color)) . "' onclick='selectColor(this)'></span>";
                            }
                        } else {
                            echo "<span class='color-swatch' style='background-color: gray;' title='Invalid colors data.'></span>"; // Handle invalid JSON format
                        }
                        ?>
                    </div>
                </div>

                <!-- Size Options -->
                <div class="size-options">
                    <strong>Select Size:</strong>
                    <div class="size-circles">
                        <?php
                        foreach ($sizes as $size) {
                            $quantity = $size_quantities[$size]; // Use the quantity for the specific size
                            echo "<div class='size-circle' title='$size' onclick='selectSize(this, \"$size\")'>
                                    $size
                                  </div>
                                  <span class='quantity'>($quantity left)</span>";
                        }
                        ?>
                    </div>
                </div>

                <button class="add-to-cart" onclick="addToCart(<?php echo $product_id; ?>)">
                    <img src="../../icons/cart.png" alt="Cart Icon" style="width: 20px; height: 20px; vertical-align: top; margin-right: 5px;">
                    Add to Cart
                </button>
                <button class="add-to-wishlist" onclick="addToWishlist(<?php echo $product_id; ?>)">
                    <img src="../../icons/heart.png" alt="Wishlist Icon" style="width: 20px; height: 20px; vertical-align: top; margin-right: 5px;">
                    Add to Wishlist
                </button>

            </div>
        </div>
    </div>
    <script src="product_details.js"></script>
</body>
</html>
