<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Log the start of the script
error_log("=== OTP Form Script Started ===");

use AfricasTalking\SDK\AfricasTalking;

// Verify vendor autoload exists
if (!file_exists('vendor/autoload.php')) {
    die("Error: vendor/autoload.php not found. Please run 'composer install'");
}

require_once('vendor/autoload.php');

// Start session with error handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("Session started. Session data: " . print_r($_SESSION, true));

// Database connection
try {
    $con = mysqli_connect("localhost", "root", "", "voting");
    if (mysqli_connect_errno()) {
        throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    
    if (!isset($_POST['phone'])) {
        throw new Exception("Phone number is required");
    }
    
    $phone = trim($_POST['phone']);
    
    // Include voter login data to process OTP
    if (!file_exists("includes/voter_login_data.php")) {
        throw new Exception("Voter login data file not found");
    }
    
    include "includes/voter_login_data.php";
    
    // Verify if OTP was generated and stored in session
    if (!isset($_SESSION['otp']) || empty($_SESSION['otp'])) {
        throw new Exception("OTP not generated. Please try again.");
    }
    
    // Format phone number for Africa's Talking
    $to = $phone;
    if (substr($to, 0, 1) === '0') {
        $to = '+254' . substr($to, 1);
    } elseif (substr($to, 0, 5) !== '+2547' && substr($to, 0, 5) !== '+2541') {
        $to = '+254' . ltrim($to, '+');
    }
    
    // Initialize Africa's Talking with sandbox credentials
    $username = "sandbox";  // Use 'sandbox' for testing
    $apiKey = "atsk_15816eb5aabd188032279125489cefad2b764fb1b62db1800392a807bd9ad444cd7d6636";
    $message = "Your OTP is " . $_SESSION['otp'];
    
    try {
        $at = new AfricasTalking($username, $apiKey);
        $sms = $at->sms();
        
        // For sandbox, use a valid test number format
        if ($username === 'sandbox') {
            // Format must be in the format: +2547XXXXXXXX or +2541XXXXXXXX
            if (!preg_match('/^\+254[17]\d{8}$/', $to)) {
                throw new Exception("For sandbox testing, please use a valid test number in the format +2547XXXXXXXX or +2541XXXXXXXX");
            }
            
            // In sandbox, you can only send to whitelisted numbers
            $message = "[TEST] " . $message;
        }
        
        // Send SMS - don't specify 'from' parameter to use default sender ID
        $result = $sms->send([
            'to'      => $to,
            'message' => $message
            // Remove 'from' parameter to use default sender ID
        ]);
        
        // Log the result for debugging
        error_log("SMS Send Result: " . print_r($result, true));
        
        // Check if SMS was sent successfully
        if (isset($result['status']) && $result['status'] === 'success' && 
            (!isset($result['data']->SMSMessageData->Message) || 
             $result['data']->SMSMessageData->Message !== 'InvalidSenderId')) {
            echo "
            <script>
            alert('OTP has been sent to your phone number');
            window.location.href = 'otp_verification.php';
            </script>";
            exit();
        } else {
            $errorMsg = isset($result['data']->SMSMessageData->Message) 
                ? $result['data']->SMSMessageData->Message 
                : 'Unknown error';
            throw new Exception("Failed to send OTP. Error: " . $errorMsg);
        }
    } catch (Exception $e) {
        error_log("SMS Sending Error: " . $e->getMessage());
        throw new Exception("Failed to send OTP: " . $e->getMessage());
    }
    
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    error_log("OTP Error: " . $errorMsg);
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Output error in a user-friendly way
    echo "<html><head><title>Error</title><style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
        .error-box { 
            background: #ffebee; 
            border: 1px solid #ef9a9a; 
            padding: 15px; 
            border-radius: 4px; 
            margin: 20px 0;
            color: #c62828;
        }
        pre { 
            background: #f5f5f5; 
            padding: 10px; 
            border-radius: 4px; 
            overflow-x: auto;
        }
    </style></head>
    <body>
        <h2>An Error Occurred</h2>
        <div class='error-box'>" . 
            nl2br(htmlspecialchars($errorMsg)) . 
        "</div>
        <p><a href='javascript:window.history.back()'>Go Back</a></p>
        <h3>Debug Information:</h3>
        <pre>" . 
            "Time: " . date('Y-m-d H:i:s') . "\n" .
            "Error: " . htmlspecialchars($errorMsg) . "\n" .
            "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n" .
            "POST Data: " . print_r($_POST, true) . "\n" .
            "Session Data: " . print_r($_SESSION, true) .
        "</pre>
    </body>
    </html>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="css/style.css">
    <style type="text/css">
        #resend
        {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Online Voting System</h1>
        </div>
        <div class="form">
            <h4>OTP Verification</h4>
            <form action="voting-system.php" method="POST">
                <label class="label">OTP:</label>
                <input type="name" name="otp" class="input" placeholder="Enter OTP" required>

                <button class="button">Verify</button>
                <center><div class="timer"></div><?php echo "<a id='resend' href='includes/resend_otp.php?phone=$_SESSION[phone]'>Resend OTP</a>";?></center>
                <p class="error"><?php echo $_SESSION['error']; ?></p>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        var timer = document.getElementsByClassName("timer");
        var link = document.getElementById("resend");
        sec=30;
         setInterval(() => {
            timer["0"].innerHTML="00:"+sec;
            sec--;
            if (sec<0) {
                timer["0"].style.display="none";
                link.style.display="block";
            }
        }, 1000)
    </script>
</body>

</html>