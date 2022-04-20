<?php
include("../includes/connection.php");
//ItemID='+ItemID+'&Sponsor='+Sponsor+'&Item_Category_ID='+Item_Category_ID+'&ItemVal='+ItemVal,

$ItemID=$_GET['ItemID'];
$Sponsor=$_GET['Sponsor'];
$Item_Category_ID=$_GET['Item_Category_ID'];
$ItemVal=$_GET['ItemVal'];
$Fast_Track_Price = $_GET['Fast_Track_Price'];
if($Sponsor=="All"){
    $existence=mysql_query("SELECT * FROM tbl_general_item_price WHERE Item_ID='".$ItemID."'");
    $num_rows=  mysql_num_rows($existence);
    if($num_rows>0){
      $updating=mysql_query("UPDATE tbl_general_item_price SET Items_Price='".$ItemVal."' WHERE Item_ID='".$ItemID."'");
      
      echo 'success';
        
    }  else {
      $inserting=mysql_query("INSERT INTO tbl_general_item_price (Item_ID,Items_Price) VALUES ('$ItemID','$ItemVal')"); 
        echo 'success';
    }
} else {
 $existence=mysql_query("SELECT * FROM tbl_item_price WHERE Item_ID='".$ItemID."' AND Sponsor_ID='".$Sponsor."'");
   $num_rows=  mysql_num_rows($existence);
     if($num_rows>0){
      $updating=mysql_query("UPDATE tbl_item_price SET Items_Price='".$ItemVal."' WHERE Item_ID='".$ItemID."' AND Sponsor_ID='".$Sponsor."'");
        echo 'success';
        
    }  else {
      $inserting=mysql_query("INSERT INTO tbl_item_price (Sponsor_ID,Item_ID,Items_Price) VALUES ('$Sponsor','$ItemID','$ItemVal')");  
        echo 'success';
    }
}

//Update Fast_Track_Price
$select = mysql_query("select Item_Price from tbl_fast_track_price where Item_ID = '$ItemID'") or die(mysql_error());
$num = mysql_num_rows($select);
if($num > 0){
  mysql_query("update tbl_fast_track_price set Item_Price = '$Fast_Track_Price' where Item_ID = '$ItemID'");
}else{
  mysql_query("insert into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$ItemID','$Fast_Track_Price')");
}
?>