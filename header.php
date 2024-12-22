<!DOCTYPE html>
<html>
<head>
    <title>Nile.com</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/index.js"></script>
</head>
<body>
<nav>
    <ul class="navList">
        <li class="navItem left"><a href="../login/index.php">NILE</a></li>
        <input type="text" id="searchbar" class="navItem searchbar" list="categories" placeholder="Search Nile.com..." style="align-self: center; border-radius: 25px; border-color: black; border-width: 2px;"> 
        <button type="button" id="searchbutton" class="navItem left" style="color: black; background-color:white; font-size: 2em; border-radius: 25px;" onclick="searchbarfunc(this)">Go</button> 
        <li class="navItem right dropdown">
            <a href="#">CATEGORIES ▼</a>
            <ul class="dropdown-content">
                <li><a href="../pages/supermarket.html">SUPERMARKET</a></li>
                <li><a href="../pages/phones.html">PHONES & TABLETS</a></li>
                <li><a href="../pages/laptops.html">LAPTOPS & DESKTOPS</a></li>
                <li><a href="../pages/electronics.html">ELECTRONICS</a></li>
                <li><a href="../pages/fashion.html">FASHION</a></li>
                <li><a href="../pages/furniture.html">FURNITURE</a></li>
                <li><a href="../pages/baby.html">BABY PRODUCTS</a></li>
            </ul>
        </li>
        <li class="navItem right"><a href="../login/cart.php">CART</a></li>
        <li class="navItem right"><a href="../login/logout.php">LOG OUT</a></li>
    </ul>
</nav>
