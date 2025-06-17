<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set test OTP if not set
if (!isset($_SESSION['otp'])) {
    $_SESSION['otp'] = '123456';
    $_SESSION['phone'] = '0702502952';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .otp-container {
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 400px;
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            gap: 10px;
        }
        .otp-input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .btn {
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .btn:hover {
            background: #45a049;
        }
        .debug-info {
            margin-top: 30px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        #result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .error {
            background: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>OTP Verification Test</h2>
        <p>Enter the 6-digit OTP sent to <?php echo htmlspecialchars(substr($_SESSION['phone'] ?? '', -4)); ?></p>
        
        <form id="otpForm">
            <div class="otp-inputs">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <input type="text" name="otp<?php echo $i; ?>" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" required>
                <?php endfor; ?>
            </div>
            <button type="submit" class="btn">Verify OTP</button>
        </form>
        
        <div id="result"></div>
        
        <div class="debug-info">
            <h3>Debug Information:</h3>
            <p><strong>Session OTP:</strong> <?php echo htmlspecialchars($_SESSION['otp'] ?? 'Not set'); ?></p>
            <p><strong>Session Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone'] ?? 'Not set'); ?></p>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('otpForm');
        const resultDiv = document.getElementById('result');
        const otpInputs = document.querySelectorAll('.otp-input');
        
        // Auto-focus first input
        if (otpInputs.length > 0) {
            otpInputs[0].focus();
        }
        
        // Handle auto-tab between inputs
        otpInputs.forEach((input, index) => {
            // Allow only numbers
            input.addEventListener('input', (e) => {
                // Remove any non-digit characters
                e.target.value = e.target.value.replace(/\D/g, '');
                
                // Auto-tab to next input
                if (e.target.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });
        
        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Verifying...';
            
            try {
                const formData = new FormData(form);
                const response = await fetch('verify_otp_handler.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                // Show result
                resultDiv.style.display = 'block';
                resultDiv.className = result.success ? 'success' : 'error';
                resultDiv.textContent = result.message;
                
                // Redirect if successful
                if (result.success && result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                }
                
            } catch (error) {
                resultDiv.style.display = 'block';
                resultDiv.className = 'error';
                resultDiv.textContent = 'An error occurred. Please try again.';
                console.error('Error:', error);
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    });
    </script>
</body>
</html>
