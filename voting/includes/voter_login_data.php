<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Check if database connection exists
if (!isset($con) || !($con instanceof mysqli)) {
    die("Database connection not established");
}

// Check if phone number is provided and valid
if (!isset($phone) || empty(trim($phone))) {
    die("Phone number is required");
}

// Sanitize phone number
$phone = trim($phone);
$password = trim($password);
// Check if resend flag is set
$isResend = isset($resend) && $resend == 1;

try {
    // Prepare and execute query with prepared statement
    $reg_query = "SELECT * FROM register WHERE phone = ?";
    $stmt = $con->prepare($reg_query);
    if (!$stmt) {
        throw new Exception("Database query preparation failed: " . $con->error);
    }
    
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        // No user found with this phone number
        echo "
        <script>
        alert('Phone number not registered');
        window.history.back();
        </script>";
        exit();
    }
    
    // Set session variables
    $_SESSION['fname'] = $result['fname'];
    $_SESSION['lname'] = $result['lname'];
    $_SESSION['idnum'] = $result['idnum'] ?? $result['lname']; // Fixed: was setting idnum to lname
    $_SESSION['phone'] = $result['phone'];
    $_SESSION['idcard'] = $result['idcard'];
    $_SESSION['verify'] = $result['verify'];
    $_SESSION['status'] = $result['status'];
    $_SESSION['password'] = $result['password'];
    // Check if user is verified
    if ($_SESSION['verify'] !== "yes") {
        echo "
        <script>
        alert('Your account has not been verified by the administrator');
        window.location.href = 'index.php';
        </script>";
        exit();
    }
    
    // Generate and store OTP
    $otp = rand(1000, 9999); // 4-digit OTP
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_attempts'] = 0; // Track OTP attempts
    $_SESSION['otp_generated_at'] = time(); // Track when OTP was generated
    
    // Log OTP generation (for debugging, remove in production)
    error_log("Generated OTP " . $otp . " for phone " . $phone);
    
    // Let otpform.php handle the SMS sending
    // We just need to set the session variables and return
    
} catch (Exception $e) {
    error_log("Error in voter_login_data.php: " . $e->getMessage());
    echo "
    <script>
    alert('An error occurred. Please try again later.');
    window.history.back();
    </script>";
    exit();
}
