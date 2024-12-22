<?php
include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productNames = json_decode(file_get_contents('php://input'), true)['productNames'];

    foreach ($productNames as $productName) {
        $productName = mysqli_real_escape_string($con, $productName);
        $query = "INSERT INTO products (productName) VALUES ('$productName')";
        if (mysqli_query($con, $query)) {
            echo "Product added successfully!";
        } else {
            echo "Error adding product: " . mysqli_error($con);
        }
    }
} else {
    echo "Invalid request method!";
}
?>
