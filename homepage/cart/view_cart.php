<?php
session_start();

// Ensure cart session is an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty!</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="view_cart.css">
</head>
<body>
    <h2>Your Shopping Cart</h2>
    <div class="cart-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Price</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                foreach ($_SESSION['cart'] as $index => $item) {
                    // Verify each cart item has the expected structure
                    if (is_array($item) && isset($item['image'], $item['description'], $item['size'], $item['color'], $item['price'])) {
                        $total_price += $item['price'];
                        echo "<tr>
                                <td><img src='../../Seller/Images/{$item['image']}' alt='" . htmlspecialchars($item['description']) . "'></td>
                                <td>" . htmlspecialchars($item['description']) . "</td>
                                <td>" . htmlspecialchars($item['size']) . "</td>
                                <td><span class='color-swatch' style='background-color: " . htmlspecialchars($item['color']) . ";'></span></td>
                                <td>₹" . htmlspecialchars($item['price']) . "</td>
                                <td><button onclick='removeFromCart($index)'>Remove</button></td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <h3>Total: ₹<?php echo $total_price; ?></h3>
    </div>
    <button onclick="location.href='checkout.php'">Proceed to Checkout</button>

    <script src="view_cart.js"></script>
</body>
</html>
