<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_SESSION['Admission_Requisition_ID'])){
        $Requisition_ID = $_SESSION['Admission_Requisition_ID'];

        $sql_select = mysqli_query($conn,"select rq.Requisition_ID from tbl_requisition rq, tbl_requisition_items rqi where
                                    rq.Requisition_ID = rqi.Requisition_ID and
                                        rq.Requisition_Status = 'pending' and
                                            rq.Requisition_id = '$Requisition_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($sql_select);
        if($num > 0 ){
            echo 'Yes';
        }else{
            echo 'No';
        }
    }else{
        echo 'No';
    }
?>