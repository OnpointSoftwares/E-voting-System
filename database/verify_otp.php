<?php
session_start();

if(isset($_POST['submit_otp'])) {
    $user_otp = $_POST['otp'];

    if($user_otp == $_SESSION['otp']) {
        // OTP matches, login successful
        $_SESSION['userLogin'] = 1;
        // Clear OTP session variable if needed
        unset($_SESSION['otp']);
        
        echo "<script>alert('Login successful!'); location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid OTP, try again.');</script>";
    }
}
?>

<form method="post">
    <label>Enter OTP sent to your phone:</label>
    <input type="text" name="otp" required>
    <input type="submit" name="submit_otp" value="Verify OTP">
</form>
