<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit();
}

// Your database connection code here (replace with your actual database credentials)
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

// Retrieve the current user's ID from the session
$current_user_id = $_SESSION['user_id'];
$gmail=$_SESSION['username'];
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $contact = $_POST["contact"];
    $birthday = $_POST["birthday"];
    $country = $_POST["country"];
    $name = $_POST["name"];

    // Validate and sanitize the data (you should add proper validation)

    // Hash the password (you should use a secure hashing algorithm)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's profile in the database
    $sql = "UPDATE user SET username='$username', password='$hashed_password', contact='$contact', birthday='$birthday', country='$country', name='$name' WHERE user_id='$current_user_id'";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Profile updated successfully"); window.location.href="index.php";</script>';

    } else {
        echo '<script>alert("Error updating profile: ' . $conn->error . '"); window.location.href="";</script>';
    }
}

// Fetch the current user's information from the database
$sql = "SELECT * FROM user WHERE user_id='$current_user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();

    // Use the fetched data to pre-fill the form fields
    $username = $row['username'];
    $contact = $row['contact'];
    $birthday = $row['birthday'];
    $country = $row['country'];
    $name = $row['name'];
} else {
    echo "User not found.";
}

// Close the database connection
$conn->close();
?>

<!-- HTML form goes here with pre-filled values -->

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
        <form method="post" action="update_profile.php" id="profileForm">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" id="username" name="username" value="<?php echo $gmail; ?>" required>
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
                        <div class="tab-pane fade" id="account-info">
                            <div class="card-body pb-2">
                               
                                <div class="form-group">
                                    <label class="form-label">Birthday</label>
                                    <input type="text" class="form-control" id="birthday" name="birthday" value="<?php echo $birthday; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    <select class="custom-select" id="country" name="country" value="<?php echo $country; ?>" required>
                                        <option>India</option>
                                        <option selected>Canada</option>
                                        <option>UK</option>
                                        <option>Germany</option>
                                        <option>France</option>
                                    </select>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Contacts</h6>
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id="contact"class="form-control" name="contact" value="<?php echo $contact; ?>" required>
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