<?php
require_once "db.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert admin user into the database
    $role = "admin";
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashedPassword', '$role')";

    if (mysqli_query($conn, $query)) {
        echo "Admin user created successfully.";
    } else {
        echo "Error creating admin user: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
