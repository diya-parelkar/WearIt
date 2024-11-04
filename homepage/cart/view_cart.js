function removeFromCart(index) {
    fetch(`remove_from_cart.php?index=${index}`, {
        method: 'GET'
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload(); // Reloads the cart page to reflect changes
    })
    .catch(error => console.error('Error:', error));
}
