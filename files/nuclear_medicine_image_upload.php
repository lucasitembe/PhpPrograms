<?php

include("../includes/connection.php");
//get all item details based on item id
session_start();
if(isset($_POST['save_image'])){
    if (isset($_POST['Registration_ID'])) {
        $Registration_ID = $_POST['Registration_ID'];
    } else {
        $Registration_ID = 0;
    }
    if (isset($_POST['Payment_Item_Cache_List_ID'])) {
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    } else {
        $Payment_Item_Cache_List_ID = 0;
    }

    if(isset($_POST['attachment'])){
        $attachment = $_POST['attachment'];
    }else{
        $aattachment='None';
    }
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $sql = "INSERT INTO tbl_nm_image(Registration_ID, Nuclearmedicine_image, Patient_Payment_Item_List_ID,Employee_ID) VALUES( '$Registration_ID',  '$attachment','$Payment_Item_Cache_List_ID','$Employee_ID')";
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if ($result) {
        echo "Attachment Uploaded Successfully.";
    } else {
        echo "Failed To Upload Image,Try again or contact the system admin";
    }
}


if(isset($_POST['load_image'])){
    if (isset($_POST['Registration_ID'])) {
        $Registration_ID = $_POST['Registration_ID'];
    } else {
        $Registration_ID = 0;
    }
    if (isset($_POST['Payment_Item_Cache_List_ID'])) {
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    } else {
        $Payment_Item_Cache_List_ID = 0;
    }

    $selctimige = mysqli_query($conn, "SELECT * FROM tbl_nm_image WHERE Patient_Payment_Item_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

    if(mysqli_num_rows($selctimige)>0){
        while($rw = mysqli_fetch_assoc($selctimige)){
            $Nuclearmedicine_image= $rw['Nuclearmedicine_image'];
           $Pic_ID =$rw['Pic_ID'];

            $imaging = "<a href='Nm/imageuploads/".$Nuclearmedicine_image."' title='Nuclear Medicine attachment For ID#. $Registration_ID' class='fancyboxRadimg' target='_blank'><img height='100' alt=''  src='Nm/imageuploads/".$Nuclearmedicine_image."'  alt='Nuclear Medicine attachment'/></a>";

            echo "<div class='row col-md-2' style='width: 40%;height: 30px; padding-bottom: 110px;'>$imaging  <input type='button' class='btn btn-warning btn-xs' style='height:20px;width:30px; background-color:red;' value='X' onclick=removeImage($Pic_ID)></div>";
        }
    }else{
        echo "No any attachment";
    }
}

if(isset($_POST['remove_image'])){
    $Pic_ID = $_POST['Pic_ID'];
    $sqlDelect = mysqli_query($conn, "DELETE FROM tbl_nm_image WHERE Pic_ID='$Pic_ID'") or die(mysqli_error($conn));
    if($sqlDelect){
        echo "Attachment deleted";
    }else{
        echo "Failed to delete an Attachment";
    }
}