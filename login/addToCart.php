<?php
include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['itemName'];

    session_start();
    $user_data = check_login($con);

    if ($user_data) {
        $userID = $user_data['userID'];

        $query = "SELECT cart.userID, cart.cartID, product.productID, product.productName 
                  FROM cart 
                  JOIN has ON cart.cartID = has.cartID 
                  JOIN product ON product.productID = has.productID 
                  WHERE userID = '$userID' AND productName = '$itemName'";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "Item already exists in cart!";
        } else {
            $cartQuery = "SELECT cartID FROM cart WHERE userID = '$userID'";
            $cartResult = mysqli_query($con, $cartQuery);

            if ($cartResult && mysqli_num_rows($cartResult) > 0) {
                $cartData = mysqli_fetch_assoc($cartResult);
                $cartID = $cartData['cartID'];

                $productQuery = "SELECT productID FROM product WHERE productName = '$itemName'";
                $productResult = mysqli_query($con, $productQuery);

                if ($productResult && mysqli_num_rows($productResult) > 0) {
                    $productData = mysqli_fetch_assoc($productResult);
                    $productID = $productData['productID'];
                    $stockQuery = "SELECT stock FROM product WHERE product.productID = '$productID'";
                    $stockResult = mysqli_query($con, $stockQuery);

                    if ($stockResult) {
                        $stockData = mysqli_fetch_assoc($stockResult);
                        $stock = $stockData['stock'];
                        
                        if ($stock > 0) {
                            $addToCartQuery = "INSERT INTO has (cartID, productID) VALUES ('$cartID', '$productID')";

                            if (mysqli_query($con, $addToCartQuery)) {
                                echo "Item added to cart successfully!";
                            } else {
                                echo "Error: " . $addToCartQuery . "<br>" . mysqli_error($con);
                            }
                        } else {
                            echo "Item out of stock!";
                        }
                    } else {
                        echo "Error fetching stock: " . mysqli_error($con);
                    }
                } else {
                    echo "Product not found!";
                }
            } else {
                echo "Cart not found!";
            }
        }
    } else {
        echo "User not logged in!";
    }
} else {
    echo "Invalid request method!";
}
?>
