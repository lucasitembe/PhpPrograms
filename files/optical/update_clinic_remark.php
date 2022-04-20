<?php
 include("./includes/connection.php");
 if(isset($_POST['Clinic_ID'])){
    $Clinic_ID=$_POST['Clinic_ID'];
    $remark=$_POST['remark'];
    $sql="UPDATE tbl_clinic SET remark='$remark' WHERE Clinic_ID='$Clinic_ID'";
    $check= mysqli_query($conn,$sql) or die(mysqli_error($query)); 
    if($check){
        echo "changed";
    }
    else{
        echo 'not changed';
    }

} 
    if(isset($_POST['disease_group_id'])){
        $disease_group_id=$_POST['disease_group_id'];
        $remark=$_POST['remark'];
        $sql="UPDATE tbl_disease_group SET remarks='$remark' WHERE disease_group_id='$disease_group_id'";
        $check= mysqli_query($conn,$sql) or die(mysqli_error($query)); 
        if($check){
            echo "changed";
        }
        else{
            echo 'not changed';
        }
    }
?>