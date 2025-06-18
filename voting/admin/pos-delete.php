<?php
$id=$_GET['id'];
$con = mysqli_connect("localhost","dxusvdvu_voting","Koskey@2024","dxusvdvu_voting");
$query="DELETE FROM can_position WHERE id='$id'";
$data=mysqli_query($con,$query);

if($data)
{
    echo "
        <script>
            alert('position deleted!')
            history.back()
        </script>
        ";
}
?>