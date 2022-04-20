<?php
include("./includes/connection.php");
session_start();
if(isset($_POST['selected_Phamathetical_dataelement_id'])){
   $selected_Phamathetical_item_id=$_POST['selected_Phamathetical_dataelement_id']; 
}
if(isset($_POST['phabrand_id'])){
    $phabrand_idphabrand_id=$_POST['phabrand_id'];
}
    function Start_Transaction(){
        mysqli_query($conn,"START TRANSACTION");
    }

    function Commit_Transaction(){
        mysqli_query($conn,"COMMIT");
    }

    function Rollback_Transaction(){
        mysqli_query($conn,"ROLLBACK");
    }

Start_Transaction();
$an_error_occured=FALSE;
$item_array=explode(".unganishana",$selected_Phamathetical_item_id);
$item_id=$item_array[0];
$original_item_id=$item_id;
$item_name=$item_array[1];
foreach($phabrand_idphabrand_id as $phabrand_id_n_name){
    $array=explode(".unganishana",$phabrand_id_n_name);
    $phabrand_id=$array[0];
    $phabrand_name=$array[1];
   
    //select original item detail
    $sql_select_original_item_detail_result=mysqli_query($conn,"SELECT Item_Type,NHIFItem_Type,Product_Code,Unit_Of_Measure,Item_Subcategory_ID,Status,Reoder_Level,Consultation_Type,Can_Be_Substituted_In_Doctors_Page,Visible_Status,Ward_Round_Item,item_kind,CASH,CREDIT,NHIF,Classification,Can_Be_Sold,Can_Be_Stocked,allow_zero_price,Ct_Scan_Item,consultation_Item,Seen_On_Allpayments,Particular_Type,Free_Consultation_Item,Package_Type,Tax,item_folio_number,original_item_id FROM tbl_items WHERE Item_ID='$item_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_original_item_detail_result)>0){
        $item_detail_rows=mysqli_fetch_assoc($sql_select_original_item_detail_result);
        $Item_Type=$item_detail_rows['Item_Type'];
        $Status=$item_detail_rows['Status'];
        $NHIFItem_Type=$item_detail_rows['NHIFItem_Type'];
        $Product_Code=$item_detail_rows['Product_Code'];
        $Unit_Of_Measure=$item_detail_rows['Unit_Of_Measure'];
        $Item_Subcategory_ID=$item_detail_rows['Item_Subcategory_ID'];
        $Reoder_Level=$item_detail_rows['Reoder_Level'];
        $Consultation_Type=$item_detail_rows['Consultation_Type'];
        $Can_Be_Substituted_In_Doctors_Page=$item_detail_rows['Can_Be_Substituted_In_Doctors_Page'];
        $Visible_Status=$item_detail_rows['Visible_Status'];
        $Ward_Round_Item=$item_detail_rows['Ward_Round_Item'];
        $item_kind=$item_detail_rows['item_kind'];
        $CASH=$item_detail_rows['CASH'];
        $NHIF=$item_detail_rows['NHIF'];
        $CREDIT=$item_detail_rows['CREDIT'];
        $Classification=$item_detail_rows['Classification'];
        $Can_Be_Sold=$item_detail_rows['Can_Be_Sold'];
        $allow_zero_price=$item_detail_rows['allow_zero_price'];
        $Can_Be_Stocked=$item_detail_rows['Can_Be_Stocked'];
        $Ct_Scan_Item=$item_detail_rows['Ct_Scan_Item'];
        $consultation_Item=$item_detail_rows['consultation_Item'];
        $Seen_On_Allpayments=$item_detail_rows['Seen_On_Allpayments'];
        $Particular_Type=$item_detail_rows['Particular_Type'];
        $Free_Consultation_Item=$item_detail_rows['Free_Consultation_Item'];
        $Package_Type=$item_detail_rows['Package_Type'];
        $Tax=$item_detail_rows['Tax'];
        $item_folio_number=$item_detail_rows['item_folio_number'];
       
    }
    ///create new item pharmacetical items with merged brand name
    $original_item_id=$item_id;
    $Product_Name=$item_name."~~>".$phabrand_name;
    $Product_Name=mysqli_real_escape_string($conn,$Product_Name);
    $sql_insert_new_brand_item_result=mysqli_query($conn,"INSERT INTO tbl_items(Item_Type,NHIFItem_Type,Product_Code,Unit_Of_Measure,Item_Subcategory_ID,Status,Reoder_Level,Consultation_Type,Can_Be_Substituted_In_Doctors_Page,Visible_Status,Ward_Round_Item,item_kind,CASH,CREDIT,NHIF,Classification,Can_Be_Sold,Can_Be_Stocked,allow_zero_price,Ct_Scan_Item,consultation_Item,Seen_On_Allpayments,Particular_Type,Free_Consultation_Item,Package_Type,Tax,item_folio_number,original_item_id,Product_Name) VALUES('$Item_Type','$NHIFItem_Type','$Product_Code','$Unit_Of_Measure','$Item_Subcategory_ID','Available','$Reoder_Level','$Consultation_Type','$Can_Be_Substituted_In_Doctors_Page','$Visible_Status','$Ward_Round_Item','$item_kind','$CASH','$CREDIT','$NHIF','$Classification','$Can_Be_Sold','$Can_Be_Stocked','$allow_zero_price','$Ct_Scan_Item','$consultation_Item','$Seen_On_Allpayments','$Particular_Type','$Free_Consultation_Item','$Package_Type','$Tax','$item_folio_number','$original_item_id','$Product_Name')") or die(mysqli_error($conn));
    if(!$sql_insert_new_brand_item_result){
        $an_error_occured=TRUE;
        echo "fail=>2";
    }
}
////MEDGE PRICE of each sponsor
$sql_select_all_sponsor_id_result=mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_sponsor_id_result)){
   while($sponsor_rows=mysqli_fetch_assoc($sql_select_all_sponsor_id_result)){
      $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
      ///get item price per this sponsor
      $sql_get_item_price_per_sponsor_result=mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID' AND Item_ID='$item_id'") or die(mysqli_error($conn));
      if(mysqli_num_rows($sql_get_item_price_per_sponsor_result)>0){
          $Items_Price=mysqli_fetch_assoc($sql_get_item_price_per_sponsor_result)['Items_Price'];
          //select all new brand name item generated from this original item
          $sql_select_new_brand_name_item_result=mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE original_item_id='$item_id'") or die(mysqli_error($conn));
          if(mysqli_num_rows($sql_select_new_brand_name_item_result)>0){
              while($new_brand_name_rows=mysqli_fetch_assoc($sql_select_new_brand_name_item_result)){
                  $Item_ID=$new_brand_name_rows['Item_ID'];
                  //merge item price to new item per specific sponsor
                  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                  $sql_merge_price_result=mysqli_query($conn,"INSERT INTO tbl_item_price(Sponsor_ID,Item_ID,Items_Price,last_updated_by) VALUES('$Sponsor_ID','$Item_ID','$Items_Price','$Employee_ID')") or die(mysqli_error($conn));
                  if(!$sql_merge_price_result){
                      $an_error_occured=TRUE;
                      echo "fail=>3";
                  }
              }
          }
      }
   } 
}
 //disable original item
    $disable_original_item_result=mysqli_query($conn,"UPDATE tbl_items SET Status='Not Available' WHERE Item_ID='$original_item_id'") or die(mysqli_error($conn));
    if(!$disable_original_item_result){
        $an_error_occured=TRUE;
        echo "fail=>1";
    }
//clear cached brand name
$sql_delete_result=mysqli_query($conn,"DELETE FROM tbl_phamathetical_item_brand_name WHERE phamathetical_item_id='$original_item_id'") or die(mysqli_error($conn));
if(!$an_error_occured){
   Commit_Transaction(); 
}else{
   Rollback_Transaction(); 
}

///select all merged item
$sql_select_merged_item_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE original_item_id='$original_item_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_merged_item_result)>0){
    $count_sn=1;
    while($merged_items_rows=mysqli_fetch_assoc($sql_select_merged_item_result)){
        $Product_Name=$merged_items_rows['Product_Name'];
        echo "<tr><td>$count_sn.</td><td>$Product_Name</td></tr>";
        $count_sn++;
    }
}