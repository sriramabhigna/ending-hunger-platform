<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your custom CSS -->
    <title>Send OTP</title>
    <style>
        /* Your existing CSS styles */
    </style>
</head>
<body>
    <!-- <?php include 'navbar.php'; ?> -->
    <div class="form-container">
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require 'vendor/autoload.php';

        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            // Capture form data
            $recipientEmail = $_POST['email'];
            $volunteerName = isset($_POST['volunteer_name']) ? $_POST['volunteer_name'] : '';
            $volunteerPhone = isset($_POST['volunteer_phone']) ? $_POST['volunteer_phone'] : '';
            $messageContent = isset($_POST['message']) ? $_POST['message'] : '';

            $otp = rand(100000, 999999);  // Generate a random 6-digit OTP

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $recipientEmail;

            sendOTP($recipientEmail, $otp, $volunteerName, $volunteerPhone, $messageContent);
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otp'])) {
            if ($_POST['otp'] == $_SESSION['otp']) {
                // OTP verified successfully
                deleteDonorAndSaveToHistory($_SESSION['email']);
                echo 'OTP verified successfully! Donor details have been deleted and archived.';
            } else {
                echo 'Invalid OTP. Please try again.';
                displayOTPForm();
            }
        } else {
            displayEmailForm();
        }

        function sendOTP($recipientEmail, $otp, $volunteerName, $volunteerPhone, $messageContent) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'sriramabhigna@gmail.com';  // SMTP username
                $mail->Password = 'atpr zkun ahph judq';  // SMTP password (use an app-specific password for Gmail)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;  // TCP port to connect to

                // Recipients
                $mail->setFrom('sriramabhigna@gmail.com', 'Your Name');
                $mail->addAddress($recipientEmail);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code and Volunteer Details';
                $mail->Body = "
                    <p>Hello,</p>
                    <p>Your OTP code is: <strong>$otp</strong></p>
                    <p>Volunteer Details:</p>
                    <ul>
                        <li>Name: $volunteerName</li>
                        <li>Phone: $volunteerPhone</li>
                    </ul>
                    <p>Message from Volunteer:</p>
                    <p>$messageContent</p>
                    <p>Please use this OTP to confirm your donation.</p>
                    <p>Thank you!</p>
                ";
                $mail->AltBody = "Your OTP code is: $otp\nVolunteer Details:\nName: $volunteerName\nPhone: $volunteerPhone\nMessage: $messageContent\nPlease use this OTP to confirm your donation.\nThank you!";

                $mail->send();
                echo 'OTP has been sent. Please check your email.';
                displayOTPForm();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        function deleteDonorAndSaveToHistory($email) {
            // Database connections
            $donorsConn = new mysqli("localhost", "root", "", "donars");
            $onlyHungerConn = new mysqli("localhost", "root", "", "only_hunger_db");

            if ($donorsConn->connect_error || $onlyHungerConn->connect_error) {
                die("Connection failed: " . ($donorsConn->connect_error ?: $onlyHungerConn->connect_error));
            }

            // Fetch donor details from the `donardetails` table in the `Donors` database
            $query = "SELECT * FROM `donardetails` WHERE email = ?";
            $stmt = $donorsConn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Move donor details to `deleted_donors_history` table in the `only_hunger` database
                while ($row = $result->fetch_assoc()) {
                    $insertQuery = "INSERT INTO `deleted_donors_history` (name, email, phone, food, address, quantity, organisation, deleted_at) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $insertStmt = $onlyHungerConn->prepare($insertQuery);
                    
                    // Check if the number of parameters matches the placeholders
                    if (!$insertStmt) {
                        die("Prepare failed: " . $onlyHungerConn->error);
                    }
                    
                    // Bind parameters (make sure the number and types match the placeholders)
                    $insertStmt->bind_param("sssssss", 
                                            $row['name'], 
                                            $row['email'], 
                                            $row['phone'], 
                                            $row['food'],
                                            $row['address'], 
                                            $row['quantity'],
                                            $row['organisation']);
                    if (!$insertStmt->execute()) {
                        die("Execute failed: " . $insertStmt->error);
                    }
                }

                // Delete donor details from the `donardetails` table in the `Donors` database
                $deleteQuery = "DELETE FROM `donardetails` WHERE email = ?";
                $deleteStmt = $donorsConn->prepare($deleteQuery);
                $deleteStmt->bind_param("s", $email);
                $deleteStmt->execute();
            }

            // Close the database connections
            $stmt->close();
            $donorsConn->close();
            $onlyHungerConn->close();
        }

        function displayEmailForm() {
            echo '<form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Donor Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="volunteer_name" class="form-label">Volunteer Name:</label>
                        <input type="text" id="volunteer_name" name="volunteer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="volunteer_phone" class="form-label">Volunteer Phone Number:</label>
                        <input type="tel" id="volunteer_phone" name="volunteer_phone" class="form-control" pattern="[0-9]{10}" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message:</label>
                        <textarea id="message" name="message" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send OTP</button>
                  </form>';
        }

        function displayOTPForm() {
            echo '<form action="" method="post">
                    <label for="otp" class="form-label">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" class="form-control" required>
                    <button type="submit" class="btn btn-primary mt-3">Verify OTP</button>
                  </form>';
        }
        ?>
    </div>
</body>
</html>
