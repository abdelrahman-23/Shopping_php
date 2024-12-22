<!DOCTYPE html>
<html>
<head>
    <title>Purchase Confirmation</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Project/index.css">
    <style>
        .confirmation {
            text-align: center;
            margin: 50px auto;
            padding: 20px;
            max-width: 600px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .confirmation h1 {
            font-size: 2em;
            color: green;
        }
        .confirmation p {
            font-size: 1.2em;
        }
        .confirmation a {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #56baed;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }
        .confirmation a:hover {
            background-color: #3a8ed0;
        }
    </style>
</head>
<body>
    <div class="confirmation">
        <?php
        session_start();
        if (isset($_SESSION['confirmation_message'])) {
            echo "<h1>" . $_SESSION['confirmation_message'] . "</h1>";
            echo "<p>Items will be delivered to " . $_SESSION['delivery_location'] . ".</p>";
            unset($_SESSION['confirmation_message'], $_SESSION['delivery_location']);
        } else {
            echo "<h1>Thank you!</h1>";
            echo "<p>Your order has been processed successfully.</p>";
        }
        ?>
        <a href="index.php">Continue Shopping</a>
    </div>
</body>
</html>
