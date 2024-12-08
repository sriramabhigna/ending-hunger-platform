<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\logg\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\logg\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\logg\PHPMailer\src\SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "only_hunger_db";

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $sql = "SELECT full_name FROM volunteers WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $full_name = $row['full_name'];

        $new_password = generateRandomPassword(8);
        $hashed_password = md5($new_password);

        $update_sql = "UPDATE volunteers SET password='$hashed_password' WHERE email='$email'";
        if ($conn->query($update_sql) === TRUE) {
            if (sendEmail($full_name, $email, $new_password)) {
                header('Location: lol.php');
                exit();
            } else {
                echo "Error sending email.";
            }
        } else {
            echo "Error updating password for $email: " . $conn->error;
        }
    } else {
        echo "No volunteer found with that email.";
    }

    $conn->close();
} else {
    echo "Invalid request. Please use the form to submit your email.";
}

function generateRandomPassword($length = 2) {
    $characters = '012';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function sendEmail($full_name, $email, $password) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sriramabhigna@gmail.com';
        $mail->Password = 'atpr zkun ahph judq';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('sriramabhigna@gmail.com', 'Only Hunger Project');
        $mail->addAddress($email, $full_name);

        $mail->isHTML(true);
        $mail->Subject = 'Your Volunteer Credentials for Only Hunger Project';
        $mail->Body = "
            <h3>Hello $full_name,</h3>
            <p>Thank you for volunteering for the Only Hunger project. Here are your login credentials:</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Password:</strong> $password</p>
            <p>Please keep this information safe and use it to log in to our system.</p>
            <p>Best regards,<br>Only Hunger Project Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

