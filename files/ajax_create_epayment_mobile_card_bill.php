<?php
    include("./includes/connection.php");
    session_start();  
//POST posted values
if(isset($_POST['Payment_Cache_ID'])){
    $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
}else{
    $Payment_Cache_ID = '';
}
if(isset($_POST['Sub_Department_Name'])){
    $Sub_Department_Name = $_POST['Sub_Department_Name'];
}else{
    $Sub_Department_Name = '';
}
    
if(isset($_POST['Registration_ID'])){
    $Registration_ID = $_POST['Registration_ID'];
}else{
    $Registration_ID = '';
}   
if(isset($_POST['Check_In_Type'])){
    $Check_In_Type=$_POST['Check_In_Type'];
}else{
   $Check_In_Type=""; 
}
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
    $update = "update tbl_item_list_cache set status = 'approved',Sub_Department_ID=$Sub_Department_ID where
                    status = 'active' and 
                        Payment_Cache_ID = '$Payment_Cache_ID'
                                and  Check_In_Type='$Check_In_Type'";
    $update_result=mysqli_query($conn,$update) or die(mysqli_error($conn));
    if($update_result){
        echo "success";
    }else{
       echo "fail"; 
    }
?>