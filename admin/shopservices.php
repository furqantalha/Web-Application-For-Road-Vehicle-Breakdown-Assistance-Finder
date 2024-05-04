<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the JSON data sent from the client
$data = json_decode(file_get_contents("php://input"));

$shopname = $data->shopname;
$service_name = $data->service_name;

// Validate and sanitize the data (you should perform more validation based on your requirements)

// Prevent SQL injection
$shopname = mysqli_real_escape_string($conn, $shopname);
$service_name = mysqli_real_escape_string($conn, $service_name);

// Insert data into the database
$sql = "INSERT INTO shopServices (shopname, service_name) VALUES ('$shopname', '$service_name')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
