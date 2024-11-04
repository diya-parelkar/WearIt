<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="CSS/seller_dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../Navbar/navbar.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include '../Navbar/navbar.php'; ?>
    <div class="container">
        <h2>ADMIN DASHBOARD</h2>
        <h2>Product List</h2>
        <?php
        $servername = "localhost:3307";
        $username = "root";
        $password = "";
        $dbname = "fashion_website_wpl";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch records with additional fields, including image
        $sql = "SELECT id, description, seller_name, category, price, total_quantity, s_quantity, m_quantity, l_quantity, xl_quantity, colors, image FROM product";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                <tr>
                    <th rowspan='2'>Description</th>
                    <th rowspan='2'>Seller Name</th>
                    <th rowspan='2'>Category</th>
                    <th rowspan='2'>Price</th>
                    <th colspan='5'>Quantity</th> <!-- Quantity as a merged header -->
                    <th rowspan='2'>Colors</th>
                    <th rowspan='2'>Image</th> <!-- Image column added -->
                    <th rowspan='2'>Actions</th>
                </tr>
                <tr>
                    <th>S</th>
                    <th>M</th>
                    <th>L</th>
                    <th>XL</th>
                    <th>Total</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['description']}</td>
                            <td>{$row['seller_name']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['s_quantity']}</td>
                            <td>{$row['m_quantity']}</td>
                            <td>{$row['l_quantity']}</td>
                            <td>{$row['xl_quantity']}</td>
                            <td>{$row['total_quantity']}</td>
                            <td>";
                    
                    // Decode the JSON string into a PHP array
                    $colors = json_decode($row['colors'], true); // true for associative array
                
                    // Check if json_decode was successful
                    if (json_last_error() === JSON_ERROR_NONE && is_array($colors)) {
                        foreach ($colors as $color) {
                            echo htmlspecialchars(trim($color)) . '<br>'; // Display each color on a new line
                        }
                    } else {
                        echo "Invalid colors data."; // Handle invalid JSON format
                    }
                
                    echo "  </td>
                            <td>
                                <img src='Images/" . htmlspecialchars($row['image']) . "' alt='Product Image' style='width:150px;height:200px;'>
                            </td>
                            <td>
                                <a href='PHP/update_product.php?id={$row['id']}' class='button edit-button'>Edit</a>
                                <a href='PHP/delete_product.php?id={$row['id']}' class='button delete-button' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>
                            </td>
                          </tr>";
                }                                
            echo "</table>";
        } else {
            echo "<p>No records found.</p>";
        }

        $conn->close();
        ?>
        <!-- Add Product button -->
        <div class="add-product-button">
            <a href="add_product.html" class="button">Add Product</a>
        </div>
    </div>
</body>
</html>
