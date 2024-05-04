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
$sql = "SELECT id, status, user_id, time_date, address, mechanic, service_name FROM locations WHERE user_id = '" . $_SESSION['user_id'] . "' ORDER BY time_date DESC";
$result = $conn->query($sql);


// Include your database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $confirmCancel = isset($_POST["confirm_cancel"]) ? 'yes' : 'no'; // If checkbox is checked, set to 'yes'; otherwise, set to 'no'
    $comment = $_POST["comment"];

    // Update the database table
    $sql = "UPDATE locations SET confirm_cancel = ?, comment = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $confirmCancel, $comment, $id);

    // Execute the update
    if ($stmt->execute()) {
              // JavaScript alert
        echo "<script>alert('Appointment canceled successfully.');</script>";
    } else {
        echo "Error canceling appointment: " . $stmt->error;
    }


    // Close the statement
    $stmt->close();
}

// Close the database connection
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
		.cancel-btn {
            background-color: #ff0000; /* Red color for the cancel button */
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #cc0000; /* Darker shade on hover */
        }
		label {
    display: block;
    margin-bottom: 10px;
  }

  /* Checkbox styles */
  input[type="checkbox"] {
    margin-right: 5px;
  }

  /* Textarea styles */
  textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  /* Submit button styles */
  button[type="submit"] {
    background-color: #008CBA;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
  }

  button[type="submit"]:hover {
    background-color: #005684;
  }

  /* Close button styles */
  button.close-button {
    background-color: #ddd;
    color: #333;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
    margin-right: 10px;
  }

  button.close-button:hover {
    background-color: #bbb;
  }
  .popup-close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 18px;
    color: #555;
  }
		</style>


	<title>Customer</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
	<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">
				<h3><?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
			</span>
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
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="index.php" class="notification">
				<i class='bx bxs-bell'></i>
				<span class="num">
					<?php
					$servername = "localhost";
					$username = "root";
					$password = "";
					$dbname = "breakdown_assistance";
					
					$conn = new mysqli($servername, $username, $password, $dbname);
					
					// Check connection
					if ($conn->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					}
					
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

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Recent Orders</h3>

					</div>
					<table>
						<thead>
						<tr>
								<th>Username</th>
								<th>Time/Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									echo "<tr>";
									echo "<td><p>" . htmlspecialchars($_SESSION['username']) . "</p></td>";
									echo "<td>" . $row["time_date"] . "</td>";
									echo "<td><span class='status " . strtolower($row["status"]) . "'>" . ucfirst($row["status"]) . "</span></td>";
									echo "<td><button class='cancel-btn' onclick='openCancelForm(" . $row["id"] . ", \"" . $row["address"] . "\", \"" . $row["mechanic"] . "\", \"" . $row["service_name"] . "\")'>Cancel</button></td>";
									echo "</tr>";
								}
							} else {
								echo "<tr><td colspan='4'>0 results</td></tr>";
							}
							?>
						</tbody>
					</table>

					<!-- Cancel appointment form -->
					<div id="cancelPopup" class="popup">
					<button class="popup-close" onclick="closeCancelPopup()">&times;</button>
						<form method="POST" action="">
						
							<label>
								
								<input type="checkbox" name="confirm_cancel" required>
								Are you sure you want to cancel this appointment please feedback(comment)?
							</label>
							<input type="hidden" name="id" id="hiddenId" value="">
							<textarea name="comment" placeholder="Add a comment"></textarea>
							<button type="submit">Submit</button>
						</form>
						
					</div>

					<script>
						function openCancelForm(id, address, mechanic, service_name) {
							var cancelForm = document.getElementById("cancelPopup");
							var confirmCancelCheckbox = cancelForm.querySelector('[name="confirm_cancel"]');
							var commentTextarea = cancelForm.querySelector('[name="comment"]');
							var hiddenIdInput = cancelForm.querySelector('#hiddenId');
							
							hiddenIdInput.value = id;
							confirmCancelCheckbox.checked = false;
							commentTextarea.value = "";

							cancelForm.style.display = "block";
						}

						function closeCancelPopup() {
							var cancelForm = document.getElementById("cancelPopup");
							cancelForm.style.display = "none";
						}
					</script>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>