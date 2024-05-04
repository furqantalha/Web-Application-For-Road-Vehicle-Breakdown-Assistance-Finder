<?php
// Connect to your MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'your_table' table
$sql = "SELECT id, latitude, longitude, address, status, user_id, mechanic, time_date, username, mechanicEmail, service_name FROM your_table";
$result = $conn->query($sql);

// Generate a CSV file with the data
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="locations.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('id', 'latitude', 'longitude', 'address', 'status', 'user_id', 'mechanic', 'time_date', 'username', 'mechanicEmail', 'service_name'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
