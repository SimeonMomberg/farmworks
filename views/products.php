<?php
session_start();
include '../config/database.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmWorks</title>
    <link rel="stylesheet" href="../assets/css/products.css">    
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="search-filter-box">
        
        <div class="form-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search description" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <select name="sort">
                    <option value="">Sort by Price</option>
                    <option value="asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
                <label>
                    <input type="checkbox" name="hide_hold" value="1" <?php echo isset($_GET['hide_hold']) ? 'checked' : ''; ?>>
                    Hide Items on Hold
                </label>
                <button type="submit">Apply</button>
            </form>
        </div>
    </div>
    <div class='products'>
        <div class="product-list">
            <?php
            
            $now = date('Y-m-d H:i:s');
            $sql_update_hold = "UPDATE products SET hold = 0, hold_until = NULL WHERE hold = 1 AND hold_until <= '$now'";
            $conn->query($sql_update_hold);

            
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
            $hide_hold = isset($_GET['hide_hold']) ? 1 : 0;

            $sql = "SELECT * FROM products WHERE 1=1";
            if ($search) {
                $sql .= " AND pdescription LIKE '%$search%'";
            }
            if ($hide_hold) {
                $sql .= " AND hold = 0";
            }
            if ($sort == 'asc') {
                $sql .= " ORDER BY price ASC";
            } elseif ($sort == 'desc') {
                $sql .= " ORDER BY price DESC";
            }

            $result = $conn->query($sql);

            
            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['pimage']) . "' alt='Product Image'>";
                    echo "<h2>" . $row['pname'] . "</h2>";
                    echo "<p>Price: R" . $row['price'] . "</p>";
                    if ($row['hold'] == 0) {
                        echo "<button class='add-to-cart' onclick='addToCart(" . $row['product_id'] . ")'>Add to Cart</button>";
                    } else {
                        echo "<p>This item is on hold until " . $row['hold_until'] . ".</p>";
                    }
                    echo "</div>";
                }
            } else {
                echo "No products available";
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function addToCart(productId) {
            
            <?php if (!isset($_SESSION['user_id'])) { ?>
                alert('You need to log in to add items to the cart.');
                return;
            <?php } ?>

            fetch('../assets/js/add_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item added to cart');
                } else {
                    alert('Failed to add item to cart');
                }
            });
        }
    </script>
</body>
</html>
