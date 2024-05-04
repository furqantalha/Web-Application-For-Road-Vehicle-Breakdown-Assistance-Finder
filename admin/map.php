<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../adminsignlogin.php");
    exit();
}
// Step 1: Connect to the MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Step 2: Fetch data from the database
$sql = "SELECT id, status, user_id, time_date, username FROM locations";
$result = $conn->query($sql);

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

function updateLocationStatus($conn, $location_id, $new_status, $new_mechanic, $mechanicEmail) {
    $sql = "UPDATE locations SET status = ?, mechanic = ?, mechanicEmail = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $new_status, $new_mechanic, $mechanicEmail, $location_id);
    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["location_id"]) && isset($_POST["new_status"]) && isset($_POST["mechanic"]) && isset($_POST["username"]) && isset($_POST["mechanicEmail"])) {
        $location_id = $_POST["location_id"];
        $new_status = $_POST["new_status"];
        $new_mechanic = $_POST["mechanic"];
        $customerEmail = $_POST["username"];
        $mechanicEmail = $_POST["mechanicEmail"];

        if (updateLocationStatus($conn, $location_id, $new_status, $new_mechanic, $mechanicEmail)) {
            // Assuming you already have the necessary PHPMailer initialization
            require_once "PHPMailer/src/PHPMailer.php";
            require_once "PHPMailer/src/SMTP.php";
            require_once "PHPMailer/src/Exception.php";

            try {
                // Initialize PHPMailer
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mailid';
                $mail->Password = '';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Send email to customer
                $mail->setFrom('mailid', 'Breakdown Assistance');
                $mail->addAddress($customerEmail, 'Customer');
                $mail->isHTML(true);
        
// Assuming you have variables like $customerEmail, $new_status, $new_mechanic
                $mail->Body = "
                    <!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                            }

                            .status-container {
                                margin-top: 20px;
                                border: 1px solid #ccc;
                                padding: 10px;
                                border-radius: 5px;
                            }

                            .status {
                                display: inline-block;
                                padding: 8px;
                                margin: 5px;
                                border: 1px solid #ccc;
                                border-radius: 3px;
                            }

                            .inprogress {
                                background-color: #ffd700;
                                color: #000;
                            }

                            .completed {
                                background-color: #32cd32;
                                color: #fff;
                            }

                            .pending {
                                background-color: #ff4500;
                                color: #fff;
                            }

                            .resolved {
                                background-color: #008000;
                                color: #fff;
                            }

                            .cancelled {
                                background-color: #ff0000;
                                color: #fff;
                            }
                        </style>
                    </head>
                    <body>
                        <p>Dear $customerEmail,</p>
                        <p>Your status has been updated to $new_status, and the assigned mechanic is $new_mechanic. You can contact them for further details.</p>

                        <div class='status-container'>
                            <p><strong>Shipment Status:</strong></p>
                            <div class='status pending'>Pending</div>
                            <div class='status inprogress'>In Progress</div>
                            <div class='status resolved'>Resolved</div>
                            <div class='status cancelled'>Cancelled</div>
                            <!-- Add more statuses as needed -->

                            <p><strong>Current Status:</strong></p>
                            <div class='status inprogress'> $new_status </div>
                            <!-- Display the current status dynamically -->
                        </div>

                        <p>Thank you,<br>Breakdown Assistance</p>
                    </body>
                    </html>
                ";

                // Additional mail configuration and sending logic here...

                $mail->send();

                // Send email to mechanic
                $mail->clearAddresses();  // Clear previous recipient
                $mail->addAddress($mechanicEmail, 'Mechanic');
                $mail->Subject = 'New Assignment';

                // Fetch latitude and longitude based on username
                $sql = "SELECT latitude, longitude FROM locations WHERE username = '$customerEmail'";
                $result = $conn->query($sql);

                if ($result) {
                    $row = $result->fetch_assoc();
                    $latitude = $row['latitude'];
                    $longitude = $row['longitude'];

                    $mapLink = "https://maps.google.com/?q={$latitude},{$longitude}";
                    $mail->Body = "Dear $mechanicEmail,<br><br>Here is the link for the customer's location: <a href='{$mapLink}'>Customer Location</a>. You can use this link for further assistance.<br><br>Thank you,<br>Breakdown Assistance";
                    $mail->send();

                    echo '<script>alert("Status updated successfully! Emails sent.");</script>';
                } else {
                    echo '<script>alert("Error fetching location: ' . $conn->error . '");</script>';
                }
            } catch (Exception $e) {
                echo '<script>alert("Error sending email: ' . $mail->ErrorInfo . '");</script>';
            }
        } else {
            echo '<script>alert("Error updating status: ' . $conn->error . '");</script>';
        }
    } else {
        echo '<script>alert("Incomplete form data.");</script>';
    }
}


$locationData = getLocationData($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">

	<title>Admin</title>
    <style>
      #content {
            position: relative;
            width: calc(100% - 280px);
            left: 280px;
            transition: .3s ease;
        }
        #sidebar.hide ~ #content {
            width: calc(100% - 60px);
            left: 60px;
        }
        .location-property {
        margin-top: 15px;
    }

    form {
        margin-top: 10px;
    }

    select, button {
        margin-top: 5px;
    }

    button {
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }


    </style>
    
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">Admin</span>
		</a>
		<ul class="side-menu top">
            <li>
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li  class="active">
				<a href="map.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Map view</span>
				</a>
			</li>
			<li>
				<a href="mechanic.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Mechanic</span>
				</a>
			</li>
			<li>
				<a href="servicelist.php">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Service</span>
				</a>
			</li>
			<li>
				<a href="shop.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Home Shop</span>
				</a>
			</li>
            <li>
				<a href="chat.php">
					<i class='bx bxs-chat' ></i>
					<span class="text">ChatBot message</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="profile/index.php">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form id="searchForm">
			<div class="form-input">
				<input type="search" placeholder="Search..." list="searchOptions" id="searchInput">
				<datalist id="searchOptions">
					<option value="Dashboard">
					<option value="Map view">
					<option value="Mechanic">
					<option value="Service">
					<option value="Home Shop">
					<!-- Add more options as needed -->
				</datalist>
				<button onclick="redirectBasedOnSearch()" class="search-btn"><i class='bx bx-search'></i></button>
			</div>
		</form>

		<script>
			function redirectBasedOnSearch() {
				var searchInput = document.getElementById("searchInput").value.toLowerCase();

				// Define your links based on the search input
				switch (searchInput) {
					case "dashboard":
						document.getElementById("searchForm").action = "index.php";
						break;
					case "map view":
						document.getElementById("searchForm").action = "map.php";
						break;
					case "mechanic":
						document.getElementById("searchForm").action = "mechanic.php";
						break;
					case "service":
						document.getElementById("searchForm").action = "servicelist.php";
						break;
					case "home shop":
						document.getElementById("searchForm").action = "home.php";
						break;
					default:
						// Handle default case or display an error message
						alert("Invalid search option");
						return false; // Prevent form submission
				}

				// Continue with form submission
				document.getElementById("searchForm").submit();
			}
		</script>

			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="index.php" class="notification">
				<i class='bx bxs-bell' ></i>
                <span class="num">
					<?php
					// Use htmlspecialchars to properly escape user input

					$sql_count = "SELECT COUNT(*) as total FROM locations ";
					$result_count = $conn->query($sql_count);

					if ($result_count) {
						$row = $result_count->fetch_assoc(); // Fetch the result as an associative array
						$total = $row['total']; // Extract the count value
						echo "<span class='text'><h3>$total</h3></span>";
					} else {
						echo "Error: " . $conn->error; // Handle the query error if any
					}
					?>
				</span>
			</a>
			<a href="profile/index.php" class="profile">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
        	</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Map view</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Map view</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active1" href="#">Home</a>
						</li>
					</ul>
				</div>
			
            </div>
                    <style>
                        #map {
                            height: 400px;
                        }

                        /* Light mode styles */
                        .location-property {
                            background-color: #fff;
                            color: #333;
                            padding: 10px;
                            margin-top: 10px;
                        }

                        /* Dark mode styles */
                        .dark #map {
                            background-color: #333;
                        }

                        .dark .location-property {
                            background-color: #444;
                            color: #fff;
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
                        '<strong>Gmail Id:</strong> <?php echo $location["username"]; ?><br>' +
                        '<strong>Mechanic:</strong> <?php echo $location["mechanic"]; ?><br>' +
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
                                $sql = "SELECT name FROM mechanics"; // Assuming you have a 'name' column in the mechanics table
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
                            '<select name="mechanicEmail">' +
                            <?php
                                // Assuming you have a MySQL connection established earlier
                                $sql = "SELECT email FROM mechanics"; // Assuming you have an 'email' column in the mechanics table
                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $mechanicEmail = $row['email'];
                                        echo "'<option value=\"$mechanicEmail\">$mechanicEmail</option>' +";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    echo "'Error: ' + " . "mysqli_error($conn) +";
                                }
                            ?>
                            '</select>' +


                            '<input type="hidden" name="username" value="<?php echo $location['username']; ?>">' +
                            '<button type="submit">Update Status</button>' +
                            '</form>' +
                            '</div>';


                        // Add email sending part

                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });

                <?php endforeach; ?>
            }
            </script>



			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<div id="map"></div>

<script>
    initMap();
</script>

	<script src="script.js"></script>
</body>
</html>