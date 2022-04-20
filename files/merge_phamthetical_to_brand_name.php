<?php
include("./includes/connection.php");
if(isset($_POST['Item_ID'])&&isset($_POST['selected_Phamathetical_dataelement_id'])){
   $Generic_ID=(int)$_POST['selected_Phamathetical_dataelement_id'];
   $Brand_ID=(int)$_POST['Item_ID'];
   //
   // $sql_check_Phamathetical_element_to_brand_name_result=mysqli_query($conn,"SELECT brand_name_id FROM tbl_phamathetical_item_brand_name WHERE brand_name_id='$Item_ID' AND phamathetical_item_id='$selected_Phamathetical_dataelement_id'") or die(mysqli_error($conn));
   //
   // if(mysqli_num_rows($sql_check_Phamathetical_element_to_brand_name_result)>0){
   //
   //     echo "value already exist";
   //
   // }else{

    //$sql_add_Phamathetical_element_to_brand_name_result=mysqli_query($conn,"INSERT INTO tbl_phamathetical_item_brand_name(phamathetical_item_id,brand_name_id) VALUES('$selected_Phamathetical_dataelement_id','$Item_ID')") or die(mysqli_error($conn));

    /*
      select data from brand item
    */
    // $brand_result  = mysqli_query($conn,"SELECT * FROM tbl_brand_name WHERE brand_id = $Item_ID");
    // $brand_name = mysqli_fetch_assoc($brand_result)['brand_name'];

    /*
      select data from generic item
    */
    if($Generic_ID == $Brand_ID){
      $result['status'] = 'same';
    }else{
    $generic_result = mysqli_query($conn,"SELECT  * FROM tbl_items WHERE Item_ID = $Generic_ID");
    //print_r(mysqli_fetch_assoc($generic_result));
    if(mysqli_num_rows($generic_result) > 0){
      $generic_data = mysqli_fetch_assoc($generic_result);
      //die('<br> ona'.$generic_data['Item_Type']);

      /*
      check the existance of the item
      */

      $brand_result = mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Item_ID = '$Brand_ID' AND item_kind = 'brand'");
      if(mysqli_num_rows($brand_result) > 0){
        $result['status']="exist";
      }else{
        /*
          update item to brand
        */
        $brand_result = mysqli_query($conn,"UPDATE tbl_items SET item_kind = 'brand', Generic_ID = '$Generic_ID' WHERE Item_ID = '$Brand_ID'");

        if($brand_result){
          $result['status']= "success";
        }else {
          $result['status']= "fail";
        }
      }
    }
}

    echo json_encode($result);
//}
}
?>
