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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $description = htmlspecialchars($_POST['description']);
    $seller_name = htmlspecialchars($_POST['seller_name']);
    $category = htmlspecialchars($_POST['category']);
    $price = $_POST['price'];
    $total_quantity = $_POST['total_quantity'];
    $s_quantity = isset($_POST['S_quantity']) ? $_POST['S_quantity'] : 0;
    $m_quantity = isset($_POST['M_quantity']) ? $_POST['M_quantity'] : 0;
    $l_quantity = isset($_POST['L_quantity']) ? $_POST['L_quantity'] : 0;
    $xl_quantity = isset($_POST['XL_quantity']) ? $_POST['XL_quantity'] : 0;
    $colors = isset($_POST['colors']) ? $_POST['colors'] : '';

    // Validation
    $errors = [];
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($seller_name)) $errors[] = "Seller Name is required.";
    if (empty($category)) $errors[] = "Category is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a positive number.";
    if (!is_numeric($total_quantity) || $total_quantity < 0) $errors[] = "Total Quantity must be a positive integer.";
    if (!is_numeric($s_quantity) || $s_quantity < 0) $errors[] = "Small Quantity must be a positive integer.";
    if (!is_numeric($m_quantity) || $m_quantity < 0) $errors[] = "Medium Quantity must be a positive integer.";
    if (!is_numeric($l_quantity) || $l_quantity < 0) $errors[] = "Large Quantity must be a positive integer.";
    if (!is_numeric($xl_quantity) || $xl_quantity < 0) $errors[] = "Extra Large Quantity must be a positive integer.";
    
    // Validate and format colors
    $colorsArray = explode(',', $colors);
    $colorsArray = array_map('trim', $colorsArray); // Remove extra whitespace
    $colorsJson = json_encode(array_filter($colorsArray)); // Convert to JSON and remove empty values
    
    
    $imageDir = '../Images/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTemp = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $folder = $imageDir . $imageName;

        // Only proceed if there are no validation errors
        if (empty($errors)) {
            // Move the uploaded file
            if (move_uploaded_file($imageTemp, $folder)) {
                echo "File uploaded successfully.";

                // Prepare the SQL statement
                $stmt = $conn->prepare(
                    "INSERT INTO product 
                    (description, seller_name, category, price, total_quantity, S_quantity, M_quantity, L_quantity, XL_quantity, colors, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );

                // Bind parameters
                $stmt->bind_param("sssdiiiiiss", 
                    $description, 
                    $seller_name, 
                    $category, 
                    $price, 
                    $total_quantity, 
                    $s_quantity, 
                    $m_quantity, 
                    $l_quantity, 
                    $xl_quantity, 
                    $colorsJson, 
                    $imageName
                );

                // Execute statement
                if ($stmt->execute()) {
                    header("Location: ../seller_dashboard.php"); // Redirect to dashboard on success
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Failed to upload the file.";
            }
        } else {
            // Output validation errors
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
        }
    } else {
        echo "<p>Please select an image file to upload.</p>";
    }
    // Close connection
    $conn->close();
}
?>
