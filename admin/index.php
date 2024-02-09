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
$sql = "SELECT id, status, user_id, time_date, username FROM locations ORDER BY time_date DESC";
$result = $conn->query($sql);

function getLocationData($conn) {
    $sql = "SELECT * FROM locations ORDER BY time_date DESC";
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
				$mail->send();
			} catch (Exception $e) {
				echo '<script>alert("Error sending email: ' . $e->getMessage() . '");</script>';
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
	<style>
	 .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
		/* Add your existing styles */
		.view-details-btn {
			background-color: #87CEEB; /* Sky blue color */
			color: #fff; /* White text color */
			padding: 8px 12px; /* Adjust padding as needed */
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.view-details-btn:hover {
			background-color: #5F9EA0; /* Darker shade on hover */
		}
		.popup-close {
		position: absolute;
		top: 10px;
		right: 10px;
		cursor: pointer;
		font-size: 18px;
		color: #555;
	}
	button[type="submit"] {
    background-color: #008CBA; /* Set your desired background color */
    color: #fff; /* Set your desired text color */
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
  }

  button[type="submit"]:hover {
    background-color: #005684; /* Set your desired background color on hover */
  }
  select {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
    width: 100%;
    box-sizing: border-box;
  }

	</style>

	<title>Admin</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">Admin</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
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
			<a href="#" class="notification">
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
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<a href="download.php" class="btn-download">
					<i class='bx bxs-cloud-download' ></i>
					<span class="text">Download EXCEL</span>
					
				</a>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
					
					<?php

						if ($result->num_rows > 0) {
							$row_count = $result->num_rows;
							echo "<span class='text'><h3>$row_count</h3></span>";

						}
						?>
						<p>No. of appointment</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
					<?php
						$sql_count = "SELECT COUNT(*) as total_resolved FROM locations WHERE status='cancelled'";
						$result_count = $conn->query($sql_count);

						if ($result_count) {
							$row = $result_count->fetch_assoc(); // Fetch the result as an associative array
							$total_resolved = $row['total_resolved']; // Extract the count value
							echo "<span class='text'><h3>$total_resolved</h3></span>";
						} else {
							echo "Error: " . $conn->error; // Handle the query error if any
						}
					?>
						<p>No. of cancelled</p>
					
					</span>
				</li>
				<li>
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">
				
					<?php
						$sql_count = "SELECT COUNT(*) as total_resolved FROM locations WHERE status='resolved'";
						$result_count = $conn->query($sql_count);

						if ($result_count) {
							$row = $result_count->fetch_assoc(); // Fetch the result as an associative array
							$total_resolved = $row['total_resolved']; // Extract the count value
							echo "<span class='text'><h3>$total_resolved</h3></span>";
						} else {
							echo "Error: " . $conn->error; // Handle the query error if any
						}
					?>
						<p>No. of completed</p>
					</span>
				</li>
			</ul>


			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>
						<i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<table>
        <thead>
            <tr>
                <th>User</th>
				<th>Date Order</th>
                <th>Status</th>
                <th>Status change</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locationData as $location) : ?>
                <tr>
                    <td><p><?= $location["user_id"] ?></p></td>
					<td><?= $location["time_date"] ?></td>
                    <td><span class='status <?= strtolower($location["status"]) ?>'><?= ucfirst($location["status"]) ?></span></td>
					
                    <td>
					<button class="view-details-btn" onclick='openPopup("<?= $location["user_id"] ?>", "<?= $location["time_date"] ?>", "<?= $location["status"] ?>", "<?= $location["latitude"] ?>", "<?= $location["longitude"] ?>", "<?= $location["address"] ?>", "<?= $location["username"] ?>", "<?= $location["mechanic"] ?>", "<?= $location["mechanicEmail"] ?>", "<?= $location["shopname"] ?>","<?= $location["confirm_cancel"] ?>", "<?= $location["comment"] ?>")'>View Details</button>
						<div id="popup" class="popup">
						<button class="popup-close" onclick="closePopup()">&times;</button>
				<p id="popupUser"></p>
				<p id="popupDate"></p>
				<p id="popupStatus"></p>
				<div class="location-property1">
                        <form method="POST">
                            <input type="hidden" name="location_id" value="<?= $location['id'] ?>">
							<strong>Status</strong>
                            <select name="new_status">
                                <option value="pending" <?= ($location['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="On-progressing" <?= ($location['status'] === 'On-progressing') ? 'selected' : '' ?>>On-progress</option>
                                <option value="resolved" <?= ($location['status'] === 'resolved') ? 'selected' : '' ?>>Resolved</option>
                                <option value="cancelled" <?= ($location['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                            </select>
							<strong>mechanic and email</strong>
                            <select name="mechanic">
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
							</select>
							
							<select name="mechanicEmail">
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
							</select>

                            <input type="hidden" name="username" value="<?= $location['username'] ?>">
                            <button type="submit">Update Status</button>
                        </form>
						</div>
				
			</div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

			

				<script>
					function openPopup(user, date, status, latitude, longitude, address, username, mechanic, mechanicEmail,shopname,confirm_cancel,comment) {
						document.getElementById("popupUser").innerHTML = '<strong>User:</strong> ' + user;
						document.getElementById("popupDate").innerHTML = '<strong>Date:</strong> ' + date;
						document.getElementById("popupStatus").innerHTML = '<strong>Status:</strong> ' + status + '<br>' +
							'<strong>Latitude:</strong> ' + latitude + '<br>' +
							'<strong>Longitude:</strong> ' + longitude + '<br>' +
							'<strong>Address:</strong> ' + address + '<br>' +
							'<strong>Username:</strong> ' + username + '<br>' +
							'<strong>Mechanic:</strong> ' + mechanic + '<br>' +
							'<strong>Mechanic Email:</strong> ' + mechanicEmail + '<br>'+
							'<strong>Shopname:</strong> ' + shopname + '<br>'+
							'<strong>confirm cancel:</strong> ' + confirm_cancel + '<br>'+
							'<strong>comment:</strong> ' + comment + '<br>';
						document.getElementById("popup").style.display = "block";
					}

					function closePopup() {
						document.getElementById("popup").style.display = "none";
					}
				</script>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Mechanics available</h3>
						<i class='bx bx-plus' ></i>
						<i class='bx bx-filter' ></i>
					</div>
					<ul class="todo-list">
					<?php
						$sql_count = "SELECT * FROM mechanics WHERE status='active'";
						$result_count = $conn->query($sql_count);

						if ($result_count) {
							if ($result_count->num_rows > 0) {
								while ($row = $result_count->fetch_assoc()) {
									// Fetch the 'name' column from the current row
									$name = $row['name'];

									echo "<li class='completed'>";
									echo "<p>$name</p>";
									echo "<i class='bx bx-dots-vertical-rounded'></i>";
									echo "</li>";
								}
							} else {
								echo "No active mechanics found.";
							}
						} else {
							echo "Error: " . $conn->error;
						}
					?>			
					</ul>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>