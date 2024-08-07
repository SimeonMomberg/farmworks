<?php
session_start();
include '../../config/database.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id']; 
        $_SESSION['username'] = $user['name'];
        header("Location: ../../views/products.php");
    } else {
        echo '<script>
                alert("Invalid email or password");
                window.location.href = "../../views/LoginRegister.php";
              </script>';
    }
} else {
    echo '<script>
            alert("Form submission error");
            window.location.href = "../../views/LoginRegister.php";
          </script>';
}
?>
