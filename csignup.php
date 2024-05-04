<?php
require_once "db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
   
    $password = $_POST['password'];

  
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
    $sql = "INSERT INTO user ( username, password) VALUES ('$username','$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful
        echo "<script>alert('Registration successful.'); window.location.href='loginsignup.php';</script>";
    } else {
        // Error in registration
        echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "');</script>";
    }
    
    $conn->close();
    
}
?>