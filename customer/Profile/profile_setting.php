<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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
$sql = "SELECT id, status, user_id, time_date FROM locations";
$result = $conn->query($sql);
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
		/* Styles for the form */
/* Basic styling for the form */
#form {
  margin: 20px; /* Add some margin for spacing */
}

/* Style for the address input */
#address {
  width: 200px; /* Set a specific width for better appearance */
  margin-bottom: 10px; /* Add margin at the bottom for spacing */
}

/* Styling for the search navigation */
.search {
  margin-top: 20px; /* Add margin at the top for spacing */
}

/* Style for the search input */
#searchAddress {
  width: 200px; /* Set a specific width for better appearance */
  margin-right: 10px; /* Add margin on the right for spacing */
}

/* Styles for the buttons */
button {
  padding: 8px 12px; /* Add padding to the buttons for better appearance */
  cursor: pointer; /* Change cursor to pointer on hover for better interaction */
}

/* Style specifically for the submit button inside the form */
#form input[type="submit"] {
  background-color: #4caf50; /* Green background color */
  color: white; /* White text color */
  border: none; /* Remove border */
}

/* Style for the search and current location buttons */
#searchButton,
#getCurrentLocationButton {
  background-color: #008CBA; /* Blue background color */
  color: white; /* White text color */
  border: none; /* Remove border */
}

/* Add some hover effect to the buttons */
button:hover {
  opacity: 0.8; /* Reduce opacity on hover */
}


	</style>
<title>Admin</title>
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
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="dashboard.php">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">Map view</span>
				</a>
			</li>
		
		<ul class="side-menu">
			<li>
				<a href="index.php  ">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="#" class="logout">
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
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="index.php" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">
					<?php
					// Use htmlspecialchars to properly escape user input
					$username = htmlspecialchars($_SESSION['user_id']);

					$sql_count = "SELECT COUNT(*) as total FROM locations WHERE user_id = '$username'";
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
			<a href="profile/profile.php" class="profile">
				<img src="img/people.png">
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
				
			</div>
      <form action="update_profile.php" method="post">

          <label for="username">Username:</label>
          <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required><br>

          <label for="contact">Contact:</label>
          <input type="text" id="contact" name="contact"><br>

          <label for="birthday">Birthday:</label>
          <input type="date" id="birthday" name="birthday"><br>

          <label for="country">Country:</label>
          <input type="text" id="country" name="country"><br>

          <label for="name">Name:</label>
          <input type="text" id="name" name="name"><br>

          <input type="submit" value="Update Profile">
          </form>
			

		
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>