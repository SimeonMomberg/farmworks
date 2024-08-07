<?php
session_start();
include '../config/database.php'; 

if (!isset($_SESSION['user_id'])) {
    echo '<script>
            alert("You must be logged in to checkout");
            window.location.href = "../views/LoginRegister.php"; // Adjust the path to your login page
          </script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        $product_ids = array_map('intval', $_SESSION['cart']);
        $product_ids_string = implode(',', $product_ids);
        $hold_until = date('Y-m-d H:i:s', strtotime('+1 day'));

        
        $sql = "UPDATE products SET hold = TRUE, hold_until = '$hold_until' WHERE product_id IN ($product_ids_string)";
        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    alert("Checkout completed successfully. Items are now on hold.");
                    window.location.href = "products.php"; // Redirect to the products page
                  </script>';
           
            unset($_SESSION['cart']);
        } else {
            echo '<script>
                    alert("Failed to update product hold status: ' . $conn->error . '");
                  </script>';
        }
    } else {
        echo '<script>
                alert("Your cart is empty.");
                window.location.href = "cart.php"; // Redirect to the cart page
              </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="checkout">
        <h1>Checkout</h1>
        <p>Proceed with your purchase.</p>
        <p>Please send the amount due to the account numbers provided below and then email proof of payment to the respective users.</p>
        
        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $product_ids = array_map('intval', $_SESSION['cart']);
            $product_ids_string = implode(',', $product_ids);

            $sql = "SELECT DISTINCT u.email, u.account_number FROM users u JOIN products p ON u.user_id = p.user_id WHERE p.product_id IN ($product_ids_string)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='user-details'>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                    echo "<p><strong>Account Number:</strong> " . htmlspecialchars($row['account_number']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No user details found.</p>";
            }
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
        <form method="POST" action="">
            <button type="submit">Complete Checkout</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
