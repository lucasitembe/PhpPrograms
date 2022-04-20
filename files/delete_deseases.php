<?php

 include("./includes/connection.php");
 echo "jkhihiu";
if(isset($_POST['selected_desease'])){
    $selected_desease=$_POST['selected_desease'];
    foreach ($selected_desease as $deseases){
        $sql_delete = mysqli_query($conn,"DELETE FROM tbl_desease_diagnosis WHERE diagnosis_id=$deseases");
       
    }   
}

