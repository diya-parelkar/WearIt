<?php
// Fetch the product details for the given ID
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "fashion_website_wpl";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update product details
    $description = htmlspecialchars($_POST['description']);
    $seller_name = htmlspecialchars($_POST['seller_name']);
    $category = htmlspecialchars($_POST['category']);
    $price = $_POST['price'];
    $total_quantity = $_POST['total_quantity'];
    $s_quantity = $_POST['S_quantity'] ?? null;
    $m_quantity = $_POST['M_quantity'] ?? null;
    $l_quantity = $_POST['L_quantity'] ?? null;
    $xl_quantity = $_POST['XL_quantity'] ?? null;

    // Check for validation errors (if any)
    $errors = [];
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($seller_name)) $errors[] = "Seller name is required.";
    if (empty($category)) $errors[] = "Category is required.";
    if (empty($price) || !is_numeric($price)) $errors[] = "Price must be a valid number.";
    if (empty($total_quantity) || !is_numeric($total_quantity)) $errors[] = "Total quantity must be a valid number.";

    // Image upload handling
    $imageName = $product['image']; // Default to existing image name
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];
        $folder = '../Images/' . $imageName;

        // Create directory if it does not exist
        if (!is_dir('../Images')) {
            mkdir('../Images', 0777, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($imageTemp, $folder)) {
            echo "File uploaded successfully.";
        } else {
            echo "Failed to upload the file.";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // Prepare and bind the SQL statement for updating
        $stmt = $conn->prepare("UPDATE product SET description=?, seller_name=?, category=?, price=?, total_quantity=?, s_quantity=?, m_quantity=?, l_quantity=?, xl_quantity=?, image=? WHERE id=?");
        $stmt->bind_param("sssdiiiiisi", $description, $seller_name, $category, $price, $total_quantity, $s_quantity, $m_quantity, $l_quantity, $xl_quantity, $imageName, $product_id);

        if ($stmt->execute()) {
            header("Location: ../seller_dashboard.php");
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    }
}

// Fetch current product details for display
$result = $conn->query("SELECT * FROM product WHERE id = $product_id");
$product = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="../CSS/update_product.css">
</head>
<body>
    <div class="container">
        <h1>Update Product</h1>
        <form action="update_product.php?id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($product['description']); ?>" required>
            </div>
            <div class="form-group">
                <label for="seller-name">Seller Name:</label>
                <input type="text" id="seller-name" name="seller_name" value="<?php echo htmlspecialchars($product['seller_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="womens_apparel" <?php if ($product['category'] == 'womens_apparel') echo 'selected'; ?>>Women's Apparel</option>
                    <option value="mens_apparel" <?php if ($product['category'] == 'mens_apparel') echo 'selected'; ?>>Men's Apparel</option>
                    <option value="accessories" <?php if ($product['category'] == 'accessories') echo 'selected'; ?>>Accessories</option>
                    <option value="kids_apparel" <?php if ($product['category'] == 'kids_apparel') echo 'selected'; ?>>Kid's Apparel</option>
                    <option value="shoes" <?php if ($product['category'] == 'shoes') echo 'selected'; ?>>Shoes</option>
                    <option value="other" <?php if ($product['category'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="total-quantity">Total Quantity:</label>
                <input type="number" id="total-quantity" name="total_quantity" value="<?php echo htmlspecialchars($product['total_quantity']); ?>" required>
            </div>
            <div class="form-group">
                <label for="s-quantity">Small Quantity:</label>
                <input type="number" id="s-quantity" name="S_quantity" value="<?php echo htmlspecialchars($product['S_quantity']); ?>">
            </div>
            <div class="form-group">
                <label for="m-quantity">Medium Quantity:</label>
                <input type="number" id="m-quantity" name="M_quantity" value="<?php echo htmlspecialchars($product['M_quantity']); ?>">
            </div>
            <div class="form-group">
                <label for="l-quantity">Large Quantity:</label>
                <input type="number" id="l-quantity" name="L_quantity" value="<?php echo htmlspecialchars($product['L_quantity']); ?>">
            </div>
            <div class="form-group">
                <label for="xl-quantity">Extra Large Quantity:</label>
                <input type="number" id="xl-quantity" name="XL_quantity" value="<?php echo htmlspecialchars($product['XL_quantity']); ?>">
            </div>
            <div class="form-group">
                <label for="colors">Colors (comma-separated):</label>
                <input type="text" id="colors" name="colors" value="<?php echo htmlspecialchars($product['colors']); ?>" placeholder="e.g., Red, Blue, Green">
                <small>Enter colors separated by commas</small>
            </div>
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image">
                <?php if (!empty($product['image'])): ?>
                    <p>Current Image: <?php echo htmlspecialchars($product['image']); ?></p>
                <?php endif; ?>
            </div>
            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>

