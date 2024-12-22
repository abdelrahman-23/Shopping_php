<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Project/index.css">
    <script src="../Project/index.js"></script>
    <style>
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-header h1 {
            margin: 0;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 80%;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            cursor: pointer;
        }

        .cart-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: 20px;
        }

        .cart-footer label {
            margin-right: 10px;
        }

        .buy-button {
            background-color: #56baed;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buy-button:hover {
            background-color: #3a8ed0;
        }

        #bottom {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        #bottom a {
            color: #85c1e9;
            text-decoration: none;
            margin: 0 10px;
        }

        #bottom a:hover {
            color: #58a5d1;
        }
    </style>
</head>
<body>
    <nav>
        <ul class="navList">
            <li class="navItem left"><a href="index.php">NILE</a></li>
            <input type="text" id="searchbar" class="navItem searchbar" list="categories" placeholder="Search Nile.com...">
            <button type="button" id="searchbutton" class="navItem left" onclick="searchbarfunc()">Go</button>
            <li class="navItem right dropdown">
                <a href="#">CATEGORIES ▼</a>
                <ul class="dropdown-content">
                    <li><a href="../Project/pages/supermarket.html">SUPERMARKET</a></li>
                    <li><a href="../Project/pages/phones.html">PHONES & TABLETS</a></li>
                    <li><a href="../Project/pages/laptops.html">LAPTOPS & DESKTOPS</a></li>
                    <li><a href="../Project/pages/electronics.html">ELECTRONICS</a></li>
                    <li><a href="../Project/pages/fashion.html">FASHION</a></li>
                    <li><a href="../Project/pages/furniture.html">FURNITURE</a></li>
                    <li><a href="../Project/pages/baby.html">BABY PRODUCTS</a></li>
                </ul>
            </li>
            <li class="navItem right"><a href="cart.php">CART</a></li>
            <li class="navItem right"><a href="logout.php">LOG OUT</a></li>
        </ul>
    </nav>

    <?php
    include("connection.php");
    include("functions.php");
    session_start();

    if (isset($_SESSION['item_removed_message'])) {
        echo '<script>alert("' . $_SESSION['item_removed_message'] . '");</script>';
        unset($_SESSION['item_removed_message']);
    }

    $user_data = check_login($con);

    if ($user_data) {
        $userID = $user_data['userID'];

        $cartQuery = "SELECT cartID FROM cart WHERE userID = '$userID'";
        $cartResult = mysqli_query($con, $cartQuery);

        $query = "SELECT balance FROM users WHERE users.userID = '$userID'";
        $balanceResult = mysqli_query($con, $query);

        echo '<div class="cart-header">';
        if ($balanceResult) {
            $balanceData = mysqli_fetch_assoc($balanceResult);
            $balance = $balanceData['balance'];
            echo "<h1>Balance: $$balance</h1>";
        }

        if ($cartResult && mysqli_num_rows($cartResult) > 0) {
            $cartData = mysqli_fetch_assoc($cartResult);
            $cartID = $cartData['cartID'];

            $itemsQuery = "SELECT product.productID, product.productName, product.price FROM has
                           JOIN product ON product.productID = has.productID
                           WHERE has.cartID = '$cartID'";

            $itemsResult = mysqli_query($con, $itemsQuery);

            if ($itemsResult && mysqli_num_rows($itemsResult) > 0) {
                $query = "SELECT SUM(product.price) AS total_price
                          FROM product 
                          JOIN has ON product.productID = has.productID 
                          WHERE has.cartID = '$cartID'";

                $totalPriceResult = mysqli_query($con, $query);

                if ($totalPriceResult) {
                    $totalPriceData = mysqli_fetch_assoc($totalPriceResult);
                    $totalPrice = $totalPriceData['total_price'];
                    echo "<h1>Total Price: $$totalPrice</h1>";
                }

                echo '</div><div class="cart-items">';
                while ($row = mysqli_fetch_assoc($itemsResult)) {
                    echo '<div class="cart-item">';
                    echo "<h2>{$row['productName']}</h2>";
                    echo "<p>Price: \${$row['price']}</p>";
                    echo '<form method="post" action="removeFromCart.php">';
                    echo '<input type="hidden" name="productID" value="' . $row['productID'] . '">';
                    echo '<img src="../Project/photos/remove.jpeg" alt="Remove" class="button" style="height: 30px; width: 30px;" onclick="this.closest(\'form\').submit();">';
                    echo '</form>';
                    echo '</div>';
                }
                echo '</div><div class="cart-footer">';
                echo '<form method="post" action="buyItem.php">';
                echo '<label><input type="radio" name="location" value="Alexandria" required> Alexandria</label>';
                echo '<label><input type="radio" name="location" value="Cairo"> Cairo</label>';
                echo '<label><input type="radio" name="location" value="Giza"> Giza</label>';
                echo '<label><input type="radio" name="location" value="Global"> Global</label>';
                echo '<button type="submit" class="buy-button">Buy Items</button>';
                echo '</form></div>';
            } else {
                echo '<h2>No items in the cart!</h2>';
            }
        } else {
            echo '<h2>Cart not found!</h2>';
        }
    } else {
        echo '<h2>User not logged in!</h2>';
    }
    ?>

    <div id="bottom">
        <ul>
            <li><a href="../Project/pages/aboutus.html">About us</a></li>
            <li><a href="redeem.php">Redeem</a></li>
        </ul>
    </div>

</body>
</html>
