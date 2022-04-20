<?php
    include("./includes/connection.php");
    if(isset($_GET['item_folio_number'])){
        $item_folio_number=$_GET['item_folio_number'];
        $item_folio_number=mysqli_real_escape_string($conn,$item_folio_number);
    }
    if(isset($_GET['Item_ID'])){
        $Item_ID=$_GET['Item_ID'];
    }
  $sql_update_folio_number_result=mysqli_query($conn,"UPDATE tbl_items SET item_folio_number='$item_folio_number' WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));  
  if($sql_update_folio_number_result){
      echo "updated";
  }else{
      echo "fail";
  }
  ?>