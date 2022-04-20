<?php
include("./includes/connection.php");
if(isset($_GET['start_date'])&&isset($_GET['end_date'])&&isset($_GET['Product_ID'])&&isset($_GET['Product_Name'])){
  $start_date=$_GET['start_date'];
  $end_date=$_GET['end_date'];
  $Product_ID=$_GET['Product_ID'];
  $Product_Name=$_GET['Product_Name'];
  
  
  $filter="AND ed.Edited_Time BETWEEN '$start_date' AND '$end_date'";
  if(!empty($Product_Name)){
    $filter.="AND i.Product_Name LIKE '%$Product_Name%'";
  }
  if(!empty($Product_ID)){
     $filter.="AND  i.Product_Code ='$Product_ID'";
  }

  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT i.Product_Name, em.Employee_Name, i.Product_Code, ed.Activity_Done, ed.Edited_Time FROM tbl_item_audit ed, tbl_employee em, tbl_items i WHERE i.Item_ID = ed.Item_ID AND em.Employee_ID = ed.Employee_ID $filter ORDER BY ed.Item_Audit_ID ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Product_Code=$patient_list_rows['Product_Code'];
         $Product_Name=$patient_list_rows['Product_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Edited_Time=$patient_list_rows['Edited_Time'];
         $Activity_Done=$patient_list_rows['Activity_Done'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
        
                
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Product_Code</td>
                        <td>$Product_Name</td>
                        <td>$Activity_Done</td>
                        <td>".ucfirst(ucwords($Employee_Name))."</td>
                        <td>$Edited_Time</td>
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
  }
}
?>