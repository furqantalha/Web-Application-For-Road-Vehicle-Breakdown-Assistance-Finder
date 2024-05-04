<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}//Connect to the MySQL database
$host ="localhost";
$username = "root";
$password = "";
$database = "breakdown_assistance";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Insert data into the mechanics table
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "INSERT INTO mechanics (name, contact, email, status) VALUES (:name, :contact, :email, :status)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo "Record inserted successfully!";
    } else {
        echo "Error inserting record.";
    }
}

// Update data in the mechanics table
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $sql = "UPDATE mechanics SET name = :name, contact = :contact, email = :email, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Record updated successfully!";
    } else {
        echo "Error updating record.";
    }
}

// Delete data from the mechanics table
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM mechanics WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record.";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View - Geo Location Data</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 633px;
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
            height: 500px;
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
        /* Styles for the table on the right side */
        .table1 {
            width: 50%;
            border-collapse: right;
            margin :right;
            margin-top: 20px;
            background-color: #fff; /* Set your desired background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .table1 th, .table1 td {
            border: 1px solid #ccc;
            color:black;
            padding: 9px;
            text-align: left;
        }

        .table1 th {
            background-color: #f2f2f2;
        }

        .table1 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table1 tr:nth-child(odd) {
            background-color: #fff;
        }
       /* Styles for the right-side navigation */
        .right {
            float: right;
            width: 250px;
            padding: 10px;
            background-color: #fff; /* Set your desired background color */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .right p {
            font-weight: bold;
        }

        .right form {
            margin-bottom: 20px;
        }

        .right input[type="text"],
        .right select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .right input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0074D9;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .right input[type="submit"]:hover {
            background-color: #0056b3;
        }



        /* Add some basic styles for the table */
       
    </style>
</head>
<body>
    <h2>Admin</h2>
    <div class="sidebar">
        <a href="#" class="logo">
            <i class='bx bxs-dashboard'></i>
            <div class="logo-name"><span>Dashboard</span></div>
        </a>
    

        <ul class="side-menu">
            <li><a href="admin_dashboard.php"><i class='bx bx-store-alt'></i> Customer Request</a></li>
            <li><a href="mechanic.php" class="logout"><i class='bx bx-log-out-circle'></i> Mechanic List</a></li>
            <li><a href="logout.php" class="logout"><i class='bx bx-log-out-circle'></i> Logout</a></li>
        </ul>
    </div>
</body>
    <body>
        <p>Mechanic List</p>

        <table class="table1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Status</th>
            </tr>

            <?php
            // Retrieve and display mechanics data (replace with your database query)
            $conn = new mysqli("localhost", "root", "", "breakdown_assistance");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM mechanics";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["contact"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No mechanics found.</td></tr>";
            }

            $conn->close();
            ?>
        </table>
        <nav class="right">
        <p>Insert Record</p>
        <form method="post">
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="text" name="contact" placeholder="Contact" required><br>
            <input type="text" name="email" placeholder="Email" required><br>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on-leave">On Leave</option>
            </select><br>
            <input type="submit" name="submit" value="Insert">
        </form>

        <p>Update Record</p>
        <form method="post">
            <input type="text" name="id" placeholder="ID" required><br>
            <input type="text" name="name" placeholder="Name" required><br>
            <input type="text" name="contact" placeholder="Contact" required><br>
            <input type="text" name="email" placeholder="Email" required><br>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on-leave">On Leave</option>
            </select><br>
            <input type="submit" name="update" value="Update">
        </form>

        <p>Delete Record</p>
        <form method="post">
            <input type="text" name="id" placeholder="ID" required><br>
            <input type="submit" name="delete" value="Delete">
        </form>
        </nav>
</body>
</html>


