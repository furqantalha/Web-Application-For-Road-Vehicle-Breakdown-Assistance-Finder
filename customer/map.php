<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login_c.php");
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
$sql = "SELECT id, status, user_id, time_date FROM locations WHERE user_id = '" . $_SESSION['user_id'] . "'";
$result = $conn->query($sql);


// Step 2: Fetch data from the database
$sql = "SELECT id, status, user_id, time_date FROM locations";
$result = $conn->query($sql);

// Assuming you have a database connection function
function connectToDatabase() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "breakdown_assistance";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to fetch service names based on the shop name
function getServiceNames($shopname) {
    $conn = connectToDatabase();

    // Prevent SQL injection - use prepared statements
    $stmt = $conn->prepare("SELECT service_name FROM shopservices WHERE shopname = ?");
    $stmt->bind_param("s", $shopname);

    $stmt->execute();

    $result = $stmt->get_result();

    $serviceNames = array();

    while ($row = $result->fetch_assoc()) {
        $serviceNames[] = $row['service_name'];
    }

    $stmt->close();
    $conn->close();

    return $serviceNames;
}

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
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #map-container {
            flex: 4;
            display: flex;
        }

        #map {
            flex: 9; /* Adjust the flex value as needed */
            height: 100vh;
            width: 100vh;
        }

        #mapsidebar {
            flex: 4; /* Adjust the flex value as needed */
            height: 100vh;
            overflow-y: scroll;
            padding: 8px;
            background-color: #f4f4f4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .service-item {
            cursor: pointer;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #fff;
            transition: background-color 0.3s;
        }

        .service-item:hover {
            background-color: #e0e0e0;
        }

        .service-item strong {
            display: block;
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        #search {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
        }

        #resetButton {
            margin-bottom: 10px;
            padding: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: #fff;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        #resetButton:hover {
            background-color: #2980b9;
        }

        #searchAddress {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
        }

        #searchButton,
        #getCurrentLocationButton {
            margin-bottom: 10px;
            padding: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #3498db;
            color: #fff;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        #searchButton:hover,
        #getCurrentLocationButton:hover {
            background-color: #2980b9;
        }
 /* Style for the tabs container */
.tabs {
    display: flex;
}

/* Style for the individual tab buttons */
.tab-button {
    cursor: pointer;
    padding: 10px 15px;
    color: #fff;
    border: none;
    outline: none;
    flex-grow: 1;
    text-align: center;
    background-color: #3498db; /* Default tab background color */
    transition: background-color 0.3s;
}

/* Change the background color when a tab is active */
.tab-button.active {
    background-color: #2c3e50; /* Active tab background color */
}

/* Style for the tab content */
.tab-content {
    display: none;
    padding: 20px;
    background-color: #f2f2f2; /* Change this color to your desired tab content background color */
    border-radius: 0 0 5px 5px;
}

/* Show the active tab content */
.tab-content.active {
    display: block;
}

/* Style for the image */
.info-window-content img {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Style for the review form */
/* Style for the form container */
form {
    max-width: 300px; /* Adjust the maximum width based on your design */
    margin: 0 auto;
}

/* Style for form labels */
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

/* Style for form input fields */
form input[type="text"],
form input[type="password"],
form input[type="email"],
form select,
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Style for the submit button */
form input[type="submit"] {
    background-color: #3498db;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Hover effect for the submit button */
form input[type="submit"]:hover {
    background-color: #2c3e50;
}

/* Style for the form container */
nav.search {
    margin-top: 20px;
    background-color: #f2f2f2;
    padding: 15px;
    border-radius: 5px;
}

/* Style for the "Get Current Location" button */
nav.search button {
    background-color: #3498db;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Hover effect for the "Get Current Location" button */
nav.search button:hover {
    background-color: #2c3e50;
}


.star-rating {
    font-size: 24px;
}

.star {
    color: #ccc;
    cursor: pointer;
    transition: color 0.3s;
}

.star:hover,
.star.active {
    color: #f39c12; /* Change this color to your desired active star color */
}


/* Style for the submit button in the form */
button[type="submit"],
button[type="button"] {
    background-color: #3498db; /* Change this color to your desired button background color */
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Hover effect for buttons */
button:hover {
    background-color: #2c3e50; /* Change this color to your desired button hover background color */
}

   
    </style>
    <title>customer</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="dashboard.php" class="brand">
            <i class='bx bxs-smile'></i>
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="map.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Map view</span>
                </a>
            </li>
        </ul>

        <ul class="side-menu">
            <li>
                <a href="profile/index.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
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
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form id="searchForm">
			<div class="form-input">
				<input type="search" placeholder="Search..." list="searchOptions" id="searchInput">
				<datalist id="searchOptions">
					<option value="Dashboard">
					<option value="Map view">
				
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
                <i class='bx bxs-bell'></i>
                <span class="num">
                    <?php
                    $username = htmlspecialchars($_SESSION['user_id']);

                    $sql_count = "SELECT COUNT(*) as total FROM locations WHERE user_id = '$username'";
                    $result_count = $conn->query($sql_count);

                    if ($result_count) {
                        $row = $result_count->fetch_assoc();
                        $total = $row['total'];
                        echo "<span class='text'><h3>$total</h3></span>";
                    } else {
                        echo "Error: " . $conn->error;
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
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>

            </div>
            <div id="map-container">
                <div id="map"></div>
                <div id="mapsidebar"></div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
        <script>
            function initMap() {
    // Your map initialization code goes here
}
var map;
var markers = [];
var originalServices = [];
var filteredServices = [];
var username = '<?php echo $_SESSION['username']; ?>';

function initializeMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: userLocation,
                zoom: 12
            });

            const blueMarkerIcon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 6,
                fillColor: "blue",
                fillOpacity: 1,
                strokeWeight: 0
            };

            const marker = new google.maps.Marker({
                position: userLocation,
                map: map,
                title: "Your Current Location",
                icon: blueMarkerIcon
            });

            fetchOriginalServices();
        }, function () {
            handleLocationError(true);
        });
    } else {
        handleLocationError(false);
    }
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(userLocation);
            map.setZoom(12);

            markers.forEach(marker => {
                if (marker.title === "Your Current Location") {
                    marker.setPosition(userLocation);
                }
            });

            // Update the form fields with location details
            document.getElementById('lat').value = userLocation.lat;
            document.getElementById('lng').value = userLocation.lng;

            // Reverse geocoding to get the address
            reverseGeocode(userLocation.lat, userLocation.lng);
        }, function () {
            handleLocationError(true);
        });
    } else {
        handleLocationError(false);
    }
}

function reverseGeocode(lat, lng) {
    var reverseGeocodeURL = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=APIKEY`;

    fetch(reverseGeocodeURL)
        .then(response => response.json())
        .then(data => {
            var address = data.results[0].formatted_address;
            document.getElementById('address').value = address;
        })
        .catch(error => {
            console.error('Error in reverse geocoding:', error);
        });
}

function fetchOriginalServices() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            originalServices = JSON.parse(this.responseText);
            filteredServices = originalServices.slice();
            updateMap();
        }
    };

    xhttp.open("GET", "get_data.php", true);
    xhttp.send();
}
// Inside your existing JavaScript code...

// Add a function to create and apply dynamic styles
function applyDynamicStyles() {
    // Example: Apply a dynamic background color to the body element
    document.body.style.backgroundColor = '#f4f4f4';

    // Example: Apply dynamic styles to specific elements
    var mapContainer = document.getElementById('map-container');
    mapContainer.style.display = 'flex';

    var map = document.getElementById('map');
    map.style.flex = '9';
    map.style.height = '100vh';
    map.style.width = '100vh';

    var mapSidebar = document.getElementById('mapsidebar');
    mapSidebar.style.flex = '4';
    mapSidebar.style.height = '100vh';
    mapSidebar.style.overflowY = 'scroll';
    mapSidebar.style.padding = '8px';
    mapSidebar.style.backgroundColor = '#f4f4f4';
    mapSidebar.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';

    // Add more dynamic styles as needed...
}

// Call the function to apply dynamic styles
applyDynamicStyles();

// Rest of your existing JavaScript code...

function filterServices() {
    var searchInput = document.getElementById('search').value.toLowerCase().trim();

    if (event && event.inputType === 'deleteContentBackward' && searchInput === '') {
        filteredServices = originalServices.slice();
    } else {
        filteredServices = originalServices.filter(function (service) {
            var shopnameLower = service.shopname.toLowerCase();
            return (
                shopnameLower.startsWith(searchInput) ||
                (shopnameLower.length > 1 && shopnameLower.charAt(1) === searchInput) ||
                (shopnameLower.length > 2 && shopnameLower.charAt(2) === searchInput)
            );
        });
    }

    updateMap();
}

function resetView() {
    filteredServices = originalServices.slice();
    document.getElementById('search').value = '';
    updateMap();
}

function updateMap() {
    markers.forEach(function (marker) {
        marker.setMap(null);
    });
    markers = [];

    var sidebar = document.getElementById('mapsidebar');
    sidebar.innerHTML = '';

    var searchInput = document.getElementById('search');
    if (!searchInput) {
        searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.id = 'search';
        searchInput.placeholder = 'Search service';
        searchInput.oninput = filterServices;
        sidebar.appendChild(searchInput);
    }

    var resetButton = document.getElementById('resetButton');
    if (!resetButton) {
        resetButton = document.createElement('button');
        resetButton.id = 'resetButton';
        resetButton.innerHTML = 'Reset View';
        resetButton.onclick = resetView;
        sidebar.appendChild(resetButton);
    }

    filteredServices.forEach(function (service, index) {
        var marker = new google.maps.Marker({
            position: { lat: parseFloat(service.lat), lng: parseFloat(service.lng) },
            map: map,
            title: service.shopname,
            animation: google.maps.Animation.DROP,
        });
        markers.push(marker);


// Assuming $service is an associative array received from PHP


    var infowindow = new google.maps.InfoWindow({
        content: `<div class="info-window-content">
        
                    <div class="tabs">
                        <button class="tab-button" onclick="openTab('reviewTab')">Review</button>
                        <button class="tab-button" onclick="openTab('emergencyTab')">Emergency Request</button>
                    </div>
                    <img src="${service.photo_path}" alt="${service.shopname}" width="100"><br>
                    <strong>${service.shopname}</strong><br>
                    <i class='bx bxs-detail'> ${service.description}</i><br>
                    <i class='bx bxs-location-plus'></i>${service.address}  <br>

                    <div id="reviewTab" class="tab-content">
                        <h2>Review</h2>
                        <form id="reviewForm-${service.id}">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" value="${username}" readonly>
                            <label for="starRating">Star Rating:</label>
                            <div class="star-rating">
                                <span class="star" onclick="rateStar(1, ${service.id})">&#9733;</span>
                                <span class="star" onclick="rateStar(2, ${service.id})">&#9733;</span>
                                <span class="star" onclick="rateStar(3, ${service.id})">&#9733;</span>
                                <span class="star" onclick="rateStar(4, ${service.id})">&#9733;</span>
                                <span class="star" onclick="rateStar(5, ${service.id})">&#9733;</span>
                            </div>
                            <!-- Add a hidden input to store the selected rating -->
                            <input type="hidden" id="starRating" name="starRating">
                            <label for="comment">Comment:</label>
                            <textarea id="comment" name="comment" rows="4" cols="50"></textarea><br>
                            <button type="button" onclick="submitReview(${service.id})">Submit Review</button>
                        </form>
                    </div>

                    <div id="emergencyTab" class="tab-content">
                        <h2>Emergency Request</h2>
                        <form action="save_location.php" id="form" method="post">
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                            <label for="address">Address:</label>
                            <input type="text" name="address" id="address" placeholder="Address" required>
                            <label for="service_name">Service Name:</label>
                            <select name="service_name" id="service_name">
                            <?php
                            $sql = "SELECT service_name FROM services";
                            $result = $conn->query($sql);
                            
                            // Check if there are results
                            if ($result->num_rows > 0) {
                                // Output options for each service name
                                while ($row = $result->fetch_assoc()) {
                                    $services_name = $row['service_name'];
                                    echo "<option value='$services_name'>$services_name</option>";
                                }
                            } else {
                                // Handle the case when there are no results
                                echo "<option value=''>No services found</option>";
                            }
                            
                            // Close the database connection
                            $conn->close();
                            ?>

                            </select>
                            <input type="hidden" name="shopname" value="${service.shopname}">
                            <input type="submit" value="Submit">
                        </form>
                        <style>
                        
                        </style>
                        <button onclick="getCurrentLocation()">
                            <i class='bx bx-current-location'></i> Current Location
                            </button>


                    </div>
                    
                </div>`
    });

marker.addListener('click', function () {
    infowindow.open(map, marker);
});

        var serviceItem = document.createElement('div');
        serviceItem.className = 'service-item';
        serviceItem.innerHTML = `<strong>${service.shopname}</strong><br>` +
            `<i class='bx bxs-detail'> ${service.description}<br>` +
            `<i class='bx bxs-location-plus'> ${service.address}`;
        serviceItem.onclick = function () {
            map.setCenter({ lat: parseFloat(service.lat), lng: parseFloat(service.lng) });
            map.setZoom(15);
            infowindow.open(map, marker);
        };

        sidebar.appendChild(serviceItem);
    });
}
// Add this function to your existing script
// Add this function to your existing script
function rateStar(rating, serviceId) {
    // Remove 'active' class from all stars
    const stars = document.querySelectorAll(`#reviewForm-${serviceId} .star`);
    stars.forEach(star => star.classList.remove('active'));

    // Add 'active' class to clicked stars
    for (let i = 1; i <= rating; i++) {
        const star = document.querySelector(`#reviewForm-${serviceId} .star:nth-child(${i})`);
        star.classList.add('active');
    }

    // Set the selected rating in the hidden input
    document.querySelector(`#reviewForm-${serviceId} #starRating`).value = rating;
}

// Modify the existing submitReview function
function submitReview(shopId) {
    var starRating = document.getElementById(`reviewForm-${shopId}`).elements['starRating'].value;
    var comment = document.getElementById(`reviewForm-${shopId}`).elements['comment'].value;

    var reviewData = {
        shopId: shopId,
        username: username,
        starRating: starRating,
        comment: comment
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_review.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log(xhr.responseText);
                alert("Thank you for reviewing!");
                // Close the infowindow (if you have infowindow reference)
                // markers.forEach(marker => {
                //     infowindow.close();
                // });
            } else {
                console.error("Error submitting review:", xhr.statusText);
                alert('Error submitting review');
            }
        }
    };

    xhr.send(JSON.stringify(reviewData));
}

function openTab(tabName) {
    var i;
    var tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    var tabButtons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove("active");
    }

    var tabElement = document.getElementById(tabName);
    if (tabElement) {
        tabElement.style.display = "block";

        // Use tabName directly to add the "active" class
        for (i = 0; i < tabButtons.length; i++) {
            if (tabButtons[i].innerHTML.toLowerCase().includes(tabName.toLowerCase())) {
                tabButtons[i].classList.add("active");
                break;
            }
        }
    }
}

// Set the default tab to be displayed
openTab('reviewTab');

initializeMap();

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=APIKEY&libraries=places&callback=initMap"
        async defer></script>
           
</body>

</html>
