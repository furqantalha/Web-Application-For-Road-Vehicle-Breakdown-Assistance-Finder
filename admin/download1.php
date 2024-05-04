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


$sql = "SELECT id, name, contact, email, status, date_created FROM mechanics";
$result = $conn->query($sql);

// Generate a CSV file with the data
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="mechanics.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('id', 'name', 'contact', 'email', 'status', 'date_created'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
