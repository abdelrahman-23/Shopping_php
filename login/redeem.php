<?php
include("connection.php");
include("functions.php");
session_start();

// Check if user is logged in
$user_data = check_login($con);
if (!$user_data) {
    echo "User not logged in!";
    exit();
}

$userID = $user_data['userID'];
$message = '';

// Handle voucher redemption
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucherCode = $_POST['voucher_code'] ?? '';

    // Validate voucher code
    $voucherQuery = "SELECT * FROM vouchers WHERE code = '$voucherCode' AND status = 'active'";
    $voucherResult = mysqli_query($con, $voucherQuery);

    if ($voucherResult && mysqli_num_rows($voucherResult) > 0) {
        $voucherData = mysqli_fetch_assoc($voucherResult);
        $discount = $voucherData['discount']; // Discount amount or percentage

        // Update user balance
        $updateBalanceQuery = "UPDATE users SET balance = balance + $discount WHERE userID = '$userID'";
        $updateVoucherQuery = "UPDATE vouchers SET status = 'redeemed' WHERE code = '$voucherCode'";

        if (mysqli_query($con, $updateBalanceQuery) && mysqli_query($con, $updateVoucherQuery)) {
            $message = "Voucher redeemed successfully! You received $$discount.";
        } else {
            $message = "Error applying voucher.";
        }
    } else {
        $message = "Invalid or already redeemed voucher code.";
    }
}

// Fetch user balance
$balanceQuery = "SELECT balance FROM users WHERE userID = '$userID'";
$balanceResult = mysqli_query($con, $balanceQuery);
$balance = $balanceResult ? mysqli_fetch_assoc($balanceResult)['balance'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redeem Voucher</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Project/index.css">
    <style>
        .redeem-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .redeem-container h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .redeem-container p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .redeem-container input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .redeem-container button {
            background-color: #56baed;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .redeem-container button:hover {
            background-color: #3a8ed0;
        }

        .message {
            margin-top: 20px;
            font-size: 1.2em;
            color: green;
        }

        .error {
            color: red;
        }

        .back-to-shopping {
            display: inline-block;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-to-shopping:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="redeem-container">
        <h1>Redeem Your Voucher</h1>
        <p>Your Current Balance: $<?php echo number_format($balance, 2); ?></p>
        <form method="post">
            <input type="text" name="voucher_code" placeholder="Enter Voucher Code" required>
            <br>
            <button type="submit">Redeem</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? '' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="back-to-shopping">Back to Shopping</a>
    </div>
</body>
</html>
