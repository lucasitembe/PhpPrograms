<?php
session_start();
 include("./includes/connection.php");

  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
if(isset($_POST['selected_item'])){
    $selected_item=$_POST['selected_item'];
    $Registration_ID=$_POST['Registration_ID'];
    $check_resut=0;
    foreach ($selected_item as $selected_item_n_status){
          $selected_item_n_status;
        
          $selected_item_array=explode("..kiunganishi",$selected_item_n_status);
           $Patient_Payment_Item_List_ID=$selected_item_array[0];
          $paymentstatus=$selected_item_array[1];
//          echo "payment_id=>$Patient_Payment_Item_List_ID \n";
//          echo "payment status=>$paymentstatus \n";
          $specimenRejectionQr=false;
          if($paymentstatus == "Not Billed" || $paymentstatus == "Paid" || $paymentstatus == "Approved"){
              $sql  = "select ref_specimen_ID,tests_specimen_ID from tbl_tests_specimen WHERE tests_item_ID IN (SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID =  '$Patient_Payment_Item_List_ID')";
              $sql_result = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
             
              if(mysqli_num_rows($sql_result)>0){
                   
                  while($rows = mysqli_fetch_assoc($sql_result)){
                   $ref_specimen_ID  =(int)$rows['ref_specimen_ID'];
                   $tests_specimen_ID  =(int)$rows['tests_specimen_ID'];
                   
                   $sql_check_if_exist = mysqli_query($conn,"SELECT Specimen_ID FROM tbl_specimen_results WHERE payment_item_ID='$Patient_Payment_Item_List_ID' AND Specimen_ID='$ref_specimen_ID'") or die(mysqli_error($conn));
                  
                  if(mysqli_num_rows($sql_check_if_exist) > 0){
                             $specimenRejectionQr=mysqli_query($conn,"UPDATE tbl_specimen_results SET collection_Status='not collected',TimeCollected=NOW(),specimen_results_Employee_ID='".$Employee_ID."' WHERE payment_item_ID='$Patient_Payment_Item_List_ID' AND Specimen_ID='$ref_specimen_ID'")or die(mysqli_error($conn));
                    }else{
                            $specimenRejectionQr=mysqli_query($conn,"INSERT INTO tbl_specimen_results (payment_item_ID,Specimen_ID,collection_Status,specimen_results_Employee_ID,TimeCollected,BarCode) VALUES ('$Patient_Payment_Item_List_ID','$ref_specimen_ID','not collected','$Employee_ID',NOW(),'')") or die(mysqli_error($conn));
                  }
                  if($specimenRejectionQr)$check_resut++;
                 }
              }else{
                  echo 201;
              }
    } 
    
}  
if($check_resut>0){
   echo "collected_successfullly"; 
}else{
   echo "fail"; 
}
}

if(isset($_POST['uncollectSpecimen'])){
    $Patient_Payment_Item_List_ID =$_POST['searchspecmen_id'];
    $Registration_ID=$_POST['Registration_ID'];
    $check_resut=0;
    
    $sql  = "select ref_specimen_ID,tests_specimen_ID from tbl_tests_specimen WHERE tests_item_ID IN (SELECT Item_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID =  '$Patient_Payment_Item_List_ID')";
    $sql_result = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 

    if(mysqli_num_rows($sql_result)>0){
        while($rows = mysqli_fetch_assoc($sql_result)){
         $ref_specimen_ID  =(int)$rows['ref_specimen_ID'];
         $tests_specimen_ID  =(int)$rows['tests_specimen_ID'];

         $sql_check_if_exist = mysqli_query($conn,"SELECT Specimen_ID FROM tbl_specimen_results WHERE payment_item_ID='$Patient_Payment_Item_List_ID' AND Specimen_ID='$ref_specimen_ID'") or die(mysqli_error($conn));

        if(mysqli_num_rows($sql_check_if_exist) > 0){
            $sql_result2 = mysqli_query($conn,"DELETE FROM `tbl_test_results` WHERE `payment_item_ID`='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn)); 
            $specimenRejectionQr=mysqli_query($conn,"UPDATE tbl_specimen_results SET collection_Status='not collected',TimeCollected=NOW(),specimen_results_Employee_ID='".$Employee_ID."' WHERE payment_item_ID='$Patient_Payment_Item_List_ID' AND Specimen_ID='$ref_specimen_ID'")or die(mysqli_error($conn));
            $specimenRejectionQr=mysqli_query($conn,"UPDATE `tbl_item_list_cache` SET `Status`='Paid' WHERE `Payment_Item_Cache_List_ID`='$Patient_Payment_Item_List_ID'")or die(mysqli_error($conn));
        }else{
                  $specimenRejectionQr=mysqli_query($conn,"INSERT INTO tbl_specimen_results (payment_item_ID,Specimen_ID,collection_Status,specimen_results_Employee_ID,TimeCollected,BarCode) VALUES ('$Patient_Payment_Item_List_ID','$ref_specimen_ID','not collected','$Employee_ID',NOW(),'')") or die(mysqli_error($conn));
                  $specimenRejectionQr=mysqli_query($conn,"UPDATE `tbl_item_list_cache` SET `Status`='Paid' WHERE `Payment_Item_Cache_List_ID`='$Patient_Payment_Item_List_ID'")or die(mysqli_error($conn));      
        }
        if($specimenRejectionQr)$check_resut++;
       }
    }
    
    if($check_resut>0){
        echo (int)1; 
     }else{
        echo 0; 
     }
}
?>
