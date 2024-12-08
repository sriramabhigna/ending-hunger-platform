<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'C:\xampp\htdocs\logg\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\logg\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\logg\PHPMailer\src\SMTP.php';

// Database connection settings
$host = "localhost";
$user = "root";
$pass = "";
$port = 3306;

// Connect to the donars database
$db1 = "donars";
$conn1 = new mysqli($host, $user, $pass, $db1, $port);
if ($conn1->connect_error) {
    die("Failed to connect to the donars database: " . $conn1->connect_error);
}

// Connect to the only_hunger database
$db2 = "only_hunger_db";
$conn2 = new mysqli($host, $user, $pass, $db2, $port);
if ($conn2->connect_error) {
    die("Failed to connect to the only_hunger database: " . $conn2->connect_error);
}

// Check if form is submitted and handle accordingly
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_donar'])) {
        saveDonor($conn1, $conn2);
    } elseif (isset($_POST['delete_donar'])) {
        deleteDonor($conn1, $conn2);
    } elseif (isset($_POST['update_donar'])) {
        updateDonor($conn1);
    }
}

// Function to save a new donor and notify volunteers
function saveDonor($conn1, $conn2) {
    $name = mysqli_real_escape_string($conn1, $_POST['name']);
    $email = mysqli_real_escape_string($conn1, $_POST['email']);
    $phone = mysqli_real_escape_string($conn1, $_POST['phone']);
    $food = mysqli_real_escape_string($conn1, $_POST['food']);
    $address = mysqli_real_escape_string($conn1, $_POST['address']);
    $quantity = mysqli_real_escape_string($conn1, $_POST['quantity']);
    $organisation = mysqli_real_escape_string($conn1, $_POST['organisation']);

    if (empty($name) || empty($email) || empty($phone) || empty($food) || empty($address) || empty($quantity) || empty($organisation)) {
        $_SESSION['message'] = "All fields are required";
        header("Location: donar-create.php");
        exit(0);
    }

    $sql = "INSERT INTO donardetails (name, email, phone, food, address, quantity, organisation) 
            VALUES ('$name', '$email', '$phone', '$food', '$address', '$quantity', '$organisation')";
    
    if ($conn1->query($sql) === TRUE) {
        notifyVolunteers($conn2, $name, $email, $phone, $food, $address, $quantity, $organisation);
        header("Location: index5.html");
        exit(); 
    } else {
        $_SESSION['message'] = "Donor Not Created: " . $conn1->error;
        header("Location: donar-create.php");
        exit(0);
    }
}

// Function to update a donor
function updateDonor($conn1) {
    $donar_id = mysqli_real_escape_string($conn1, $_POST['donar_id']);
    $name = mysqli_real_escape_string($conn1, $_POST['name']);
    $email = mysqli_real_escape_string($conn1, $_POST['email']);
    $phone = mysqli_real_escape_string($conn1, $_POST['phone']);
    $food = mysqli_real_escape_string($conn1, $_POST['food']);
    $address = mysqli_real_escape_string($conn1, $_POST['address']);
    $quantity = mysqli_real_escape_string($conn1, $_POST['quantity']);
    $organisation = mysqli_real_escape_string($conn1, $_POST['organisation']);

    if (empty($name) || empty($email) || empty($phone) || empty($food) || empty($address) || empty($quantity) || empty($organisation)) {
        $_SESSION['message'] = "All fields are required";
        header("Location: donar.php");
        exit(0);
    }

    $query = "UPDATE donardetails SET name='$name', email='$email', phone='$phone', food='$food', address='$address', quantity='$quantity', organisation='$organisation' WHERE id='$donar_id'";
    $query_run = mysqli_query($conn1, $query);

    if ($query_run) {
        $_SESSION['message'] = "Donor Updated Successfully";
        header("Location: donar.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Donor Not Updated: " . $conn1->error;
        header("Location: donar.php");
        exit(0);
    }
}

// Function to delete a donor
function deleteDonor($conn1, $conn2) {
    if (isset($_POST['delete_donar'])) {
        $donar_id = mysqli_real_escape_string($conn1, $_POST['delete_donar']);

        // Check if donor ID is numeric to prevent SQL injection
        if (!is_numeric($donar_id)) {
            $_SESSION['message'] = "Invalid Donor ID";
            header("Location: donar.php");
            exit(0);
        }

        // Get the donor details before deletion
        $query = "SELECT * FROM donardetails WHERE id='$donar_id'";
        $result = mysqli_query($conn1, $query);
        $donor = mysqli_fetch_assoc($result);

        if ($donor) {
            // Insert into the history table
            $insert_query = "INSERT INTO deleted_donors_history (name, email, phone, food, address, quantity, organisation) 
                             VALUES ('{$donor['name']}', '{$donor['email']}', '{$donor['phone']}', '{$donor['food']}', '{$donor['address']}', '{$donor['quantity']}', '{$donor['organisation']}')";
            mysqli_query($conn2, $insert_query);

            // Delete the donor
            $delete_query = "DELETE FROM donardetails WHERE id='$donar_id'";
            $delete_run = mysqli_query($conn1, $delete_query);

            if ($delete_run) {
                if (mysqli_affected_rows($conn1) > 0) {
                    $_SESSION['message'] = "Donor Deleted Successfully and saved to history";
                } else {
                    $_SESSION['message'] = "Donor ID Not Found";
                }
            } else {
                $_SESSION['message'] = "Error: " . mysqli_error($conn1);
            }
        } else {
            $_SESSION['message'] = "Donor ID Not Found";
        }

        header("Location: donar.php");
        exit(0);
    }
}

// Function to notify all volunteers
function notifyVolunteers($conn2, $name, $email, $phone, $food, $address, $quantity, $organisation) {
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sriramabhigna@gmail.com'; // Your Gmail address
        $mail->Password = 'atpr zkun ahph judq'; // Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender
        $mail->setFrom('sriramabhigna@gmail.com', 'Only Hunger Project');

        // Get all volunteers' emails
        $sql = "SELECT email FROM volunteers";
        $result = $conn2->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $volunteer_email = $row['email'];
                $mail->addAddress($volunteer_email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Donor Added to Only Hunger Project';
                $mail->Body = "
                    <h3>Dear Volunteer,</h3>
                    <p>A new donor has been added to the Only Hunger Project. Here are the details:</p>
                    <p><strong>Donor Name:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Phone:</strong> $phone</p>
                    <p><strong>Food Type:</strong> $food</p>
                    <p><strong>Address:</strong> $address</p>
                    <p><strong>Quantity:</strong> $quantity</p>
                    <p><strong>Organisation:</strong> $organisation</p>
                    <p>Please check the details and take necessary actions.</p>
                    <p>Best regards,<br>Only Hunger Project Team</p>
                ";

                // Send email
                $mail->send();
                $mail->clearAddresses(); // Clear all addresses after each email
            }
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
