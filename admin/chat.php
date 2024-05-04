<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../adminsignlogin.php");
    exit();
}
$host ="localhost";
$username = "root";
$password = "";
$database = "breakdown_assistance";
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data
$sql = "SELECT id, email, comment, timestamp FROM feedback";
$result = $conn->query($sql);

// Rest of your PHP code...
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
        /* Light mode styles */
.table1 {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table1 th, .table1 td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.table1 th {
    background-color: #f2f2f2;
}

.table1 tbody {
    height: 200px; /* Set the desired height for the table body */
    overflow-y: auto;
}

/* Optional: Add a hover effect for rows */
.table1 tbody tr:hover {
    background-color: #f5f5f5;
}

/* Dark mode styles */
.dark .table1 {
    background-color: #333;
    color: #fff;
}

.dark .table1 th, .dark .table1 td {
    border-color: #555;
}

.dark .table1 th {
    background-color: #444;
}

.dark .table1 tbody tr:hover {
    background-color: #555;
}


   /* Light mode styles */
.combined {
    width: 300px;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    transition: background-color 0.3s ease;
}

.combined label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.combined select,
.combined input[type="text"],
.combined input[type="submit"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.combined select {
    height: 34px; /* Match the height of input elements for consistency */
}

.combined input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
}

.combined input[type="submit"]:hover {
    background-color: #45a049;
}

/* Optional: Add styling for form elements on focus */
.combined input:focus,
.combined select:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 5px rgba(33, 150, 243, 0.5);
}

/* Dark mode styles */
.dark .combined {
    background-color: #333;
    color: #fff;
}

.dark .combined label {
    color: #fff;
}

.dark .combined select,
.dark .combined input[type="text"],
.dark .combined input[type="submit"] {
    border-color: #555;
    background-color: #444;
    color: #fff;
}

.dark .combined input[type="submit"]:hover {
    background-color: #3d8b40;
}
table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            max-height: 300px; /* Set a fixed height for the table */
            overflow-y: auto; /* Add vertical scroll if the table exceeds the height */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
			<li >
				<a href="mechanic.php">
					<i class='bx bxs-doughnut-chart' ></i>
					<span class="text">Mechanic</span>
				</a>
			</li>
			<li>
				<a href="servicelist.php">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Service </span>
				</a>
			</li>
			<li>
				<a href="shop.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Home Shop</span>
				</a>
			</li>
            <li class="active">
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
			<a href="profile/index.php" class="profile">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Mechanic list</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Mechanic list</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<a href="downloadchat.php" class="btn-download">
					<i class='bx bxs-cloud-download' ></i>
					<span class="text">Download Excel</span>
				</a>
			</div>
      

            <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Timestamp</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['comment']}</td>
                        <td>{$row['timestamp']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data found</td></tr>";
        }
        ?>
    </tbody>
</table>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>