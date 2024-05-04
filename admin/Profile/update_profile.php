<?php
// Connect to your MySQL database (replace these values with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example: Update general information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["general"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $company = $_POST["company"];

    $sql = "UPDATE users SET name = '$name', email = '$email', company = '$company' WHERE id = 1"; // Assuming you have a user with ID 1
    $result = $conn->query($sql);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating general information: ' . $conn->error]);
    }
}

// Example: Update password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];

    // Add password update logic here

    echo json_encode(['success' => true]);
}

// Example: Update contact information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["contact"])) {
    $birthday = $_POST["birthday"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];

    // Add contact information update logic here

    echo json_encode(['success' => true]);
}

$conn->close();
?>
