<?php
$con = mysqli_connect("localhost","dxusvdvu_voting","Koskey@2024","dxusvdvu_voting");

$vid=$_GET['vid'];

$query="UPDATE register SET verify='yes' where id='$vid'";

$data=mysqli_query($con,$query);

if($data)
{
    echo "<script> history.back() </script>";
}

?>