<?php
include("../includes/connection.php");
//ItemID='+ItemID+'&Sponsor='+Sponsor+'&Item_Category_ID='+Item_Category_ID+'&ItemVal='+ItemVal,

$Employee_ID=$_GET['Employee_ID'];
$ItemID=$_GET['ItemID'];
$Sponsor=$_GET['Sponsor'];
$Item_Category_ID=$_GET['Item_Category_ID'];
$ItemVal=$_GET['ItemVal'];
$Fast_Track_Price = $_GET['Fast_Track_Price'];
if($Sponsor=="All"){
//    $existence=mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='".$ItemID."'");
//    $num_rows=  mysqli_num_rows($existence);
//    if($num_rows>0){
//      $updating=mysqli_query($conn,"UPDATE tbl_general_item_price SET Items_Price='".$ItemVal."' WHERE Item_ID='".$ItemID."'");
//      
//      echo 'success';
//        
//    }  else {
//      $inserting=mysqli_query($conn,"INSERT INTO tbl_general_item_price (Item_ID,Items_Price) VALUES ('$ItemID','$ItemVal')"); 
//        echo 'success';
//    }
} else {
 $existence=mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Item_ID='".$ItemID."' AND Sponsor_ID='".$Sponsor."'");
   $num_rows=  mysqli_num_rows($existence);
     if($num_rows>0){
      $updating=mysqli_query($conn,"UPDATE tbl_item_price SET last_updated_by='$Employee_ID',Items_Price='".$ItemVal."' WHERE Item_ID='".$ItemID."' AND Sponsor_ID='".$Sponsor."'");
        echo 'success';
        
    }  else {
        $inserting=mysqli_query($conn,"INSERT INTO tbl_item_price (Sponsor_ID,Item_ID,Items_Price,last_updated_by) VALUES ('$Sponsor','$ItemID','$ItemVal','$Employee_ID')");  
        echo 'success';
    }
}

//Update Fast_Track_Price
$select = mysqli_query($conn,"select Item_Price from tbl_fast_track_price where Item_ID = '$ItemID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if($num > 0){
  mysqli_query($conn,"update tbl_fast_track_price set Item_Price = '$Fast_Track_Price' where Item_ID = '$ItemID'");
}else{
  mysqli_query($conn,"insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$ItemID','$Fast_Track_Price')");
}
?>