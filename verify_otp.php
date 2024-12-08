<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOtp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        // OTP is verified successfully
        echo 'OTP verified successfully! Redirecting...';
        header('Location: donar.php');
        exit; // Exit to prevent further code execution
    } else {
        // OTP is invalid
        echo 'Invalid OTP. Please try again.';
        echo '<form action="verify_otp.php" method="post">
                <label for="otp">Enter OTP:</label>
                <input type="text" id="otp" name="otp" required>
                <button type="submit">Verify OTP</button>
              </form>';
    }
}
?>
