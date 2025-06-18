<?php
// Start session
session_start();

// Set test OTP if not set
if (!isset($_SESSION['otp'])) {
   header("Location: otpform.php");
   exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Enter Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .otp-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .otp-input {
            width: 40px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .submit-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background: #45a049;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
        }
        .error {
            background: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <div class="otp-box row g-3">
        <h2 class="text-center">Enter Password</h2>
        <p>Enter the 6-digit password</p>
        
        <form method="post" action="verify_otp_handler.php" enctype="multipart/form-data">
            <div class="otp-inputs row g-3">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <input type="text" name="otp<?php echo $i; ?>" class="otp-input form-control" maxlength="1" pattern="\d" required>
                <?php endfor; ?>
            </div>
            <button type="submit" class="submit-btn btn btn-primary">Verify Password</button>
        </form>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="message error">
                <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
            </div>
        <?php endif; ?>
    </div>
    </div>
    <script>
    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.otp-input');
        if (inputs.length > 0) inputs[0].focus();
        
        // Auto-tab between inputs
        inputs.forEach((input, index) => {
            // Allow only numbers
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '');
                
                // Auto-tab to next input
                if (e.target.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            
            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    });
    </script>
</body>
</html>
