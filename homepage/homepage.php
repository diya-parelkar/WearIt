<?php
session_start(); // Start session to access user name

// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "fashion_website_wpl";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_name'])) {
    header("Location: ../login/login.html");
    exit();
}

// Get filter values from form submission
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$colorFilter = isset($_GET['colors']) ? $_GET['colors'] : '';
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$sellerFilter = isset($_GET['seller_name']) ? $_GET['seller_name'] : '';

// Fetch unique sellers for the seller dropdown filter
$sellerResult = $conn->query("SELECT DISTINCT seller_name FROM product");

// SQL query for filtering products
$sql = "SELECT id, description, price, image, category FROM product WHERE 1=1";

if ($categoryFilter) {
    $sql .= " AND category = '" . $conn->real_escape_string($categoryFilter) . "'";
}
if ($colorFilter) {
    $sql .= " AND FIND_IN_SET('" . $conn->real_escape_string($colorFilter) . "', colors) > 0";
}
if ($priceMin !== '' && $priceMax !== '') {
    $sql .= " AND price BETWEEN " . intval($priceMin) . " AND " . intval($priceMax);
}
if ($sellerFilter) {
    $sql .= " AND seller_name = '" . $conn->real_escape_string($sellerFilter) . "'";
}

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

    <div class="welcome-bar">
        <h2>Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Guest"; ?>!</h2>
    </div>

    <div class="main-content">
        <!-- Sidebar for filters -->
        <div class="filter-sidebar">
            <form method="GET" action="">
                <h3>Filter By</h3>
                
                <!-- Category Filter -->
                <div class="filter-group">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">All Categories</option>
                        <option value="Tops">Tops</option>
                        <option value="Bottoms">Bottoms</option>
                        <option value="Dresses">Dresses</option>
                        <option value="Shoes">Shoes</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Kids Apparel">Kids Apparel</option>
                        <option value="Outerwear">Outerwear</option>
                    </select>
                </div>

                <!-- Color Filter -->
                <div class="filter-group">
                    <label for="colors">Color</label>
                    <select name="colors" id="colors">
                        <option value="">All Colors</option>
                        <option value="Red">Red</option>
                        <option value="Blue">Blue</option>
                        <option value="Green">Green</option>
                        <option value="Pink">Pink</option>
                        <option value="Black">Black</option>
                        <option value="Brown">Brown</option>
                        <option value="Gray">Gray</option>
                        <option value="Yellow">Yellow</option>
                        <option value="Dark Blue">Dark Blue</option>
                        <option value="Light Blue">Light Blue</option>
                        <option value="White">White</option>
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div class="filter-group">
                    <label>Price Range</label>
                    <input type="number" name="price_min" placeholder="Min" value="<?php echo htmlspecialchars($priceMin); ?>">
                    <input type="number" name="price_max" placeholder="Max" value="<?php echo htmlspecialchars($priceMax); ?>">
                </div>

                <!-- Seller Filter -->
                <div class="filter-group">
                    <label for="seller_name">Seller</label>
                    <select name="seller_name" id="seller_name">
                        <option value="">All Sellers</option>
                        <?php
                        if ($sellerResult->num_rows > 0) {
                            while ($row = $sellerResult->fetch_assoc()) {
                                $selected = ($row['seller_name'] == $sellerFilter) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($row['seller_name']) . "' $selected>" . htmlspecialchars($row['seller_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <!-- Product Display Section -->
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
    </div>
</body>
</html>

<?php
$conn->close();
?>
