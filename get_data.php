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
        $data[] = array(
            'id' => $row['id'],
            'shopname' => $row['shopname'],
            'photo_path' => $row['photo_path'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'description' => $row['description'],
            'address' => $row['address'],
            'avg_rating' => $row['avg_rating'],
            'reviews' => array() // Placeholder for reviews
        );

        // Fetch reviews for each shop
        $reviewSql = "SELECT username, star_rating, comment FROM shop_reviews WHERE shop_id = " . $row['id'];
        $reviewResult = $conn->query($reviewSql);

        if ($reviewResult->num_rows > 0) {
            while ($reviewRow = $reviewResult->fetch_assoc()) {
                $data[count($data) - 1]['reviews'][] = array(
                    'username' => $reviewRow['username'],
                    'star_rating' => $reviewRow['star_rating'],
                    'comment' => $reviewRow['comment']
                );
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
