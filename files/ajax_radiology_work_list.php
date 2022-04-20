<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['imageID'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $sampleID=$_POST['imageID'];
  $status = "Unknown";
  $filter="Upload_Date BETWEEN '$start_date' AND '$end_date' AND Item_ID='$sampleID'";
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT * FROM tbl_radiology_image WHERE $filter ") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $item_ID=$patient_list_rows['Item_ID'];
         $date=$patient_list_rows['Upload_Date'];
         $image=$patient_list_rows['patient_image'];
                
         //filter only patient with active or approved item
         if($image != ""){
            $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$item_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_active_or_approved_item_result)>0){
               $status = "Known";
            } 
       
           //sql select payment sponsor
           if ($BillControlNumber > 0){
               echo "
                   <tr class='rows_list'>
                           <td>$count_sn.</td>
                           <td>$item_ID</td>
                           <td>$status</td>
                           <td>$date</td>
                       </a>
                   </tr>
                   ";
            $count_sn++;
               
           }
         }
         
      }
  }
}




