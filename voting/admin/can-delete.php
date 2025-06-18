<?php
$id=$_GET['id'];;

$con = mysqli_connect("localhost","dxusvdvu_voting","Koskey@2024","dxusvdvu_voting");
$query="DELETE FROM candidate WHERE id='$id'";
$data=mysqli_query($con,$query);

if($data)
{
    echo "<script>
            alert('candidate deleted!')
            history.back()
         </script>
         <head>
    <meta name='viewport' content='width=device-width, initial-scale=1'>";
}
?>