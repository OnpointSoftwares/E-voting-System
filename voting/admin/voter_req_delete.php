    <?php

            $id=$_GET['id'];
            $con = mysqli_connect("localhost","dxusvdvu_voting","Koskey@2024","dxusvdvu_voting");
            $query="DELETE FROM phno_change WHERE id='$id'";
            $data=mysqli_query($con,$query);

            if($data)
            {
                echo "<script> history.back()</script>";
            }
        ?>  