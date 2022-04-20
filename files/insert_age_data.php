<?php
 include("./includes/connection.php");
 
if(isset($_POST['start_age'])) {
    $start_age = $_POST['start_age'];
} else {
    $start_age = 0;
}
if(isset($_POST['end_age'])) {
    $end_age = $_POST['end_age'];
} else {
    $end_age = 0;
}
   
                   
            $sql_select_age_result=mysqli_query($conn,"SELECT start_age,end_age FROM tbl_age_range WHERE start_age='$start_age' AND end_age='$end_age'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_age_result)<=0){
                   $sql_insert_age = mysqli_query($conn,"INSERT INTO tbl_age_range(start_age,end_age) VALUES('$start_age','$end_age') ");

                }
                
                echo "successuful";

                     
