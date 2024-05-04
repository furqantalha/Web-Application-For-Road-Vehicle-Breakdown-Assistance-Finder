<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT shops.id, shopname, photo_path, lat, lng, description, address, AVG(review.star_rating) AS avg_rating
        FROM shops
        LEFT JOIN shop_reviews AS review ON shops.id = review.shop_id
        GROUP BY shops.id, shopname, photo_path, lat, lng, description, address";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
