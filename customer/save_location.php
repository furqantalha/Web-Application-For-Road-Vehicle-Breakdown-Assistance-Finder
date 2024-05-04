<?php
require_once "db.php";
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";

// Start or resume the session
session_start();

// Check if the form is submitted
$lat = $_POST["lat"];
$lng = $_POST["lng"];
$address = $_POST["address"];
$service_name = $_POST["service_name"]; // Added this line
$shopname = isset($_POST["shopname"]) ? $_POST["shopname"] : null;

// Check if the user is logged in and if 'user_id' is set in the session
if (isset($_SESSION['user_id'], $_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Check if the user exists
    $check_user_query = "SELECT * FROM user WHERE user_id = ? AND username = ?";
    $stmt_check_user = $conn->prepare($check_user_query);

    if ($stmt_check_user) {
        $stmt_check_user->bind_param("is", $user_id, $username);
        $stmt_check_user->execute();

        $result_check_user = $stmt_check_user->get_result();

        if ($result_check_user->num_rows == 0) {
            echo "Error: User with ID $user_id and username $username does not exist.";
        } else {
            // Insert data into the database
            $sql = "INSERT INTO locations (latitude, longitude, address, user_id, username, service_name, shopname) VALUES (?, ?, ?, ?, ? , ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("ddsssss", $lat, $lng, $address, $user_id, $username, $service_name, $shopname);
            
                if ($stmt->execute()) {
                    // Retrieve the ID of the inserted row
                    $id = $stmt->insert_id;

                        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                        try {
                            // Server settings
                            $mail->isSMTP(); // Send using SMTP
                            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                            $mail->SMTPAuth = true; // Enable SMTP authentication
                            $mail->Username = 'mailid'; // SMTP username
                            $mail->Password =  ''; // SMTP password
                            $mail->SMTPSecure = 'ssl'; // Enable implicit TLS encryption
                            $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            // Recipients
                            $mail->setFrom('mailid', 'Breakdown_assistance');
                            $mail->addAddress($_SESSION['username'], 'customer'); // Add a recipient

                            // Content
                            $mail->isHTML(true);
                            $mail->Subject = "Booking and Order Update";
                            $mail->Body = "
                            <html>
                            <head>
                                <style>
                                    body {
                                        font-family: 'Arial', sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                    }
                                    .email-container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        padding: 20px;
                                        border: 1px solid #ddd;
                                    }
                                    h1 {
                                        color: #0066cc;
                                    }
                                    p {
                                        margin-bottom: 20px;
                                    }
                                    // Add more styles as needed
                                </style>
                            </head>
                            <body>
                                <div class='email-container'>
                                    <h1>Dear $username, your order no. $id</h1>
                                    <p>Your booking and order have been updated for service: $shopname. We will provide you with more details soon.</p>
                                    <p>Thank you,<br>Breakdown Assistance</p>
                                </div>
                            </body>
                            </html>
                        ";
                        $mail->isHTML(true);
                        $mail->send();
    
                            // Display a success message and redirect to the home page
                            echo '<script type="text/javascript"> 
                                    alert("Message has been sent successfully.");
                                    window.location.href = "index.php"; 
                                </script>';
                            exit();  // Exit to prevent further execution

                        } catch (Exception $e) {
                            // Display an error message and redirect to the home page
                            echo '<script type="text/javascript"> 
                                    alert("Error sending message. Please try again.");
                                    window.location.href = "index.php"; 
                                </script>';
                            exit();  // Exit to prevent further execution
                        }
                       
                        // Close the prepared statement
                        $stmt->close();
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Error: Prepare statement failed.";
                }
            }

            // Close the prepared statement for checking user existence
            $stmt_check_user->close();
        } else {
            echo "Error: Prepare statement for checking user failed.";
        }
    } else {
        echo "Error: User is not properly authenticated or session variables are not set.";
    }

    // Close the database connection
    $conn->close();
?>
