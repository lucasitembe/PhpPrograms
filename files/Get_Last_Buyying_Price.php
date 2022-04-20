<?php
    include("./includes/connection.php");
    include("./functions/items.php");
    
    if(isset($_GET['Item_ID'])){
        $Item_ID = mysqli_real_escape_string($conn,$_GET['Item_ID']);
    }else{
        $Item_ID = '';
    }
    $Last_Buy_Price = Get_Last_Buy_Price($Item_ID);
    echo $Last_Buy_Price;

    /*if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
    $sql="
          SELECT Buying_Price FROM tbl_purchase_order_items WHERE Item_ID='$Item_ID' ORDER BY Order_Item_ID DESC LIMIT 1
           ";
    $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
    $priceDm=0;
    if(mysqli_num_rows($result) > 0){
        $price=  mysqli_fetch_assoc($result)['Buying_Price'];
        if(empty($price)){
           $priceDm=0;
        }else{
           echo $price=$price; 
        }
    }  else {
      $priceDm=0;
   }
   echo $priceDm;*/
?>