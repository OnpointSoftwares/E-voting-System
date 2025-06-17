<?php
// Start session
session_start();

// Check if OTP is set in session
if (!isset($_SESSION['otp'])) {
    header('Location: simple_otp_form.php?error=' . urlencode('No OTP found. Please request a new one.'));
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect OTP digits from the form
        $enteredOtp = '';
        for ($i = 1; $i <= 6; $i++) {
            $field = 'otp' . $i;
            if (!isset($_POST[$field]) || !is_numeric($_POST[$field])) {
                throw new Exception('Please enter a valid 6-digit OTP');
            }
            $enteredOtp .= $_POST[$field];
        }

        // Verify OTP
        if ($enteredOtp !== $_SESSION['otp']) {
            throw new Exception('Invalid OTP. Please try again.');
        }

        // OTP is valid
        // Clear OTP from session
        unset($_SESSION['otp']);
        
        // Set verified flag
        $_SESSION['otp_verified'] = true;
        
        // Redirect to success page or voting system
        header('Location: voting-system.php');
        exit();
        
    } catch (Exception $e) {
        // Redirect back with error message
        header('Location: simple_otp_form.php?error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If not a POST request, redirect to form
    header('Location: simple_otp_form.php');
    exit();
}
