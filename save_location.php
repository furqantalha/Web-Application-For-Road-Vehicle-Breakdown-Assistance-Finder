<?php
require_once "db.php";

// Start or resume the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $address = $_POST["address"];
    
    // Check if the user is logged in and if 'user_id' is set in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Check if the user exists
        $check_user_query = "SELECT * FROM user WHERE user_id = '$user_id'";
        $result = $conn->query($check_user_query);

        if ($result->num_rows == 0) {
            echo "Error: User with ID $user_id does not exist.";
        } else {
            // Insert data into the database
            $sql = "INSERT INTO locations (latitude, longitude, address, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) { // Check if the prepare statement was successful
                $stmt->bind_param("ddsi", $lat, $lng, $address, $user_id);

                if ($stmt->execute()) {
                    // Display a success message using JavaScript
                    echo '<script type="text/javascript">
                        alert("Location data saved successfully! We will contact you soon.");
                        window.location.href = "#";
                    </script>';
                } else {
                    echo "Error: " . $stmt->error;
                }
                // Close the database connection
                $stmt->close();
            } else {
                echo "Error: Prepare statement failed.";
            }
        }
    } else {
        echo "Error: User is not properly authenticated or session variables are not set.";
    }
    $conn->close();
}
?>
