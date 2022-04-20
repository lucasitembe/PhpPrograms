<?php

include("./includes/connection.php");
include("allFunctions.php");
session_start();
$employee_id = $_SESSION['userinfo']['Employee_ID'];
        if(isset($_POST['save_image'])){

            if(isset($_POST['description'])){
                $description = $_POST['description'];
            }else{
                $description='';
            }
            if(isset($_POST['department'])){
                $department = $_POST['department'];
            }else{
                $department='';
            }
            if(isset($_POST['Patient_Payment_ID'])){
                $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
            }else{
                $Patient_Payment_ID='';
            }
            if(isset($_POST['Registration_ID'])){
                $Registration_ID = $_POST['Registration_ID'];
            }else{
                $Registration_ID='';
            }

            if(isset($_POST['attachment'])){
                $file_name = $_POST['attachment'];
            }else{
                $file_name='';
            }
            $date=date('Y-m-d H:i:s');
           
            $sql= mysqli_query($conn,"INSERT INTO tbl_attachment  (Registration_ID,Employee_ID,Description,Check_In_Type,Attachment_Url,Attachment_Date, Patient_Payment_ID) VALUES('$Registration_ID','$employee_id','$description','$department','$file_name','$date', '$Patient_Payment_ID')") or die(mysqli_error($conn));

            if($sql){
                echo 'sent';
            }else{
                echo 'not sent';
            }

        }
    if(isset($_POST['load_image'])){
        if(isset($_POST['Patient_Payment_ID'])){
            $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
        }else{
            $Patient_Payment_ID='';
        }
        if(isset($_POST['Registration_ID'])){
            $Registration_ID = $_POST['Registration_ID'];
        }else{
            $Registration_ID='';
        }

        $selctimige = mysqli_query($conn, "SELECT * FROM tbl_attachment WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

        if(mysqli_num_rows($selctimige)>0){
            while($rw = mysqli_fetch_assoc($selctimige)){
                $Attachment_Url= $rw['Attachment_Url'];
                $Attachment_ID =$rw['Attachment_ID'];

                $imaging = "<a href='attachment/".$Attachment_Url."' title='Other Attachment For ID#. $Registration_ID' class='fancyboxRadimg' target='_blank'><img height='100' alt=''  src='attachment/".$Attachment_Url."'  alt='Other Attachment'/></a>";

                echo "<div class='row col-md-2' style='width: 40%;height: 30px; padding-bottom: 110px;'>$imaging  <input type='button' class='btn btn-warning btn-xs' style='height:20px;width:30px; background-color:red;' value='X' onclick=removeImage($Attachment_ID)></div>";
            }
        }else{
            echo "No any attachment";
        }

    }
    if(isset($_POST['remove_image'])){
        $Attachment_ID = $_POST['Attachment_ID'];
        $sqlDelect = mysqli_query($conn, "DELETE FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'  AND Employee_ID='$employee_id' ") or die(mysqli_error($conn));
        if($sqlDelect){
            echo "Attachment deleted";
        }else{
            echo "Failed to delete an Attachment";
        }
    }
    // ALTER TABLE `tbl_attachment` CHANGE `payment_item_list_id` `Patient_Payment_ID` INT NULL; 
?>