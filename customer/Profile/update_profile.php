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
        echo '<script>alert("Profile updated successfully"); window.location.href="index.php";</script>';

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
