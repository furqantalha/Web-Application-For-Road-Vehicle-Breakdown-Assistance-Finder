<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../adminsignlogin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breakdown_assistance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shopname = $_POST["shopname"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $address = $_POST["address"];
    $description = $_POST["description"];

    $targetDir = "../uploads/";
// Check if the directory exists, and create it if not
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO shops (shopname, photo_path, lat, lng, address, description) VALUES ('$shopname', '$targetFile', $lat, $lng, '$address', '$description')";
            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("Record added successfully");</script>';
            } else {
                echo '<script>alert("Error: ' . $sql . '\\n' . $conn->error . '");</script>';
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
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
			<li class="active">
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

			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
            <a href="index.php" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">
					<?php
					// Use htmlspecialchars to properly escape user input
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "breakdown_assistance";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

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

        #map-container {
            height: 400px;
            margin-bottom: 20px;
        }

        #locationForm {
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="file"] {  /* Added file input type */
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div id="map-container"></div>

<form action="" id="locationForm" method="post" enctype="multipart/form-data"> <!-- Added enctype for file upload -->
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="lng" id="lng">
    <label for="shopname">Shopname:</label>
    <input type="text" name="shopname" id="shopname" placeholder="Name" required>
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" placeholder="Address" required>
    <label for="description">Description:</label>
    <textarea name="description" id="description" placeholder="Description"></textarea>
    <label for="fileToUpload">Upload Photo:</label>
    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" required>
    <input type="submit" value="Submit">
    <button type="button" onclick="clearForm()">Clear</button>
</form>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi1J7tVDmwiNc0URaVy-We-t1cNoIPmWM&callback=initMap" async defer></script>
    <script>
        let map;
        let markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById('map-container'), {
                center: { lat: 13.036029, lng: 77.507577 },
                zoom: 15,
            });

            map.addListener('click', (event) => {
                addMarker(event.latLng, map);
            });
        }

        function addMarker(location, map) {
            // Clear existing markers
            clearMarkers();

            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: 'New Marker',
            });

            document.getElementById("lat").value = location.lat();
            document.getElementById("lng").value = location.lng();

            // Get the address using geocoding
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: location }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById("address").value = results[0].formatted_address;
                } else {
                    console.error('Geocoder failed due to: ' + status);
                }
            });

            // Save the marker
            markers.push(marker);
        }

        function clearMarkers() {
            // Remove existing markers from the map
            markers.forEach(marker => marker.setMap(null));

            // Clear the markers array
            markers = [];
        }

        function clearForm() {
        document.getElementById("shopname").value = "";
        document.getElementById("address").value = "";
        document.getElementById("description").value = "";
        document.getElementById("lat").value = "";
        document.getElementById("lng").value = "";
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