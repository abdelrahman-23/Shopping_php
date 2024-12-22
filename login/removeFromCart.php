<?php
include("connection.php");
include("functions.php");

session_start();
$user_data = check_login($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_data) {
        $userID = $user_data['userID'];
        $productID = $_POST['productID'];

        $cartQuery = "SELECT cartID FROM cart WHERE userID = '$userID'";
        $cartResult = mysqli_query($con, $cartQuery);

        if ($cartResult && mysqli_num_rows($cartResult) > 0) {
            $cartData = mysqli_fetch_assoc($cartResult);
            $cartID = $cartData['cartID'];

            $deleteQuery = "DELETE FROM has WHERE cartID = '$cartID' AND productID = '$productID'";
            $deleteResult = mysqli_query($con, $deleteQuery);

            if ($deleteResult) {
                header("Location: cart.php");
                exit();
            } else {
                echo "Error removing product: " . mysqli_error($con);
            }
        } else {
            echo "Cart not found!";
        }
    } else {
        echo "User not logged in!";
    }
} else {
    echo "Invalid request method!";
}
?>
