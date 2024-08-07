<?php
session_start();
include '../config/database.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<script>
            alert("You must be logged in to sell items");
            window.location.href = "../views/LoginRegister.php"; // Adjust the path to your login page
          </script>';
    exit();
}

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id']; 
    $pname = $_POST['item_name'];
    $pdescription = $_POST['item_description'];
    $price = $_POST['price'];
    $pimage = null;

    if ($_FILES['item_image']['error'] == UPLOAD_ERR_OK) {
        $pimage = file_get_contents($_FILES['item_image']['tmp_name']); 
    } else {
        echo '<script>
                alert("Failed to upload image");
              </script>';
        exit(); 
    }

    
    $stmt = $conn->prepare('INSERT INTO products (user_id, pname, pdescription, price, pimage) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issds', $user_id, $pname, $pdescription, $price, $pimage);

   
    if ($stmt->execute()) {
        echo '<script>
                alert("Item listed for sale successfully");
                window.location.href = "../views/products.php"; // Adjust this path as needed
              </script>';
    } else {
        echo '<script>
                alert("Failed to list the item for sale: ' . $stmt->error . '");
              </script>';
    }
}


if (isset($_POST['remove'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare('DELETE FROM products WHERE product_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $product_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo '<script>
                alert("Item removed successfully");
                window.location.href = "sell.php"; // Redirect back to sell.php
              </script>';
        exit();
    } else {
        echo '<script>
                alert("Failed to remove the item: ' . $stmt->error . '");
              </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmWorks</title>
    <link rel="stylesheet" href="../assets/css/sell.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="wrapper">
      <div class="sellbox">
        <h1>Sell an Item</h1>
        
        <form action="sell.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <textarea name="item_description" placeholder="Item Description" required></textarea>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="file" name="item_image" accept="image/*" required>
            <button type="submit" name="submit">Sell Item</button>
        </form>
      </div>
      <div class='products'>
       
         <h1>Your Listed Items</h1>
         <div class="product-list">
            <?php
           
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT * FROM products WHERE user_id = $user_id";
            $result = $conn->query($sql);

           
            if ($result->num_rows > 0) {
               
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['pimage']) . "' alt='Product Image'>";
                    echo "<h2>" . $row['pname'] . "</h2>";
                    echo "<p>Price: R" . $row['price'] . "</p>";
                    echo "<form method='POST' action='sell.php'>";
                    echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
                    echo "<button type='submit' name='remove'>Remove Item</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "You have not listed any items.";
            }
            ?>
          
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
