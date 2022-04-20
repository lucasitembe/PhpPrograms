<?php
    include("./includes/header.php");
    include("./includes/connection.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(isset($_POST['merge_btn'])){
  $to_sponsor_id=$_POST['to_sponsor_id'];
  $from_sponsor_id=$_POST['from_sponsor_id'];
  
  $sql_select_item_price="SELECT *FROM tbl_item_price WHERE Sponsor_ID='$from_sponsor_id'";
  $sql_select_item_price_result=mysqli_query($conn,$sql_select_item_price) or die(mysqli_error($conn));
  $count_merged_price=0;
  $count_price_to_merge=0;
  if(mysqli_num_rows($sql_select_item_price_result)>0){
      while($item_rows=mysqli_fetch_assoc($sql_select_item_price_result)){
          $Item_ID=$item_rows['Item_ID'];
          $Items_Price=$item_rows['Items_Price'];
          
          $sql_insert_item_price="INSERT INTO tbl_item_price(Sponsor_ID,Item_ID,Items_Price) VALUES('$to_sponsor_id','$Item_ID','$Items_Price')";
          $sql_insert_item_price_result=mysqli_query($conn,$sql_insert_item_price) or die(mysql_erro());
          if($sql_insert_item_price_result){
             $count_merged_price++; 
          }
          $count_price_to_merge++;
      }
  }
}
echo "merge price of $count_merged_price iterm ........out of$count_price_to_merge item";
?>
