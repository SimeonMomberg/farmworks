<?php
session_start();
include '../config/database.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/cart.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="cart">
    <h1><?php if (isset($_SESSION['username'])) { echo "" . htmlspecialchars($_SESSION['username']); } ?>'s Cart</h1>
        <?php
        $total = 0;
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            
            $product_ids = array_map('intval', $_SESSION['cart']);
            $product_ids_string = implode(',', $product_ids);

            
            $sql = "SELECT * FROM products WHERE product_id IN ($product_ids_string)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='cart-item'>";
                    echo "<h2>" . $row['pname'] . "</h2>";
                    echo "<p>Price: R" . $row['price'] . "</p>";
                    echo "<button onclick='removeFromCart(" . $row['product_id'] . ")'>Remove</button>";
                    echo "</div>";
                    $total += $row['price'];
                }
                echo "<h2>Total: R$total</h2>";
                echo "<button class='checkout-btn' onclick='window.location.href=\"checkout.php\"'>Checkout</button>";
            } else {
                echo "<p>Your cart is empty.</p>";
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function removeFromCart(productId) {
            fetch('../assets/js/remove_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to remove item from cart');
                }
            });
        }
    </script>
</body>
</html>
