<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
use AfricasTalking\SDK\AfricasTalking;


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
    //$address="$_POST['address'];"
    $address="";
    if(isset($_POST['register']))
    {
            $con=mysqli_connect("localhost","root","","voting");
            $date1=new DateTime("$dob");
            $date2=new DateTime("now");
            $dateDiff=$date1->diff($date2);
           
            if(strlen($phone)!=13)
            {
                echo "<script> 
                        alert('Phone Number must 13 digit')
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

                $query="INSERT INTO register(fname,lname,idname,idnum,idcard,inst_id,dob,gender,phone,address,status,verify) VALUES('$fname','$lname','$idname','$idnum','$folder','$instidnum','$dob','$gender','$phone','$address','not voted','no')";
                $data=mysqli_query($con,$query);

                if($data)
                {
                    // Format phone number to international format if needed
                    $to = trim($phone);
                    if (substr($to, 0, 1) === '0') {
                        $to = '+254' . substr($to, 1);
                    } elseif (substr($to, 0, 1) !== '+') {
                        $to = '+' . $to;
                    }
                    
                    $username = "voting_2025";
                    $apiKey = "atsk_43275c85d8ce0d592027241dfd1c0e25263587306b8704d077361b77bda26cdadfe95800";
                    $message = "Thank you for Registering in Online Voting System";
                    
                    try {
                        $at = new AfricasTalking($username, $apiKey);
                        $sms = $at->sms();
                        
                        // Send SMS
                        $result = $sms->send([
                            'to'      => $to,
                            'message' => $message,
                            'from'    => '' // Optional: Set your Africa's Talking shortcode or leave empty
                        ]);
                        
                        // Log the result for debugging
                        error_log("SMS Send Result: " . print_r($result, true));
                        
                        if (isset($result['data']->SMSMessageData->Recipients[0]->status) && 
                            $result['data']->SMSMessageData->Recipients[0]->status === 'Success') {
                            $smsStatus = 'success';
                        } else {
                            $smsStatus = 'error';
                        }
                        
                        // Return JSON response
                        echo json_encode([
                            'status' => $smsStatus,
                            'phone' => $to,
                            'message' => $message,
                            'details' => $result
                        ]);
                        
                    } catch (Exception $e) {
                        error_log("SMS Error: " . $e->getMessage());
                        echo json_encode([
                            'status' => 'error',
                            'phone' => $to,
                            'message' => 'Failed to send SMS',
                            'error' => $e->getMessage()
                        ]);
                    }
                   echo"<script>
                
                            alert('Registration Successfully!')
                            //location.href='index.php'
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
                $username="voting2025";
                $apiKey="atsk_43275c85d8ce0d592027241dfd1c0e25263587306b8704d077361b77bda26cdadfe95800";
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