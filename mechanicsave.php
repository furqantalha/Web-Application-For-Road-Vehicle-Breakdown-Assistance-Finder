<?php
require_once "db.php";
function getLocationData($conn) {
    $sql = "SELECT * FROM locations";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return array();
    }
}

// Function to update location status
function updateLocationStatus($conn, $location_id, $new_status) {
    $sql = "UPDATE locations SET mechanic = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $location_id);
    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["location_id"]) && isset($_POST["mechanic"])) {
        $location_id = $_POST["location_id"];
        $new_status = $_POST["mechanic"];
        if (updateLocationStatus($conn, $location_id, $new_status)) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    }
}
$locationData = getLocationData($conn);


?>
