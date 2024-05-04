<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

$shopId = $data->shopId;
$starRating = $data->starRating;
$comment = $data->comment;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Insert the review into the database (you need to adjust this based on your database schema)
$sql = "INSERT INTO shop_reviews (shop_id, star_rating, comment, username) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $shopId, $starRating, $comment, $username);

if ($stmt->execute()) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review: " . $stmt->error;
}

$stmt->close();
?>
