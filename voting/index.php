<?php
session_start();
session_destroy();
error_reporting(0);
$_SESSION['userLogin'] = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solypark Opinion Poll System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="heading text-center">
            <h1>Solypark Opinion Poll System</h1>
        </div>
        <div class="form row g-3">
            <h4>Voter Login</h4>
            <form action="otpform.php" method="POST" enctype="multipart/form-data">
                <label class="label">Phone Number:</label>
                <input type="text" name="phone" id="" class="input form-control" placeholder="Enter Phone Number" required>

                <button class="button" name="login">Login</button>
                <div class="link1">New user ? <a href="registration.php">Register here</a></div>
                <div class="link1">Change Mobile Number ? <a href="lost_phone.php">Send Request</a></div>
            </form>
            <p class="error"><?php echo $_SESSION['error']; ?></p>
        </div>

    </div>
</body>
</html>