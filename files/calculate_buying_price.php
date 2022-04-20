<?php


  function get_item_buying_price($Item_ID,$Dispense_Date_Time,$Sub_Department_ID){
//        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        $select_list_of_buying_price_result=mysqli_query($conn,"SELECT Approval_Date_Time,Selling_Price,Last_Buying_Price FROM tbl_requisition req INNER JOIN tbl_requisition_items reqi ON req.Requisition_ID=reqi.Requisition_ID WHERE Store_Need='$Sub_Department_ID' AND Item_ID='$Item_ID' AND req.Requisition_Status='Received' ORDER BY Approval_Date_Time DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_list_of_buying_price_result)>0){
            while($buying_price_rows=mysqli_fetch_assoc($select_list_of_buying_price_result)){
                $Approval_Date_Time=$buying_price_rows['Approval_Date_Time'];
                $Selling_Price=$buying_price_rows['Selling_Price'];
                $Last_Buying_Price=$buying_price_rows['Last_Buying_Price'];
                if($Dispense_Date_Time<$Approval_Date_Time){
                    
                }else{
                    if($Selling_Price==0){
                      return $Last_Buying_Price;
                    }
                    if(isset($_GET['buying_selling_price'])&&$_GET['buying_selling_price']=="original_buying_price"){
                        return $Last_Buying_Price;
                    }else{
                       return $Selling_Price;  
                    }
                      
                }
            }
        }
        return "not_seted";
     }