<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emergency"])) {
    $latitude = floatval($_POST["latitude"]);
    $longitude = floatval($_POST["longitude"]);
    
    $sql = "INSERT INTO assistance_requests (latitude, longitude) VALUES ($latitude, $longitude)";
    if ($conn->query($sql) === TRUE) {
        echo "Emergency request submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM assistance_requests WHERE status = 'pending'";
$result = $conn->query($sql);
$assistanceRequests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assistanceRequests[] = $row;
    }
}
$conn->close();
?>

