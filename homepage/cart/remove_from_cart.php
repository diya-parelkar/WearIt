<?php
session_start();

if (isset($_GET['index'])) {
    $index = $_GET['index'];

    // Remove item from cart session array
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        echo "Item removed from cart.";
    } else {
        echo "Item not found in cart.";
    }
} else {
    echo "Invalid request.";
}
?>
