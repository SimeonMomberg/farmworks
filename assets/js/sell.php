<?php
session_start();
include '../../config/database.php'; 


if (!isset($_SESSION['user_id'])) {
    echo '<script>
            alert("You must be logged in to sell items");
            window.location.href = "../../views/loginRegister.php"; // Adjust the path to your login page
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
    $stmt->bind_param('isdsb', $user_id, $pname, $pdescription, $price, $pimage);
    
    
    if ($stmt->execute()) {
        echo '<script>
                alert("Item listed for sale successfully");
                window.location.href = "../../views/products.php"; // Adjust this path as needed
              </script>';
    } else {
        echo '<script>
                alert("Failed to list the item for sale: ' . $stmt->error . '");
              </script>';
    }
}
?>


