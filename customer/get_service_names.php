<?php
$shopname = $_GET['shopname'];

// Perform database query to get service names based on shopname
// Replace the following lines with your database connection and query logic
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT service_name FROM shopservices WHERE shopname=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $shopname);
$stmt->execute();
$result = $stmt->get_result();

$serviceNames = array();
while ($row = $result->fetch_assoc()) {
    $serviceNames[] = $row['service_name'];
}

$stmt->close();
$conn->close();


// Return service names as JSON
header('Content-Type: application/json');
echo json_encode($serviceNames);
?>

