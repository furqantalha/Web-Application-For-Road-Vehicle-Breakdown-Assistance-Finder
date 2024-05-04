<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit();
}

// Your database connection code here (replace with your actual database credentials)
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "breakdown_assistance";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the current user's ID from the session
$current_user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $contact = $_POST["contact"];
    $birthday = $_POST["birthday"];
    $country = $_POST["country"];
    $name = $_POST["name"];

    // Validate and sanitize the data (you should add proper validation)

    // Hash the password (you should use a secure hashing algorithm)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's profile in the database
    $sql = "UPDATE user SET username='$username', password='$hashed_password', contact='$contact', birthday='$birthday', country='$country', name='$name' WHERE user_id='$current_user_id'";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Profile updated successfully");</script>';
    } else {
        echo '<script>alert("Error updating profile: ' . $conn->error . '");</script>';
    }
}

// Fetch the current user's information from the database
$sql = "SELECT * FROM user WHERE user_id='$current_user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();

    // Use the fetched data to pre-fill the form fields
    $username = $row['username'];
    $contact = $row['contact'];
    $birthday = $row['birthday'];
    $country = $row['country'];
    $name = $row['name'];
} else {
    echo "User not found.";
}

// Close the database connection
$conn->close();
?>

<!-- HTML form goes here with pre-filled values -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            margin: 50px 90px;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
    <h2>Profile Update</h2>
    <form action="" method="post">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact"><br>

        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday"><br>

        <label for="country">Country:</label>
        <input type="text" id="country" name="country"><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br>

        <input type="submit" value="Update Profile">
    </form>
</body>
</html>
