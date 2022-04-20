<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['Registration_ID'])&&isset($_POST['Patient_Name'])&&isset($_POST['sangira_code'])&&isset($_POST['payment_direction'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  $sangira_code=$_POST['sangira_code'];
  $sangira_status=$_POST['sangira_status'];
  $payment_direction=$_POST['payment_direction'];
  
  $filter="AND tc.transaction_date_time BETWEEN '$start_date' AND '$end_date'";
  if(!empty($Patient_Name)){
    $filter.="AND pr.Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($Registration_ID)){
     $filter.="AND  pr.Registration_ID ='$Registration_ID'";
  }
  if(!empty($sangira_code)){
    $filter.="AND  tc.bill_payment_code ='$sangira_code'";
 }
 if($sangira_status != 'All'){
  $filter.="AND  tc.transaction_status ='$sangira_status'";
}
if($payment_direction != 'All'){
  $filter.="AND tc.payment_direction = '$payment_direction'";
}
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT tc.payment_amount,tc.bill_payment_code,pr.Registration_ID,pr.Patient_Name,emp.Employee_Name, tc.transaction_status,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID,tc.transaction_date_time,tc.patient_phone,tc.payment_direction FROM `tbl_card_and_mobile_payment_transaction` tc,tbl_patient_registration pr, tbl_employee emp WHERE pr.`Registration_ID`=tc.`Registration_ID` AND emp.Employee_ID = tc.Employee_ID $filter ORDER BY tc.transaction_date_time ASC") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
         $Gender=$patient_list_rows['Gender'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $Payment_Date_And_Time=$patient_list_rows['transaction_date_time'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
         $transaction_status = $patient_list_rows['transaction_status'];
         $bill_payment_code = $patient_list_rows['bill_payment_code'];
         $payment_direction = $patient_list_rows['payment_direction'];
         $Amount = $patient_list_rows['payment_amount'];
         
         $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
            if ($payment_direction == 'to_nmb'){
                $bank = 'NMB Bank';
            }elseif($payment_direction == 'to_crdb'){
                $bank = 'CRDB Bank';
            }

            if ($transaction_status == 'completed'){
              $status = 'Completed';
            }elseif($transaction_status == 'pending'){
                $status = 'Pending';
            }
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Phone_Number</td>
                        <td>$Gender</td>
                        <td>$Guarantor_Name</td>
                        <td>$Employee_Name</td>
                        <td>$Payment_Date_And_Time</td>
                        <td>$bank</td>
                        <td>$bill_payment_code</td>";
  
                        if($status == 'Completed'){
                            echo "<td style='background: #28d209;'><b>$status</b></td>";
                          }elseif($status == 'Pending'){
                            echo "<td style='background: #FFC300;'><b>$status</b></td>";
                          };
                         
                        echo "<td style='text-align: right;'>".number_format($Amount)."</td>
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
  }
}
?>