<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}//Connect to the MySQL database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "breakdown_assistance";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch location data
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

    function updateLocationStatus($conn, $location_id, $new_status, $new_mechanic) {
    $sql = "UPDATE locations SET status = ?, mechanic = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $new_status, $new_mechanic, $location_id);
    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["location_id"]) && isset($_POST["new_status"]) && isset($_POST["mechanic"])) {
        $location_id = $_POST["location_id"];
        $new_status = $_POST["new_status"];
        $new_mechanic = $_POST["mechanic"];
         if (updateLocationStatus($conn, $location_id, $new_status, $new_mechanic)) {
            echo '<script>alert("Status updated successfully!");</script>';
        } else {
            echo '<script>alert("Error updating status: ' . $conn->error . '");</script>';
        }
    }
}
$locationData = getLocationData($conn);
?>


<html>
<head>
    <title>Admin View - Geo Location Data</title>
    <style>
        /* Add your CSS styles for the table here */
        #map {
            height: 400px;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=APIKEY&callback=initMap"></script>
    <script>
    var map;
    var infoWindow;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: new google.maps.LatLng(13.00579, 77.594417) // Set a default center
        });

        infoWindow = new google.maps.InfoWindow();

        <?php foreach ($locationData as $location): ?>
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(<?php echo $location["latitude"]; ?>, <?php echo $location["longitude"]; ?>),
                map: map,
                title: "ID: <?php echo $location["id"]; ?>, Address: <?php echo $location["address"]; ?>"
            });
            
            // Attach a click event listener to the marker to display place details and the update form
            marker.addListener('click', function() {
                var content = '<strong>ID:</strong> <?php echo $location["id"]; ?><br>' +
                              '<strong>Latitude:</strong> <?php echo $location["latitude"]; ?><br>' +
                              '<strong>Longitude:</strong> <?php echo $location["longitude"]; ?><br>' +
                              '<strong>Address:</strong> <?php echo $location["address"]; ?><br>' +
                              '<strong>User Id:</strong> <?php echo $location["user_id"]; ?><br>' +
                              '<strong>mechanics</strong> <?php echo $location["mechanic"]; ?><br>' +
                              '<div class="location-property">' +
                              '<form method="POST">' +
                              '<strong>Status Update:</strong>' +
                              '<input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">' +
                              '<select name="new_status">' +
                              '<option value="pending" <?php if ($location['status'] === 'pending') echo 'selected'; ?>>Pending</option>' +
                              '<option value="On-progressing" <?php if ($location['status'] === 'On-progressing') echo 'selected'; ?>>On-progress</option>' +
                              '<option value="resolved" <?php if ($location['status'] === 'resolved') echo 'selected'; ?>>Resolved</option>' +
                              '<option value="cancelled" <?php if ($location['status'] === 'cancelled') echo 'selected'; ?>>Cancelled</option>' +
                              '</select>' +
                              '<select name="mechanic">' +
                              <?php
                                // Assuming you have a MySQL connection established earlier
                                $sql = "SELECT name FROM mechanics";
                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $mechanicName = $row['name'];
                                        echo "'<option value=\"$mechanicName\">$mechanicName</option>' +";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    echo "'Error: ' + " . "mysqli_error($conn) +";
                                }
                              ?>
                              '</select>' +
                              '<input type="hidden" name="location_id" value="<?php echo $location['id']; ?>">' +
                              '<button type="submit">Update Status</button>' +
                              '</form>' +
                              '</div>';

                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        <?php endforeach; ?>
    }
</script>

    
</head>
<body>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <title>Admin View - Geo Location Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        #map{
            width: auto;
            height: 633;
        }
        h2 {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            float: left;
            height: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
        }

        .logo i {
            font-size: 24px;
            margin-right: 10px;
        }

        .logo-name {
            font-size: 20px;
        }

        .side-menu {
            list-style: none;
            padding: 0;
        }

        .side-menu li {
            margin-bottom: 10px;
        }

        .side-menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }

        .side-menu a i {
            margin-right: 10px;
        }
        /* Define CSS styles for the table and form elements */
        .location-list {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .location-item {
            display: table-row;
        }

        .location-property {
            display: table-cell;
            padding: 5px;
            border: 1px solid #ccc;
        }

        form {
            display: table-cell;
            padding: 5px;
            border: 1px solid #ccc;
        }

        select, button {
            width: 100%;
        }

    </style>
</head>

<body>
    <h2>Admin </h2>
    <div class="sidebar">
        <a href="#" class="logo">
            <i class='bx bxs-dashboard'></i>
            <div class="logo-name"><span>Dashboard</span></div>
        </a>

        <ul class="side-menu">
            <li><a href="admin_dasboard.php"><i class='bx bx-store-alt'></i>customer request</a></li>
            <li><a href="mechanic.php" class="logout"><i class='bx bx-log-out-circle'></i>mechanic list</a></li>
            <li><a href="logout.php" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a></li>
        </ul>
    </div>

</body>
</html>


<div id="map"></div>

<script>
    initMap();
</script>
</body>
</html>

