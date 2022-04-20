<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  $receipt_number=$_POST['receipt_number'];
  $Transaction_Type=$_POST['Transaction_Type'];
  $channel=$_POST['channel'];
  $filter = '';
    if(isset($_POST['Transaction_Type']) ){

        // elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($patient_list_rows['Transaction_status']) 
        // OR (pp.Billing_Type ='Outpatient Cash' AND pp.Pre_Paid='1') OR (pp.Billing_Type='Inpatient Credit' AND pp.payment_type == 'post')

        $Transaction_Type = $_POST['Transaction_Type'];
        if($Transaction_Type =='Cash'){
            $filter .= " AND (pp.Billing_Type ='Outpatient Cash' OR pp.Billing_Type='Inpatient Cash' OR pp.Billing_Type='patient from outside') ";
        }elseif($Transaction_Type =='Credit'){
            $filter .= " AND (pp.Billing_Type ='Outpatient Credit' OR pp.Billing_Type='Inpatient Credit')";
           
            // $filter .= " AND (pp.Billing_Type ='Outpatient Credit' OR pp.Billing_Type='Inpatient Credit') OR (pp.Billing_Type ='Inpatient Cash' and pp.payment_type = 'post')";

            
        }elseif($Transaction_Type =='cancelled'){
            $filter .= " AND pp.Transaction_status='cancelled'";
        }elseif($Transaction_Type =='All'){
            $filter .= "";
        }
    }
   
    if(isset($_POST['channel']) ){
        $channel = $_POST['channel'];
        if($channel =='to_nmb'){
            $filter .= " AND pp.payment_direction='to_nmb' AND pp.auth_code !=''";
        }elseif($channel =='to_crdb'){
            $filter .= " AND pp.payment_direction='to_crdb' AND pp.auth_code !=''";
        }elseif($channel =='manual'){
            $filter .= " AND pp.manual_offline = 'manual'";
        }elseif($channel =='crdb_pos'){
            $filter .= " AND (pp.Payment_Code != '' AND (pp.payment_mode= 'CRDB' OR pp.payment_mode= 'CRDB..' ))";
        }elseif($channel =='All'){
            $filter .= "";
        }
    }
 if(!empty($Patient_Name)){
    $filter.="AND pr.Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($receipt_number)){
     $filter.="AND  pp.Patient_Payment_ID ='$receipt_number'";
  }
  if(!empty($Registration_ID)){
    $filter.="AND  pr.Registration_ID ='$Registration_ID'";
 }
}
  
  $query = "SELECT pp.Patient_Payment_ID,pp.Payment_Date_And_Time,pr.Patient_Name,pp.auth_code,pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid,pp.Cancel_transaction_reason,pp.payment_mode,pp.Payment_Code,pp.manual_offline  FROM tbl_patient_payments as pp,tbl_patient_registration as pr, tbl_patient_payment_item_list as ppl WHERE pp.Registration_ID=pr.Registration_ID AND ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter  group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time";
//   die($query);
// echo $query;
  $sql_list_of_receipt=mysqli_query($conn,$query) or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_list_of_receipt)>0){
        $count_sn=1;
        $Cash_Total = 0;
        $Credit_Total = 0;
        $Cancelled_Total = 0;
      while($patient_list_rows=mysqli_fetch_assoc($sql_list_of_receipt)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Employee_Name=$patient_list_rows['Employee_Name'];
         $Patient_Payment_ID=$patient_list_rows['Patient_Payment_ID'];
         $Payment_Date_And_Time=$patient_list_rows['Payment_Date_And_Time'];
         $auth_code=$patient_list_rows['auth_code'];
         $transaction_status=$patient_list_rows['transaction_status'];
         $payment_type = $patient_list_rows['payment_type'];
         $Cancel_transaction_reason = $patient_list_rows['Cancel_transaction_reason'];
         echo "<tr class='rows_list'>
            <td>$count_sn.</td>
            <td>$Payment_Date_And_Time</td>
            <td>$Patient_Name</td>
            <td><a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualSummaryReport=IndividualSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$Patient_Payment_ID."</a></td>
            <td>$auth_code</td>";

        // if(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'patient from outside' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'pre')) && (strtolower($patient_list_rows['Transaction_status']) == 'active') && $patient_list_rows['auth_code'] != ''){
            if(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash'&& $patient_list_rows['Pre_Paid']==0) ||(strtolower($Billing_Type) =="patient from outside"  && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && $patient_list_rows['payment_type'] == 'pre')) && strtolower($patient_list_rows['Transaction_status']) != 'cancelled'  && ($patient_list_rows['auth_code'] != '' || $patient_list_rows['manual_offline'] = 'manual' || ($patient_list_rows['Payment_Code'] != '' && ($patient_list_rows['payment_mode']== 'CRDB' || $patient_list_rows['payment_mode']== 'CRDB..' )))){
            echo "<td>".number_format($patient_list_rows['Total'],2)."</td>";
            echo "<td>0</td>";
            echo "<td>0</td>";
            echo "<td>".$patient_list_rows['Cancel_transaction_reason']."</td>";
            $Cash_Total = $Cash_Total + $patient_list_rows['Total'];
        }elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash')) && (strtolower($patient_list_rows['Transaction_status']) == 'cancelled')){
            echo "<td>0</td>";
            echo "<td>0</td>";
            echo "<td>".number_format($patient_list_rows['Total'])."</td>";
            echo  "<td>".$patient_list_rows['Cancel_transaction_reason']."</td>";
            $Cancelled_Total = $Cancelled_Total + $patient_list_rows['Total'];
        }elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($patient_list_rows['Transaction_status']) == 'active')){
            echo "<td>0</td>";
            echo "<td>".number_format($patient_list_rows['Total'],2)."</td>"; 
            echo "<td>0</td>";
            echo  "<td>".number_format($patient_list_rows['Cancel_transaction_reason'],2)."</td>";
            $Credit_Total = $Credit_Total + $patient_list_rows['Total'];
        }elseif(((strtolower(($patient_list_rows['Billing_Type']) == 'outpatient cash' || ($patient_list_rows['Billing_Type']) == 'inpatient cash') && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit')) && (strtolower($patient_list_rows['Transaction_status']) == 'cancelled')){
            echo "<td>0</td>";
            echo "<td>0</td>";
            echo "<td>". number_format($patient_list_rows['Total'])."</td>";
            echo  "<td>".number_format($patient_list_rows['Cancel_transaction_reason'],2)."</td>";
            $Cancelled_Total = $Cancelled_Total + $patient_list_rows['Total'];
        }
        //  echo "<td><a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$row['Patient_Payment_ID']."&IndividualSummaryReport=IndividualSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Patient_Payment_ID']."</a></td>";
        //  echo "<tr class='rows_list'>
        //                 <td>$count_sn.</td>
        //                 <td>$Payment_Date_And_Time</td>
        //                 <td>$Patient_Name</td>
        //                 <td>$Patient_Payment_ID</td>
        //                 <td>$auth_code</td>
        //                 <td>".number_format($Cash_Total,2)."</td>
        //                 <td>".number_format($Credit_Total,2)."</td>
        //                 <td>".number_format($Cancelled_Total,2)."</td>
        //                 <td>$Cancel_transaction_reason</td>
        //             </a>
        //         </tr>";
        $count_sn++;
          
      }
      echo "<tr>
              <td colspan='10'><hr></td>
            </tr>";
      echo "
            <tr class='rows_list' style='font-size:18px;'>
                    <td colspan=''></td>
                    <td colspan=''><b>Total Cash Transaction : </b></td>
                    <td>". number_format($Cash_Total,2)."</td>
                    <td></td>
                    <td colspan=''><b>Total Credit Transaction</b></td>
                    <td colspan=''>".number_format($Credit_Total,2)."</td>
                    <td colspan=''><b>Total</b></td>
                    <td colspan=''>".number_format(($Cash_Total+$Credit_Total+$$Cancelled_Total),2)."</td>
                </a>
            </tr>
            ";

      echo "<tr>
      <td colspan='10'><hr></td>
    </tr>";
  }
  





