<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageDir = __DIR__ . '/Images/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    if (isset($_FILES['test_image']) && $_FILES['test_image']['error'] === UPLOAD_ERR_OK) {
        $imageTemp = $_FILES['test_image']['tmp_name'];
        $imageName = $_FILES['test_image']['name'];
        $targetPath = $imageDir . $imageName;

        if (move_uploaded_file($imageTemp, $targetPath)) {
            echo "File uploaded successfully to " . $targetPath;
        } else {
            echo "Failed to upload the file.";
        }
    } else {
        echo "No file uploaded or there was an error.";
    }
}
?>

<form action="test_upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="test_image">
    <button type="submit">Upload</button>
</form>
