<?php
include("./includes/connection.php");

  $Item_ID = $_POST['Item_ID'];
  $Registration_ID = $_POST['Registration_ID'];
  $Sponsor_ID = $_POST['Sponsor_ID'];
  $Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];

  $auto_item_update_api = mysqli_fetch_assoc(mysqli_query($conn,"SELECT auto_item_update_api FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'"))['auto_item_update_api'];
    $Check_In_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1"))['Check_In_ID'];
      if($auto_item_update_api == 1){


      //find the patient package_id as checked in from the reception
      // $query1 = mysqli_query($conn, "SELECT package_id, AuthorizationNo  FROM tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE ci.Check_In_ID = pp.Check_In_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND ppl.Patient_Payment_Item_List_ID = $Patient_Payment_Item_List_ID ORDER BY pp.Patient_Payment_ID DESC LIMIT  1");
      
      $query1 = mysqli_query($conn, "SELECT package_id, AuthorizationNo  FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' ORDER BY Check_In_ID DESC LIMIT 1");
      if(mysqli_num_rows($query1)>0){
        while($rw = mysqli_fetch_assoc($query1)){
          $package_id = $rw['package_id'];
          $AuthorizationNo =$rw['AuthorizationNo'];          

          // if($AuthorizationNo !='' && $AuthorizationNo !='0'){           
            //check if the item is excluded in the patinet package
            $query = mysqli_query($conn, "SELECT i.Product_Name, nss.ItemCode, nsp.package_name FROM tbl_nhif_services_status nss, tbl_items i, tbl_nhif_scheme_package nsp WHERE nsp.package_id = nss.package_id AND nss.ItemCode = i.Product_Code AND nss.package_id = '$package_id' AND i.Item_ID = '$Item_ID'");
            // if(mysqli_affected_rows($conn) > 0){
            //   $query_str = mysqli_fetch_assoc($query);
            //   $ItemCode = $query_str['ItemCode'];
            //   $Product_Name = $query_str['Product_Name'];
            //   $package_name = $query_str['package_name'];
            //   echo json_encode(array('message'=>'excluded','package_id'=>$package_id,'item_code'=>$ItemCode, 'Product_Name'=>$Product_Name,'package_name'=>$package_name));
            // }else{
              echo json_encode(array('message'=>'included'));
            // }
          // }else{
            // echo json_encode(array('message'=>'NotAuthorized'));
          // }
        }
      }
      
     
    }else{
      echo json_encode(array('message'=>'outside_sponsor'));
    }
 ?>
