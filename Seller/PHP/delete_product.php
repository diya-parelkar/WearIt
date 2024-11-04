<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "fashion_website_wpl";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the id is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No ID provided.";
}

// Close connection
$conn->close();

// Redirect back to the product list page
header("Location: ../seller_dashboard.php");
exit();
?>
