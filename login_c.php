<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <style>
        /* Reset some default styles */
body, h2, form {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0; /* Light gray background color */
    text-align: center;
}

h2 {
    color: #333; /* Dark text color */
    margin-top: 20px;
}

a {
    text-decoration: none;
    color: #008CBA; /* Link color */
}

a:hover {
    text-decoration: underline;
}

form {
    background-color: #fff; /* White background color for the form */
    max-width: 300px;
    margin: 20px auto;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow for the form */
}

input[type="text"],
input[type="password"] {
    width: 90%;
    padding: 7px;
    margin-bottom: 9px;
    border: 1px solid #ccc; /* Light gray border */
    border-radius: 5px;
}

button[type="submit"] {
    background-color: #008CBA; /* Blue button color */
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #005D7F; /* Darker blue on hover */
}
</style>
</head>
<body>
    <h2>Customer Login</h2>
    <a href= "index.html">homepage</a>
    <a href = "customer_reg.php">don't have account Register it<a>
    <form method="POST" >
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
<?php
session_start();
require_once "db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch hashed password from the database
    $sql = "SELECT user_id, username, password FROM user WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: customer/index.php"); // Redirect to the customer dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username.";
    }

    $conn->close();
}
?>


