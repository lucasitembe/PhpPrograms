<?php
    include("./includes/connection.php");
    @session_start();

    if(isset($_POST['save_dialysis_prescription'])){//insert
        $Registration_ID=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
        $indication=  mysqli_real_escape_string($conn,$_POST['indication']);
        $medication=  mysqli_real_escape_string($conn,$_POST['medication']);
        $mode=  mysqli_real_escape_string($conn,$_POST['mode']);
        $access=  mysqli_real_escape_string($conn,$_POST['access']);
        $duration=  mysqli_real_escape_string($conn,$_POST['duration']);
        $uf_ufr=  mysqli_real_escape_string($conn,$_POST['uf_ufr']);
        $qb=  mysqli_real_escape_string($conn,$_POST['qb']);
        $dialysate=  mysqli_real_escape_string($conn,$_POST['dialysate']);
        $bath=  mysqli_real_escape_string($conn,$_POST['bath']);
        $ordered_by = $_SESSION['userinfo']['Employee_ID'];

        $insert_sql = "INSERT INTO `tbl_dialysis_inpatient_prescriptions`(`Registration_ID`, `indication`, `medication`, `mode`, `access`, `duration`, `uf_ufr`, `qb`, `dialysate`, `bath`, `ordered_by`) VALUES ('$Registration_ID','$indication','$medication','$mode','$access','$duration','$uf_ufr', '$qb', '$dialysate', '$bath', '$ordered_by')";
        
        $query=  mysqli_query($conn,$insert_sql) or die(mysqli_error($conn));
        if($query){ 
            echo 'ok';
        }else{
            echo mysqli_error($conn);
        }
    
    }

    if(isset($_POST['prescription_id'])){//update
        $done_by = $_SESSION['userinfo']['Employee_ID'];
        $prescription_id = $_POST['prescription_id'];

        $update_sql = "UPDATE `tbl_dialysis_inpatient_prescriptions` SET `done_by`='$done_by',`done_on`=NOW(),`status`='Done' WHERE prescription_id='$prescription_id'";
        
        $query=  mysqli_query($conn,$update_sql) or die(mysqli_error($conn));
        if($query){ 
            echo 'ok';
        }else{
            echo mysqli_error($conn);
        }
    
    }

    if(isset($_GET['Registration_ID'])){//count
        $Registration_ID = $_GET['Registration_ID'];
        echo mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(prescription_id) AS total_prescriptions FROM tbl_dialysis_inpatient_prescriptions WHERE Registration_ID='$Registration_ID' AND status='Not Done'"))['total_prescriptions'];
    }