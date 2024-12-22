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

// Handle voucher collection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucherID = $_POST['voucher_id'] ?? 0;

    // Check if the voucher exists and is active
    $voucherQuery = "SELECT * FROM vouchers WHERE id = '$voucherID' AND status = 'active'";
    $voucherResult = mysqli_query($con, $voucherQuery);

    if ($voucherResult && mysqli_num_rows($voucherResult) > 0) {
        $voucherData = mysqli_fetch_assoc($voucherResult);

        // Mark voucher as claimed by the user
        $updateVoucherQuery = "UPDATE vouchers SET status = 'redeemed' WHERE id = '$voucherID'";
        if (mysqli_query($con, $updateVoucherQuery)) {
            $message = "Voucher collected successfully! Code: {$voucherData['code']} - Discount: $ {$voucherData['discount']}";
        } else {
            $message = "Error claiming the voucher.";
        }
    } else {
        $message = "Voucher not available or already claimed.";
    }
}

// Fetch available vouchers
$vouchersQuery = "SELECT * FROM vouchers WHERE status = 'active'";
$vouchersResult = mysqli_query($con, $vouchersQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Vouchers</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Project/index.css">
    <style>
        .voucher-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .voucher-container h1 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .voucher-list {
            list-style: none;
            padding: 0;
        }

        .voucher-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .voucher-item h3 {
            margin: 0;
            font-size: 1.5em;
        }

        .voucher-item button {
            background-color: #56baed;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .voucher-item button:hover {
            background-color: #3a8ed0;
        }

        .message {
            margin-top: 20px;
            font-size: 1.2em;
            color: green;
            text-align: center;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="voucher-container">
        <h1>Available Vouchers</h1>
        <ul class="voucher-list">
            <?php if ($vouchersResult && mysqli_num_rows($vouchersResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($vouchersResult)): ?>
                    <li class="voucher-item">
                        <h3><?php echo "Code: {$row['code']} - Discount: $ {$row['discount']}"; ?></h3>
                        <form method="post">
                            <input type="hidden" name="voucher_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Claim</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No vouchers available at the moment.</p>
            <?php endif; ?>
        </ul>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'successfully') !== false ? '' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
