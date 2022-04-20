<?php
@session_start();
include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }
    $start_date=$_GET['start_date'];
    $end_date=$_GET['end_date'];
    $Registration_ID=$_GET['Registration_ID'];
    $Patient_Name=$_GET['Patient_Name'];
    $receipt_number=$_GET['receipt_number'];
    $Transaction_Type=$_GET['Transaction_Type'];
    $channel=$_GET['channel'];
    $filter = '';
    if(isset($_GET['Transaction_Type']) ){

        // elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($patient_list_rows['Transaction_status']) 
        // OR (pp.Billing_Type ='Outpatient Cash' AND pp.Pre_Paid='1') OR (pp.Billing_Type='Inpatient Credit' AND pp.payment_type == 'post')

        $Transaction_Type = $_POST['Transaction_Type'];
        if($Transaction_Type =='Cash'){
            $filter .= " AND (pp.Billing_Type ='Outpatient Cash' OR pp.Billing_Type='Inpatient Cash' OR pp.Billing_Type='patient from outside') AND pp.payment_type = 'pre' and pp.auth_code <> ''";
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
            $filter .= " AND pp.payment_direction='to_nmb'";
        }elseif($channel =='to_crdb'){
            $filter .= " AAND pp.payment_direction='to_crdb'";
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
    $htm='';
    $htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>ALL CASHIER PERFOMANCE REPORT</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.$start_date.'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.$end_date.'</b></td></tr>
            </table>';
    
    
    
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td style="width:50px"><b>S/No</b></td>
                        <td><b>Date And Time</b></td>
                        <td><b>Patient Name</b></td>
                        <td><b>Receipt Number</b></td>
                        <td><b>Auth No</b></td>
                        <td><b>Cash</b></td>
                        <td><b>Credit</b></td>
                        <td><b>Cancelled</b></td>
                        <td><b>Cancel Reason</b></td>
                    </tr>
                </thead>';


  $query = "SELECT pp.Patient_Payment_ID,pp.Payment_Date_And_Time,pr.Patient_Name,pp.auth_code,pp.Billing_Type, pp.Transaction_status, sum((price*quantity)-(discount*quantity)) as Total, pp.payment_type, pp.Pre_Paid,pp.Cancel_transaction_reason  FROM tbl_patient_payments as pp,tbl_patient_registration as pr, tbl_patient_payment_item_list as ppl WHERE pp.Registration_ID=pr.Registration_ID AND ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND pp.Payment_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter  group by pp.Patient_Payment_ID order by pp.Payment_Date_And_Time";
    // die($query);
  // $htm .=  $query;
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
        //    $htm .='<tr><td colspan="10" align=center style="color:red"><b>ATA FOUND</b></td></tr>';
           $htm .=  "<tr class='rows_list'>
                <td>$count_sn.</td>
              <td>$Payment_Date_And_Time</td>
              <td>$Patient_Name</td>
              <td>$Patient_Payment_ID</td>
              <td>$auth_code</td>";
          if(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'patient from outside' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'pre')) && (strtolower($patient_list_rows['Transaction_status']) == 'active') && $patient_list_rows['auth_code'] != ''){
              $htm .=  "<td>".number_format($patient_list_rows['Total'],2)."</td>";
              $htm .=  "<td>0</td>";
              $htm .=  "<td>0</td>";
              $htm .=  "<td>".$patient_list_rows['Cancel_transaction_reason']."</td>";
              $Cash_Total = $Cash_Total + $patient_list_rows['Total'];
          }elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '0') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash')) && (strtolower($patient_list_rows['Transaction_status']) == 'cancelled')){
              $htm .=  "<td>0</td>";
              $htm .=  "<td>0</td>";
              $htm .=  "<td>".number_format($patient_list_rows['Total'])."</td>";
              $htm .=   "<td>".$patient_list_rows['Cancel_transaction_reason']."</td>";
              $Cancelled_Total = $Cancelled_Total + $patient_list_rows['Total'];
          }elseif(((strtolower($patient_list_rows['Billing_Type']) == 'outpatient cash' && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient cash' && strtolower($payment_type) == 'post')) && (strtolower($patient_list_rows['Transaction_status']) == 'active')){
              $htm .=  "<td>0</td>";
              $htm .=  "<td>".number_format($patient_list_rows['Total'],2)."</td>"; 
              $htm .=  "<td>0</td>";
              $htm .=   "<td>".number_format($patient_list_rows['Cancel_transaction_reason'],2)."</td>";
              $Credit_Total = $Credit_Total + $patient_list_rows['Total'];
          }elseif(((strtolower(($patient_list_rows['Billing_Type']) == 'outpatient cash' || ($patient_list_rows['Billing_Type']) == 'inpatient cash') && $patient_list_rows['Pre_Paid'] == '1') || (strtolower($patient_list_rows['Billing_Type']) == 'outpatient credit') || (strtolower($patient_list_rows['Billing_Type']) == 'inpatient credit')) && (strtolower($patient_list_rows['Transaction_status']) == 'cancelled')){
              $htm .=  "<td>0</td>";
              $htm .=  "<td>0</td>";
              $htm .=  "<td>". number_format($patient_list_rows['Total'])."</td>";
              $htm .=   "<td>".number_format($patient_list_rows['Cancel_transaction_reason'],2)."</td>";
              $Cancelled_Total = $Cancelled_Total + $patient_list_rows['Total'];
          }
          $count_sn++;
          $htm .=  "<tr>";
        }
        $htm .=  "<tr>
                <td colspan='10'><hr></td>
              </tr>";
        $htm .=  "
              <tr class='rows_list' style='font-size:18px;'>
                      <td colspan='2'><b>Total Cash Transaction : </b></td>
                      <td>". number_format($Cash_Total,2)."</td>
                      <td colspan='2'><b>Total Credit Transaction</b></td>
                      <td colspan=''>".number_format($Credit_Total,2)."</td>
                      <td colspan=''><b>Total</b></td>
                      <td colspan='2'>".number_format(($Cash_Total+$Credit_Total+$$Cancelled_Total),2)."</td>
                  </a>
              </tr>
              ";
  
        $htm .=  "<tr>
        <td colspan='10'><hr></td>
      </tr>";
    }else{
        $htm .='<tr><td colspan="10" align=center style="color:red"><b>NO DATA FOUND</b></td></tr>';
    }
        
        

    
    $htm .= "</table>";
    header("Content-Type:application/xls");
    header("content-disposition: attachment; filename=cashiercollectionreport.xls");
    echo $htm;