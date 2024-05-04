<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_c.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            margin: 300px;
            margin-top: 20px;  
            margin-bottom: 20px; 
        }

        .sidebar {
            width: 250px;
            height: 100%;
            background-color: #333;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }

        .logo i {
            font-size: 24px;
        }

        .logo-name {
            margin-left: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .side-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .side-menu li {
            margin-bottom: 10px;
        }

        .side-menu a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
        }

        .side-menu a:hover {
            background-color: #444;
        }

        .logout {
            background-color: #d9534f;
        }

        .logout:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <div class="sidebar">
        <a href="#" class="logo">
            <i class='bx bxs-dashboard'></i>
            <div class="logo-name"><span>Dashboard</span></div>
        </a>

        <ul class="side-menu">
            <li><a href="Emergency_Request.php"><i class='bx bx-store-alt'></i>Emergency Request</a></li>
            <li><a href="logout.php" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a></li>
        </ul>
    </div>
</body>
</html>
