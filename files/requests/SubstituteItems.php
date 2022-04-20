<?php
session_start();
include("../includes/connection.php");
 if(isset($_POST['action'])){
       if($_POST['action']=='ViewItem'){
           $item=$_POST['item'];
           $sponsor=$_POST['sponsor'];
                   $qr2 = "select Product_Name from tbl_items WHERE Item_ID='$item'";
                            $result_item = mysqli_query($conn,$qr2);
                            $row2 = mysqli_fetch_assoc($result_item);
                            echo $row2['Product_Name'];
      }
      
    if ($_POST['action']=='ViewItemPrice') {
         $item=$_POST['item'];
         $sponsor=$_POST['sponsor'];
         $payID=$_POST['payID'];
         $Quantity=$_POST['Quantity'];

         $qr2 = "select Item_ID,Items_Price from tbl_item_price WHERE Item_ID='$item' AND Sponsor_ID='$sponsor'";

                 $result_item = mysqli_query($conn,$qr2);
//                 $num_rows=  mysqli_num_rows($result_item);
                 $row2 = mysqli_fetch_assoc($result_item);
                 echo $row2['Items_Price'];
                      if($result_item){
                        //GETTING PAYMENT ID
                        $Patient_Payment_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$payID'"))['Patient_Payment_ID'];
                        if(($Patient_Payment_ID) > 0){

                          //VERIFYING BILL TYPE
                          $Billing_Type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Billing_Type FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'"))['Billing_Type'];
                              if((($Billing_Type) == 'Outpatient Credit') || ($Billing_Type) == 'Inpatient Credit'){
                                  $Update= mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Price='".$row2['Items_Price']."',Item_ID='$item',Quantity='$Quantity' WHERE Patient_Payment_Item_List_ID='$payID'");
                                }
                        }else{
                          echo "THE PATIENT SHOULD PAY FOR THIS SERVICES";
                        }
                      
                      }  
                
  }
    
    if($_POST['action']=='ViewItemSearch'){
      
      $item=mysqli_real_escape_string($conn,$_POST['item']);  
      if($item==''|| $item=='NULL'){
        $qr2 = "select * from tbl_items WHERE Can_Be_Substituted_In_Doctors_Page='yes'";  
      }  else {
         $qr2 = "select * from tbl_items WHERE Product_Name LIKE '%$item%'  AND Can_Be_Substituted_In_Doctors_Page='yes'"; 
//         echo $qr2;
      }
      
      $result_item = mysqli_query($conn,$qr2);
        while ($row2 = mysqli_fetch_assoc($result_item)) {
            echo '<tr class="tr" id="'.$row2['Item_ID'].'"><td>'.$row2['Product_Name'].'</tr></td>';  

        }
        
    }
    
   

   }
   
?>
