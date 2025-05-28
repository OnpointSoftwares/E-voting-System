<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
use AfricasTalking\SDK\AfricasTalking;
require_once('africastalking-php-master/src/AfricasTalking.php');

session_start();
$con = mysqli_connect("localhost", "root", "", "voting");
$phone = $_POST['phone'];

if($_POST['phone']!=null)
{
    include "includes/voter_login_data.php";
}

echo $_SESSION['otp'];
send_sms($phone);

function send_sms($phone)
{
    $username="voting2025";
    $apiKey="atsk_43275c85d8ce0d592027241dfd1c0e25263587306b8704d077361b77bda26cdadfe95800";
    $message="Your OTP is $_SESSION[otp]";
    $to="$phone";
    $at = new AfricasTalking($username, $apiKey);
    $sms = $at->sms();
    $result = $sms->send($to, $message);
    print_r($result);
    if($result['status']=='success')
    {
        echo "
        <script>
        alert('OTP send on your phone')
        </script>
    ";
    }
    else
    {
        echo "
        <script>
        alert('Failed to send OTP!')
        </script>
    ";
    }
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