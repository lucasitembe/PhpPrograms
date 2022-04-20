<?php
    include("./includes/connection.php");
    $Select_Price='';

if(isset($_POST['Sub_Department_ID'])){
          $Sub_Department_ID = $_POST['Sub_Department_ID'];
 }else{
          $Sub_Department_ID = '';
}

if(isset($_POST['item_name'])){
          $item_name = $_POST['item_name'];
}else{
          $item_name="";
}
//die( "SELECT Item_ID FROM tbl_items WHERE Product_Name='$item_name'");
$Item_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Item_ID FROM tbl_items WHERE Product_Name='$item_name'"))['Item_ID'];
// die("SELECT Item_Balance as balance  FROM tbl_items_balance WHERE Item_ID='$Item_ID' AND Sub_Department_ID='$Sub_Department_ID'");
$Select_Price="SELECT Item_Balance as balance  FROM tbl_items_balance WHERE Item_ID='$Item_ID' AND Sub_Department_ID='$Sub_Department_ID'";
$result = mysqli_query($conn,$Select_Price);
if(mysqli_num_rows($result)>0){
          while($row = mysqli_fetch_assoc($result)){          
                    echo $row['balance'];
          }
}else{
          echo 'Not available';
}  