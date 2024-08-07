<?php
include '../../config/database.php';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $checkEmailStmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $checkEmailStmt->bind_param('s', $email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();

    if ($checkEmailResult->num_rows > 0) {
        echo '<script>
                alert("Email already exists");
              </script>';
    } else {
        
        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $password);

        if ($stmt->execute()) {
            echo '<script>
                    alert("Registration successful");
                    window.location.href = "../../views/products.php"; // Ensure this path is correct
                  </script>';
        } else {
            echo '<script>
                    alert("Registration failed");
                  </script>';
        }
    }
}
?>
