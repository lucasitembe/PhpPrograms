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
         $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT Item_Name,gepg_bill_id FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
         if(mysqli_num_rows($sql_select_active_or_approved_item_result)>0){
            while($patient_list_rows=mysqli_fetch_assoc($sql_select_active_or_approved_item_result)){
                $Item_Name=$patient_list_rows['Item_Name'];
                $gepg_bill_id=$patient_list_rows['gepg_bill_id'];
                
                $sql_select_active_or_approved_item_result2=mysqli_query($conn,"SELECT BilledAmount,BillGeneratedDate,BillControlNumber,StatusCode FROM bill WHERE BillId='$gepg_bill_id'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_active_or_approved_item_result2)>0){
                   while($patient_list_rows=mysqli_fetch_assoc($sql_select_active_or_approved_item_result2)){
                       $StatusCode=$patient_list_rows['StatusCode'];
                       $BilledAmount=$patient_list_rows['BilledAmount'];
                       $BillGeneratedDate=$patient_list_rows['BillGeneratedDate'];
                       $BillControlNumber = $patient_list_rows['BillControlNumber'];
                       $description = null;
                       if($StatusCode === "7101"){
                           $description = "Transaction Successfull";
                       }if($StatusCode === "7201"){
                           $description = "General Failure";
                       }if($StatusCode === "7204"){
                           $description = "Bill does not exist";
                       }if($StatusCode === "7205"){
                           $description = "Invalid service provider";
                       }if($StatusCode === "7207"){
                           $description = "Duplicate payment";
                       }if($StatusCode === "7283"){
                           $description = "Bill has been cancelled";
                       }if($StatusCode === "7317"){
                           $description = "Error on processing request";
                       }if($StatusCode === "7282"){
                           $description = "Invalid payer email or phone number";
                       }if($StatusCode === "7281"){
                           $description = "Invalid payment transaction date";
                       }
                   }
                } 
            }
         } 
	
        //sql select payment sponsor
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Item_Name</td>
                        <td>$gepg_bill_id</td>
                        <td>$BillControlNumber</td>
                        <td>$BilledAmount</td>
                        <td>$BillGeneratedDate</td>
                        <td>$StatusCode</td>
                        <td>$description</td>\
                    </a>
                </tr>
                ";
         $count_sn++;
      }
  }
}




