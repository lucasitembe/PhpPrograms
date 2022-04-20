<?php
include 'includes/connection.php';
// check if item has balance
function checkItemBalance($itemId,$subDepartmentId){
  global $conn;
  $getItemBalance = "SELECT Item_Balance FROM tbl_items_balance WHERE Item_ID = '$itemId' AND Sub_Department_ID = '$subDepartmentId'";
  $result = mysqli_query($conn,$getItemBalance) or die(mysqli_error($conn));
  $numRows = mysqli_num_rows($result);
  if ($numRows > 0) {
    while ($row = mysqli_fetch_assoc($result)){
      $balance = $row['Item_Balance'];
    }
    return $balance;
  }else {
    // initialize item balance it tbl_items_balance
    initializeItemBalance($itemId,$subDepartmentId);
  }
}

function initializeItemBalance($itemId,$subDepartmentId){
  global $conn;
  $balance = 0;
  $insert_balance = "INSERT INTO tbl_items_balance(Item_ID,Item_Balance,Sub_Department_ID) VALUES('$itemId','$balance','$subDepartmentId')";

  if ($insertResult = mysqli_query($conn,$insert_balance)){
    checkItemBalance($itemId,$subDepartmentId);
  }else {
    echo mysqli_error($conn);
    var_dump(mysqli_error($conn));
  }

}

 ?>
