<?php
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

// Set test data
$_SESSION['phone'] = '0768900702';
$_SESSION['otp'] = '123456';
?>
<!DOCTYPE html>
<html>
<head>
    <title>OTP Test</title>
</head>
<body>
    <h1>OTP Test Page</h1>
    <p>If you can see this, PHP is working.</p>
    
    <?php
    // Display session data
    echo '<h2>Session Data:</h2>';
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    
    // Test database connection
    echo '<h2>Database Test:</h2>';
    try {
        $con = new mysqli("localhost", "root", "", "voting");
        if ($con->connect_error) {
            throw new Exception("Connection failed: " . $con->connect_error);
        }
        echo '<p style="color: green;">✅ Database connection successful!</p>';
        $con->close();
    } catch (Exception $e) {
        echo '<p style="color: red;">❌ Database error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
    
    <h2>PHP Info:</h2>
    <p>PHP Version: <?php echo phpversion(); ?></p>
    
    <h2>Error Log:</h2>
    <pre><?php 
    $logFile = __DIR__ . '/otp_errors.log';
    if (file_exists($logFile)) {
        echo htmlspecialchars(file_get_contents($logFile));
    } else {
        echo "Log file not found at: " . htmlspecialchars($logFile);
    }
    ?></pre>
</body>
</html>
<?php
// Flush output buffer
ob_end_flush();
