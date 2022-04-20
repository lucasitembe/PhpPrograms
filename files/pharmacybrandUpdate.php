<?php
    include("./includes/connection.php");
/*    if(isset($_POST['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
    if(isset($_POST['Item_ID'])){
        $Item_ID = $_POST['Item_ID'];
    }else{
        $Item_ID = 0;
    }

    if(isset($_POST['brand_id'])){
        $brand_name_id = $_POST['brand_id'];
    }else{
        $brand_name_id = '';
    }
    echo $brand_name_id;
    echo $Payment_Item_Cache_List_ID;

    $update_qr = mysqli_query($conn,"update tbl_item_list_cache set brand_id = '$brand_name_id' where
                        Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_error($conn));

    if($update_qr){
        echo 1;
    }else{
        echo 0;
    }
    */

    /*
    new Kipanga codes for brand items
    */
    if(isset($_POST['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
    if(isset($_POST['Item_ID'])){
        $Item_ID = $_POST['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    if(isset($_POST['Sponsor_ID'])){
        $Sponsor_ID = $_POST['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }
    if(isset($_POST['Sub_Department_ID'])){
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    if(isset($_POST['Employee_ID'])){
        $Employee_ID = $_POST['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    $Sponsor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor  WHERE  Sponsor_ID = '$Sponsor_ID'"))['Guarantor_Name'];
    //die(json_encode(array('sponsor' => $Sponsor)));
    if(($Payment_Item_Cache_List_ID != 0 && $Item_ID != 0 && $Sponsor_ID != 0) || $Sponsor == 'NHIF'){
      if($Sponsor == 'NHIF'){
        /*
        get the generic item Id
        */
        $Generic_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Generic_ID FROM tbl_items WHERE Item_ID = '$Item_ID'"))['Generic_ID'];
        /*
          find the price of the generic item
        */

        $Price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID = '$Sponsor_ID' AND Item_ID = '$Generic_ID' "))['Items_Price'];
      }else{
        $Price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Sponsor_ID = '$Sponsor_ID' AND Item_ID = '$Item_ID' "))['Items_Price'];
      }
    //die('poa '.$Price.' '.$Sponsor_ID);
    if($Price != 0){
      $balance_result = mysqli_query($conn,"SELECT item_balance FROM tbl_items_balance WHERE Item_ID = '$Item_ID' AND Sub_Department_ID = '$Sub_Department_ID'");
      $balance = mysqli_fetch_assoc($balance_result)['item_balance'];
      $result['balance'] = $balance;
      $result['price'] = $Price;

      $update_qr = mysqli_query($conn,"update tbl_item_list_cache set Item_ID = '$Item_ID', Price = '$Price' where Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID") or die(mysqli_error($conn));

        if($update_qr){
          mysqli_query($conn,"INSERT INTO tbl_generic_order_log(Generic_ID,Brand_ID,Payment_Item_Cache_List_ID,emp_changed) VALUES('$Generic_ID','$Item_ID','$Payment_Item_Cache_List_ID','$Employee_ID')");
          $result['status'] = 'success';
          //echo 1;
        }else{
          $result['status'] = 'fail';
          //  echo 0;
        }
      }else {
        $result['status'] = 'no_price';
      }
    }else {
      $result['status'] = 'invalid';
    }
    echo json_encode($result);
?>
