<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Get data from POST request
$email = sanitizeInput($_POST['email']);
$comment = sanitizeInput($_POST['comment']);

// SQL to insert data into the feedback table
$sql = "INSERT INTO feedback (email, comment) VALUES ('$email', '$comment')";

if ($conn->query($sql) === TRUE) {
    // Send a success response
    echo "Data inserted successfully!";
} else {
    // Send an error response
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>