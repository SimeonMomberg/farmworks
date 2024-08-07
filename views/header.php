<?php

?>

<header>
    <div class="logo">
        <img src="../assets/images/logo.png" alt="FarmWorks Logo">
    </div>
    <h1>FarmWorks</h1>

    <div class="nav">
        <a class="nav-link" href="../views/products.php">Shop</a>
        <a class="nav-link" href="../views/sell.php">Sell</a>
        <a class="nav-link" href="../views/cart.php">Cart</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="nav-link" href="../config/logout.php">Logout</a>
        <?php else: ?>
            <a class="nav-link" href="../views/LoginRegister.php">Login/Register</a>
        <?php endif; ?>
    </div>
</header>
