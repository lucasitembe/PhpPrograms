 <?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['Registration_ID'])&&isset($_POST['Patient_Name'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  $filter="AND pc.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' AND Patient_Name LIKE '%$Patient_Name%' AND pr.Registration_ID LIKE '%$Registration_ID%'";
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT pr.Registration_ID,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID,pc.Payment_Date_And_Time,pc.Payment_Cache_ID FROM tbl_patient_registration pr,tbl_payment_cache pc WHERE pr.Registration_ID=pc.Registration_ID AND Billing_Type IN('Inpatient Cash','Outpatient Cash') $filter ORDER BY Payment_Date_And_Time ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Payment_Cache_ID=$patient_list_rows['Payment_Cache_ID'];
                
         //filter only patient with active or approved item
         $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT Item_Name,gepg_bill_id FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND `Status` ='active'") or die(mysqli_error($conn));
         if(mysqli_num_rows($sql_select_active_or_approved_item_result)>0){
            while($patient_list_rows=mysqli_fetch_assoc($sql_select_active_or_approved_item_result)){
                $Item_Name=$patient_list_rows['Item_Name'];
                $gepg_bill_id=$patient_list_rows['gepg_bill_id'];
                
                $sql_select_active_or_approved_item_result2=mysqli_query($conn,"SELECT TrxId,BillAmt,PaidAmt,PayCtrNum,TrxDtTm FROM tbl_billpymentinfo WHERE BillId='$gepg_bill_id'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_active_or_approved_item_result2)>0){
                   while($patient_list_rows=mysqli_fetch_assoc($sql_select_active_or_approved_item_result2)){
                       $StatusCode=$patient_list_rows['PaidAmt'];
                       $BilledAmount=$patient_list_rows['BillAmt'];
                       $BillGeneratedDate=$patient_list_rows['TrxDtTm'];
                       $BillControlNumber = $patient_list_rows['PayCtrNum'];
                       $TrxId = $patient_list_rows['TrxId'];

                       //sql select payment sponsor
                        if ($BillControlNumber > 0){
                            echo "
                                <tr class='rows_list'>
                                        <td>$count_sn.</td>
                                        <td>$Patient_Name</td>
                                        <td>$Registration_ID</td>
                                        <td>$Item_Name</td>
                                        <td>$gepg_bill_id</td>
                                        <td>$BillControlNumber</td>
                                        <td>$BilledAmount</td>
                                        <td>$StatusCode</td>
                                        <td>$BillGeneratedDate</td>
                                        <td><a onclick='Print_Receipt_Payment(\"$BilledAmount,$StatusCode,$gepg_bill_id,$TrxId,$Registration_ID\")' href='#' class='art-button-green'>APROVE</a></td>\
                                    </a>
                                </tr>
                                ";
                        $count_sn++;
                            
                        }
                   }
                } 
            }
         } 
      }
  }
}




