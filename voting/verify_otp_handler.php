<?php
include "includes/all-select-data.php";
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start output buffering
ob_start();

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo $_SESSION['password'];
echo $_SESSION['phone'];

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Verify required session data

    if (!isset($_SESSION['phone']) || empty($_SESSION['phone'])) {
        throw new Exception('Phone number not found in session.');
    }

    // Get submitted OTP - now handling 4-digit codes
    $submittedOtp = '';
    for ($i = 1; $i <= 6; $i++) {
        $field = 'otp' . $i;
        if (!isset($_POST[$field]) || !is_numeric($_POST[$field])) {
            throw new Exception('Invalid Password format. Please enter all 6 digits.');
        }
        $submittedOtp .= $_POST[$field];
    }
    $sql="SELECT * FROM register WHERE phone=".$_SESSION['phone'] ." AND password=".$submittedOtp;
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_array($result);
    $count=mysqli_num_rows($result);
    // Verify OTP
    if ($count==0) {
        error_log("Password Mismatch - Submitted: $submittedOtp, Expected: " . ($_SESSION['password'] ?? 'Not set'));
        throw new Exception('Invalid Password');
    }
    // OTP is valid
    $response['success'] = true;
    $response['message'] = 'Password verified successfully!';
    $response['redirect'] = '../index.php';
    
    // Clear OTP from session after successful verification
    unset($_SESSION['password']);
    
    // Set verified flag in session
    $_SESSION['password_verified'] = true;
    $_SESSION['userLogin']=1;
    header('Location: ballet.php');
    exit();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('OTP Verification Error: ' . $e->getMessage());
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Flush output buffer
ob_end_flush();
