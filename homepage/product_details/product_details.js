// product_details.js

let selectedSize = null;
let selectedColor = null;

function selectSize(element, size) {
    const sizeCircles = document.querySelectorAll('.size-circle');
    sizeCircles.forEach(circle => circle.classList.remove('selected'));
    element.classList.add('selected');
    selectedSize = size; // Store the selected size
}

function selectColor(element) {
    const colorSwatches = document.querySelectorAll('.color-swatch');
    colorSwatches.forEach(swatch => swatch.classList.remove('selected'));
    element.classList.add('selected');
    selectedColor = element.style.backgroundColor; // Store the selected color
}

function addToCart(productId) {
    if (!selectedSize || !selectedColor) {
        alert("Please select a size and a color.");
        return;
    }

    const cartItem = {
        id: productId,
        size: selectedSize,
        color: selectedColor,
        quantity: 1 // You can modify this to take quantity input if necessary
    };

    // Send the cart item to the server or update the session (you may want to use AJAX here)
    console.log("Adding to cart:", cartItem);
    // Here you would typically make an AJAX request to add the item to the cart

    alert('Item added to cart!');
}
