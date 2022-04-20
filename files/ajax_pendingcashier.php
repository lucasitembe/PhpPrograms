<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['cashier'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  $sangira_code=$_POST['sangira_code'];
  $payment_direction=$_POST['payment_direction'];
  $filter = '';
  if(isset($_POST['cashier']) && !empty($_POST['cashier'])){
      $employee_id = $_POST['cashier'];
      $filter = " AND tbl_card_and_mobile_payment_transaction.Employee_ID='$employee_id'";
  }
  if(isset($_POST['transaction']) && !empty($_POST['transaction'])){
    $transaction = $_POST['transaction'];
    $filter .= " AND tbl_card_and_mobile_payment_transaction.transaction_status='$transaction'";
}
if(!empty($sangira_code)){
    $filter.="AND  tbl_card_and_mobile_payment_transaction.bill_payment_code ='$sangira_code'";
 }
 if(!empty($Patient_Name)){
    $filter.="AND tbl_patient_registration.Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($Registration_ID)){
     $filter.="AND  tbl_patient_registration.Registration_ID ='$Registration_ID'";
  }
  if(!empty($sangira_code)){
    $filter.="AND  tbl_card_and_mobile_payment_transaction.bill_payment_code ='$sangira_code'";
 }
 if($payment_direction != 'All'){
    $filter.="AND tbl_card_and_mobile_payment_transaction.payment_direction = '$payment_direction'";
  }
  $pendingtransaction = 0;
  $complete = 0;
  
  $query = "SELECT * FROM tbl_card_and_mobile_payment_transaction,tbl_employee,tbl_patient_registration WHERE
      tbl_card_and_mobile_payment_transaction.Registration_ID=tbl_patient_registration.Registration_ID AND tbl_card_and_mobile_payment_transaction.Employee_ID=tbl_employee.Employee_ID 
      AND tbl_card_and_mobile_payment_transaction.transaction_date_time BETWEEN '$start_date' AND '$end_date' AND bill_payment_code !='' $filter ";

  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Gender=$patient_list_rows['Gender'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
         $Payment_Date_And_Time=$patient_list_rows['transaction_date_time'];
         $bill_payment_code=$patient_list_rows['bill_payment_code'];
         $payment_amount=$patient_list_rows['payment_amount'];
         $transaction_status=$patient_list_rows['transaction_status'];
         $bank = $patient_list_rows['payment_direction'];      
         if($bank == 'to_nmb'){
            $bank='NMB';
          }elseif($bank == 'to_crdb'){
            $bank='CRDB';
          }elseif($bank == 'to_azania'){
            $bank='AZANIA';
          }
         if($transaction_status == "pending"){
             $pendingtransaction += $payment_amount;
             $change_color_style = "style='background:yellow;color:black;'";
         }else{
             $complete += $payment_amount;
             $change_color_style = "style='background:green;color:white;'";
         }
         
         echo "
                <tr class='rows_list'  >
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Gender</td>
                        <td>$Employee_Name</td>
                        <td>$bill_payment_code</td>
                        <td>".number_format($payment_amount,2)."</td>
                        <td>$Payment_Date_And_Time</td>
                        <td>$bank</td>
                        <td $change_color_style>$transaction_status</td>
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
      echo "<tr>
              <td colspan='10'><hr></td>\
            </tr>";
      echo "
            <tr class='rows_list' style='font-size:18px;'>
                    <td colspan='2'></td>
                    <td colspan='2'><b>Total Pending Transaction : </b></td>
                    <td>". number_format($pendingtransaction,2)."</td>
                    <td></td>
                    <td colspan='3'><b>Total Complete Transaction</b></td>
                    <td colspan='1'>".number_format($complete,2)."</td>
                </a>
            </tr>
            ";

      echo "<tr>
      <td colspan='10'><hr></td>\
    </tr>";
  }
  
}




