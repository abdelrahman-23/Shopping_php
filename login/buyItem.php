<?php
include("connection.php");
include("functions.php");

session_start();

// Check if the user is logged in
$user_data = check_login($con);

if (!$user_data) {
    echo "User not logged in!";
    exit();
}

$userID = $user_data['userID'];

// Fetch user's cart ID
$cartQuery = "SELECT cartID FROM cart WHERE userID = '$userID'";
$cartResult = mysqli_query($con, $cartQuery);

if (!$cartResult || mysqli_num_rows($cartResult) === 0) {
    echo "Cart not found!";
    exit();
}

$cartData = mysqli_fetch_assoc($cartResult);
$cartID = $cartData['cartID'];

// Determine product table based on location
$selectedLocation = $_POST['location'] ?? '';
$locationMap = [
    "Alexandria" => "alex_product",
    "Cairo" => "cairo_product",
    "Giza" => "giza_product"
];

$prod = $locationMap[$selectedLocation] ?? null;

if (!$prod) {
    echo "Please select a valid location.";
    exit();
}

// Fetch cart items
$itemsQuery = "SELECT {$prod}.productID, {$prod}.price, {$prod}.stock 
               FROM has 
               JOIN {$prod} ON {$prod}.productID = has.productID 
               WHERE has.cartID = '$cartID'";
$itemsResult = mysqli_query($con, $itemsQuery);

if (!$itemsResult || mysqli_num_rows($itemsResult) === 0) {
    echo "No items in the cart to buy.";
    exit();
}

// Calculate total price and validate stock
$totalPrice = 0;
$insufficientStock = false;

while ($row = mysqli_fetch_assoc($itemsResult)) {
    if ($row['stock'] <= 0) {
        $insufficientStock = true;
        break;
    }
    $totalPrice += $row['price'];
}

if ($insufficientStock) {
    echo "Some items in your cart are out of stock!";
    exit();
}

// Check user balance
$balanceQuery = "SELECT balance FROM users WHERE userID = '$userID'";
$balanceResult = mysqli_query($con, $balanceQuery);

if (!$balanceResult || !($balanceData = mysqli_fetch_assoc($balanceResult))) {
    echo "Failed to fetch user balance.";
    exit();
}

$balance = $balanceData['balance'];

if ($balance < $totalPrice) {
    echo "You don't have enough money.";
    exit();
}

// Deduct balance and update stock
mysqli_begin_transaction($con);

try {
    // Deduct user balance
    $deductBalanceQuery = "UPDATE users SET balance = balance - $totalPrice WHERE userID = '$userID'";
    if (!mysqli_query($con, $deductBalanceQuery)) {
        throw new Exception("Failed to deduct balance.");
    }

    // Update product stock
    $updateStockQuery = "UPDATE {$prod} 
                         JOIN has ON {$prod}.productID = has.productID 
                         SET {$prod}.stock = {$prod}.stock - 1 
                         WHERE has.cartID = '$cartID'";
    if (!mysqli_query($con, $updateStockQuery)) {
        throw new Exception("Failed to update stock.");
    }

    // Clear the cart
    $clearCartQuery = "DELETE FROM has WHERE cartID = '$cartID'";
    if (!mysqli_query($con, $clearCartQuery)) {
        throw new Exception("Failed to clear the cart.");
    }

    // Commit transaction
    mysqli_commit($con);

    // Redirect to confirmation page
    $_SESSION['confirmation_message'] = "Thank you for your purchase!";
    $_SESSION['delivery_location'] = $selectedLocation;
    header("Location: confirmation.php");
    exit();
} catch (Exception $e) {
    // Rollback transaction in case of error
    mysqli_rollback($con);
    echo "Transaction failed: " . $e->getMessage();
    exit();
}
?>
