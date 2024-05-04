<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: ../adminsignlogin.php");
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "breakdown_assistance";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current username from the session
$username = $_SESSION["username"];

// Assuming you have a database connection established

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // General Information Update
    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        // Assuming you have a users table in your database
        $sql = "UPDATE users SET name = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $name, $username); // 'ss' indicates string and string
        $stmt->execute();
    }

    // Password Update
    if (isset($_POST['current_password'], $_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Assuming you have a users table in your database
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $stored_password = $row['password'];

            // Verify the current password
            if (password_verify($current_password, $stored_password)) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $hashed_password, $id); // 'si' indicates string and integer
                $stmt->execute();
            } else {
                // Handle incorrect current password
                echo "Incorrect current password.";
            }
        } else {
            // Handle user not found (username doesn't exist)
            echo "User not found.";
        }
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <form method="post" action="" id="profileForm">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" >
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" id="username" name="username" value="<?php echo $username; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" id="password" name="password"class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" id="password" name="password"class="form-control">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
        <input type="submit" class="btn btn-primary" value="Update Profile"></button>&nbsp;
        <button type="button" class="btn btn-default" onclick="goBack()">Go Back</button>
        </div>
    </form>
    <script>
    function goBack() {
        window.history.back();
    }
</script>
    </div>

    </form>
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>

</html>