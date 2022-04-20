<?php 
    include_once("./includes/connection.php");

    $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $description = (isset($_POST['description'])) ? $_POST['description'] : 0;

    $get_date_time = mysqli_query($conn,'SELECT NOW() as date_time');


    while($row = mysqli_fetch_assoc($get_date_time)){
        $date_time = $row['date_time'];

        $update = mysqli_query($conn,"UPDATE tbl_requisition SET Requisition_Description = '$description', Sent_Date_Time  = '$date_time' WHERE Requisition_ID = '$id'") or die(mysqli_error($conn));
        if(!$update){
            echo "Not updated";
        }
    }
    
?>