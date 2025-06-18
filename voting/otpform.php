<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/otp_errors.log');

use AfricasTalking\SDK\AfricasTalking;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Start output buffering to prevent any accidental output
ob_start();
// Log the start of the script
error_log("=== OTP Form Script Started ===");

try {
    // Verify vendor autoload exists
    $vendorAutoload = __DIR__ . '/vendor/autoload.php';
    if (!file_exists($vendorAutoload)) {
        throw new Exception("Vendor autoload file not found at: $vendorAutoload. Please run 'composer install' in the voting directory.");
    }
    require_once($vendorAutoload);

    // Start session with error handling
    if (session_status() === PHP_SESSION_NONE) {
        if (!session_start()) {
            throw new Exception("Failed to start session");
        }
    }

    // Log session status
    error_log("Session started. Session ID: " . session_id());
    error_log("Session data: " . print_r($_SESSION, true));
    error_log("POST data: " . print_r($_POST, true));




    // Database connection
    error_log("Attempting to connect to database...");
    $con = mysqli_connect("localhost","dxusvdvu_voting","Koskey@2024","dxusvdvu_voting");

    if (!$con) {
        throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    error_log("Database connection successful");
    
    // Set charset to ensure proper character encoding
    if (!mysqli_set_charset($con, 'utf8mb4')) {
        throw new Exception("Error setting charset: " . mysqli_error($con));
    }
    
    // Check for required phone number
    if (!isset($_POST['phone'])) {
        error_log("No phone number provided in POST data");
        throw new Exception("Phone number is required");
    }
    
    $phone = trim($_POST['phone']);
    error_log("Processing phone number: " . $phone);
    
    // Store phone in session for later use
    $_SESSION['phone'] = $phone;
    
    // Include voter login data to process OTP
    $voterLoginFile = __DIR__ . "/includes/voter_login_data.php";
    if (!file_exists($voterLoginFile)) {
        error_log("Voter login data file not found at: " . $voterLoginFile);
        throw new Exception("Voter login data file not found at: " . $voterLoginFile);
    }
    
    error_log("Including voter login data file: " . $voterLoginFile);
    include $voterLoginFile;
    error_log("Successfully included voter login data file");
    
    // Verify if OTP was generated and stored in session
    if (!isset($_SESSION['otp']) || empty($_SESSION['otp'])) {
        error_log("OTP not found in session. Session data: " . print_r($_SESSION, true));
        throw new Exception("OTP not generated. Please try again.");
    }
    error_log("OTP found in session: " . $_SESSION['otp']);
    
    // Format phone number for Africa's Talking
    $to = $phone;
    if (substr($to, 0, 1) === '0') {
        $to = '+254' . substr($to, 1);
    } elseif (substr($to, 0, 5) !== '+2547' && substr($to, 0, 5) !== '+2541') {
        $to = '+254' . ltrim($to, '+');
    }
    
    // Initialize Africa's Talking with sandbox credentials
    $username = "MBNS";  // Use 'sandbox' for testing
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
        $smsFailed = false;
        $isBlacklisted = false;
        $errorMessage = '';
        
        if (isset($result['data']->SMSMessageData->Recipients[0])) {
            $recipient = $result['data']->SMSMessageData->Recipients[0];
            $isBlacklisted = ($recipient->status === 'UserInBlacklist');
            $errorMessage = $recipient->message ?? 'Unknown error';
            $smsFailed = ($recipient->status !== 'Success');
        }
        
        // If SMS failed, try to send email
        if ($smsFailed) {
            /*
            try {
            $sql = "SELECT * FROM register WHERE phone = '$phone'";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];
            }
                $mail = new PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'vincentbettoh@gmail.com'; // Replace with your Gmail
                $mail->Password   = 'lrgb gvyg cymh raao';    // Replace with App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                
                // Recipients
                $mail->setFrom('noreply@yourdomain.com', 'Solypark Opinion Poll System');
                $mail->addAddress($email, 'User'); // Replace with user's email
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = 'Your OTP code is: <strong>' . $_SESSION['otp'] . '</strong>';
                $mail->AltBody = 'Your OTP code is: ' . $_SESSION['otp'];
                
                $mail->send();
                error_log('OTP sent via email to ' . $email);
                
            } catch (Exception $e) {
                error_log('Email could not be sent. Error: ' . $mail->ErrorInfo);
                throw new Exception('Failed to send OTP via SMS or Email. Please contact support.');
            }
            */
        }
        
        // Continue if we're in test mode or if SMS was sent successfully
        if ($isTestMode || !$smsFailed || $isBlacklisted) {
            // Generate OTP (4 digits for testing)
    $otp = rand(1000, 9999);
    $_SESSION['otp'] = (string)$otp;
    
    // Log the generated OTP
    error_log("Generated OTP " . $_SESSION['otp'] . " for phone " . $phone);
            $_SESSION['phone'] = $phone;
            
            // Log the OTP for testing
            $message = $isBlacklisted ? "User is blacklisted. " : "";
            $message .= "OTP " . $_SESSION['otp'] . " for phone " . $phone;
            error_log($message);
            
            // If blacklisted, store a message to show the user
            if ($isBlacklisted) {
                $_SESSION['otp_message'] = "We couldn't send an SMS to your number. Please check your email for the OTP.";
            }
            
            // Redirect to the OTP verification form
            header('Location: simple_otp_form.php');
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
    // Get all error information
    $errorMsg = $e->getMessage();
    $errorFile = $e->getFile();
    $errorLine = $e->getLine();
    $trace = $e->getTraceAsString();
    
    // Log detailed error information
    error_log("=== CRITICAL ERROR ===");
    error_log("Message: " . $errorMsg);
    error_log("File: " . $errorFile . " (Line: " . $errorLine . ")");
    error_log("Stack Trace: " . $trace);
    error_log("POST Data: " . print_r($_POST, true));
    error_log("SESSION Data: " . print_r($_SESSION, true));
    error_log("SERVER Data: " . print_r($_SERVER, true));
    error_log("=== END ERROR ===");
    
    // Clear any previous output
    if (ob_get_level() > 0) {
        ob_end_clean();
    }
    
    // Output error in a user-friendly way
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - OTP Verification</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                line-height: 1.6; 
                padding: 20px;
                max-width: 800px;
                margin: 0 auto;
                color: #333;
            }
            .error-container { 
                background: #fff; 
                border: 1px solid #e0e0e0; 
                border-radius: 8px;
                padding: 25px;
                margin: 20px 0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .error-header {
                color: #d32f2f;
                border-bottom: 1px solid #ffcdd2;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .error-message {
                background: #ffebee;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
                border-left: 4px solid #d32f2f;
            }
            .debug-info {
                background: #f5f5f5;
                padding: 15px;
                border-radius: 4px;
                font-family: monospace;
                white-space: pre-wrap;
                word-wrap: break-word;
                font-size: 13px;
                margin-bottom: 20px;
                max-height: 300px;
                overflow-y: auto;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #1976d2;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                font-size: 14px;
                transition: background 0.3s;
            }
            .btn:hover {
                background: #1565c0;
            }
            .btn-secondary {
                background: #757575;
            }
            .btn-secondary:hover {
                background: #616161;
            }
            .btn-container {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }
            @media (max-width: 600px) {
                .btn-container {
                    flex-direction: column;
                }
                .btn {
                    width: 100%;
                    text-align: center;
                }
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1 class="error-header">An Error Occurred</h1>
            
            <div class="error-message">
                <strong>Error:</strong> <?php echo htmlspecialchars($errorMsg); ?>
                <br>
                <small>File: <?php echo htmlspecialchars($errorFile); ?> (Line: <?php echo $errorLine; ?>)</small>
            </div>
            
            <h3>Debug Information:</h3>
            <div class="debug-info">
                <strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?>
                
                <?php if (!empty($_POST)): ?>
                    <h4>POST Data:</h4>
                    <?php echo htmlspecialchars(print_r($_POST, true)); ?>
                <?php endif; ?>
                
                <?php if (!empty($_SESSION)): ?>
                    <h4>Session Data:</h4>
                    <?php echo htmlspecialchars(print_r($_SESSION, true)); ?>
                <?php endif; ?>
                
                <h4>Server Information:</h4>
                PHP Version: <?php echo phpversion(); ?>
                
                <h4>Included Files:</h4>
                <?php echo implode("\n", get_included_files()); ?>
                
                <h4>Error Log:</h4>
                <?php 
                $logFile = __DIR__ . '/otp_errors.log';
                if (file_exists($logFile)) {
                    echo nl2br(htmlspecialchars(file_get_contents($logFile)));
                } else {
                    echo "Log file not found at: " . htmlspecialchars($logFile);
                }
                ?>
            </div>
            
            <div class="btn-container">
                <a href="javascript:window.history.back()" class="btn">Go Back</a>
                <a href="index.php" class="btn btn-secondary">Return to Home</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solypark Opinion Poll System</title>
    <link rel="stylesheet" href="css/style.css">
    <style type="text/css">
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .otp-instruction {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .otp-form {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin: 20px 0 30px;
            gap: 10px;
        }
        
        .otp-input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .otp-input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .otp-inputs span {
            display: flex;
            align-items: center;
            font-size: 20px;
            color: #7f8c8d;
        }
        
        .verify-button {
            width: 100%;
            padding: 15px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }
        
        .verify-button:hover {
            background-color: #27ae60;
        }
        
        .resend-section {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .resend-section a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .resend-section a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
        }
        
        @media (max-width: 480px) {
            .otp-input {
                width: 35px;
                height: 45px;
                font-size: 20px;
            }
            
            .otp-inputs {
                gap: 5px;
            }
        }
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Solypark Opinion Poll System</h1>
        </div>
        <div class="form">
            <div class="form-header">
                <h2>OTP Verification</h2>
                <p class="otp-instruction">We've sent a 6-digit verification code to your phone number ending with <?php echo substr($_SESSION['phone'] ?? '', -4); ?></p>
            </div>
            <form action="voting-system.php" method="POST" class="otp-form">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP Code</label>
                    <div class="otp-inputs">
                        <input type="text" name="otp1" class="form-control otp-input" maxlength="1" pattern="\d" inputmode="numeric" required autofocus>
                        <input type="text" name="otp2" class="form-control otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                        <input type="text" name="otp3" class="form-control otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                        <span>-</span>
                        <input type="text" name="otp4" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                        <input type="text" name="otp5" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                        <input type="text" name="otp6" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                    </div>
                    <input type="hidden" name="otp" id="fullOtp">
                </div>

                <button type="submit" class="verify-button">
                    <span class="button-text">Verify OTP</span>
                    <span class="button-loader" style="display: none;">Verifying...</span>
                </button>
                
                <div class="resend-section">
                    <div class="timer">Resend OTP in <span id="countdown">30</span>s</div>
                    <div id="resend" style="display: none;">
                        Didn't receive code? <a href="includes/resend_otp.php?phone=<?php echo urlencode($_SESSION['phone'] ?? ''); ?>">Resend OTP</a>
                    </div>
                </div>
                
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?php 
                            echo htmlspecialchars($_SESSION['error']);
                            unset($_SESSION['error']); // Clear the error after displaying
                        ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const form = document.querySelector('.otp-form');
            const fullOtpInput = document.getElementById('fullOtp');
            const verifyButton = form.querySelector('button[type="submit"]');
            const buttonText = document.querySelector('.button-text');
            const buttonLoader = document.querySelector('.button-loader');
            
            // Timer functionality
            let timeLeft = 30;
            const countdownElement = document.getElementById('countdown');
            const resendLink = document.getElementById('resend');
            
            // Auto-focus first input
            otpInputs[0].focus();
            
            // Handle OTP input
            otpInputs.forEach((input, index) => {
                // Allow only numbers
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    if (value && !/^\d+$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Move to next input on number input
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    // Update the hidden input with full OTP
                    updateFullOtp();
                });
                
                // Handle backspace
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
                
                // Handle paste
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text/plain').trim();
                    if (/^\d+$/.test(pasteData)) {
                        const digits = pasteData.split('');
                        digits.forEach((digit, i) => {
                            if (index + i < otpInputs.length) {
                                otpInputs[index + i].value = digit;
                            }
                        });
                        updateFullOtp();
                    }
                });
            });
            
            // Update hidden input with full OTP
            function updateFullOtp() {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value || '';
                });
                fullOtpInput.value = otp;
            }
            
            // Form submission
            form.addEventListener('submit', function(e) {
                const otp = fullOtpInput.value;
                if (otp.length !== 6) {
                    e.preventDefault();
                    alert('Please enter a valid 6-digit OTP');
                    return false;
                }
                
                // Show loading state
                buttonText.style.display = 'none';
                buttonLoader.style.display = 'inline';
                verifyButton.disabled = true;
            });
            
            // Countdown timer
            const timer = setInterval(() => {
                timeLeft--;
                countdownElement.textContent = timeLeft;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    document.querySelector('.timer').style.display = 'none';
                    resendLink.style.display = 'block';
                }
            }, 1000);
            
            // Handle window blur to prevent OTP auto-fill issues
            window.addEventListener('blur', () => {
                if (document.activeElement && document.activeElement.classList.contains('otp-input')) {
                    setTimeout(() => {
                        document.activeElement.blur();
                        setTimeout(() => {
                            document.activeElement.focus();
                        }, 0);
                    }, 0);
                }
            });
        });
    </script>
</body>

</html>