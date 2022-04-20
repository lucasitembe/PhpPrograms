<?php
session_start();
include("../includes/connection.php");
 if(isset($_POST['action'])){
       if($_POST['action']=='ViewItem'){
           $item=$_POST['item'];
           $sponsor=$_POST['sponsor'];
                   $qr2 = "select Product_Name from tbl_items WHERE Item_ID='$item'";
                            $result_item = mysql_query($qr2);
                            $row2 = mysql_fetch_assoc($result_item);
                            echo $row2['Product_Name'];
      }
      
    if ($_POST['action']=='ViewItemPrice') {
         $item=$_POST['item'];
         $sponsor=$_POST['sponsor'];
         $payID=$_POST['payID'];
         $Quantity=$_POST['Quantity'];

         $qr2 = "select Item_ID,Items_Price from tbl_item_price WHERE Item_ID='$item' AND Sponsor_ID='$sponsor'";

                 $result_item = mysql_query($qr2);
//                 $num_rows=  mysql_num_rows($result_item);
                 $row2 = mysql_fetch_assoc($result_item);
                 echo $row2['Items_Price'];
               if($result_item){
                 $Update= mysql_query("UPDATE tbl_patient_payment_item_list SET Price='".$row2['Items_Price']."',Item_ID='$item',Quantity='$Quantity' WHERE Patient_Payment_Item_List_ID='$payID'");
                   
               }  
          
    }
      
    
    if($_POST['action']=='ViewItemSearch'){
      
      $item=mysql_real_escape_string($_POST['item']);  
      if($item==''|| $item=='NULL'){
        $qr2 = "select * from tbl_items WHERE Can_Be_Substituted_In_Doctors_Page='yes'";  
      }  else {
         $qr2 = "select * from tbl_items WHERE Product_Name LIKE '%$item%'  AND Can_Be_Substituted_In_Doctors_Page='yes'"; 
//         echo $qr2;
      }
      
      $result_item = mysql_query($qr2);
        while ($row2 = mysql_fetch_assoc($result_item)) {
            echo '<tr class="tr" id="'.$row2['Item_ID'].'"><td>'.$row2['Product_Name'].'</tr></td>';  

        }
        
    }
    
   

   }
   
?>
