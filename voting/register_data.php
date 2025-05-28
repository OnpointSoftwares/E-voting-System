<?php
require_once('africastalking-php-master/src/AfricasTalking.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include "includes/all-select-data.php";
    $count=mysqli_num_rows($voter_data);

    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $idname=$_POST['idname'];
    $idnum=$_POST['idnum'];
    $instidnum=$_POST['instidnum'];
    $dob=$_POST['dob'];
    $gender=$_POST['gender'];
    $phone=$_POST['phone'];
    $address=$_POST['address'];

    if(isset($_POST['register']))
    {
            $con=mysqli_connect("localhost","root","","voting");
            $date1=new DateTime("$dob");
            $date2=new DateTime("now");
            $dateDiff=$date1->diff($date2);
           
            if(strlen($phone)!=10)
            {
                echo "<script> 
                        alert('Phone Number must 10 digit')
                        history.back()
                    </script>";
            }
            else if(!is_numeric($phone))
            {
                echo "<script> 
                        alert('Phone Number must numeric')
                        history.back()
                    </script>";
            }
            else if(strlen($idnum)>13)
            {
                echo "<script> 
                        alert('Enter valid Id number')
                        history.back()
                    </script>";
            }
            else if($dateDiff->days<6570)
            {
                echo "<script>
                        alert('Your age must above 18 years')
                        history.back()
                    </script>";
            }
            else
            {
                $filename=$_FILES["idcard"]["name"];
                $tempname=$_FILES["idcard"]["tmp_name"];
                $folder="img/".$count.$filename;
                move_uploaded_file($tempname,$folder);

                $query="INSERT INTO register(fname,lname,idname,idnum,idcard,inst_id,dob,gender,phone,address,status) VALUES('$fname','$lname','$idname','$idnum','$folder','$instidnum','$dob','$gender','$phone','$address','not voted')";
                $data=mysqli_query($con,$query);

                if($data)
                {
                    send_sms($phone);
                   echo"<script>
                            alert('Registration Sussessfully!')
                            location.href='index.php'
                        </script>";
                }
                else
                {
                    echo "<script>
                            alert('mobile number or ID Number already exist!')
                            history.back()
                         </script>";
                }
            }
            function send_sms($phone)
            {
                $username="your_username";
                $apiKey="your_api_key";
                $message="Thank you for Registering in Online Voting System";
                $to="$phone";
                $at = new AfricasTalking($username, $apiKey);
                $sms = $at->sms();
                $result = $sms->send($to, $message);
                if($result['status']=='success')
                {
                    echo "<script>
                            alert('SMS sent successfully!')
                            location.href='index.php'
                        </script>";
                }
                else
                {
                    echo "<script>
                            alert('Failed to send SMS!')
                            history.back()
                        </script>";
                }
            }
    }

?>