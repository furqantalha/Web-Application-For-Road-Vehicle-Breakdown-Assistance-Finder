<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
</head>
<body>
    <style>
        /* Add some basic styles for the form */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
}

h2 {
    text-align: center;
}

form {
    max-width: 300px;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #0074D9;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
    <h2>Customer Registration</h2>
    <form method="POST" >
        Username: <input type="text" name="username"><br>
        Contact: <input type="text" name="conatact"><br>
        Password: <input type="password" name="password"><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
<?php
require_once "db.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $contact = $_POST['conatact'];
    $password = $_POST['password'];

  
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
    $sql = "INSERT INTO user ( username, contact, password) VALUES ('$username','$contact','$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login_c.php'>Login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    
}
?>
