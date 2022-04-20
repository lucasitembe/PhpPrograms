<?php
@session_start();
include("./includes/connection.php");
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }
	$end_date = $_GET['end_date'];
	$start_date = $_GET['start_date'];
        $cashier = $_GET['cashier'];
        $transaction = $_GET['transaction'];
        $Registration_ID=$_GET['Registration_ID'];
    $Patient_Name=$_GET['Patient_Name'];
    $sangira_code=$_GET['sangira_code'];
    $sangira_status=$_GET['sangira_status'];
    $payment_direction=$_GET['payment_direction'];
        
        
        $filter = '';
        if(isset($_GET['cashier']) && !empty($_GET['cashier'])){
            $employee_id = $_GET['cashier'];
            $filter .= " AND tbl_patient_registration.Employee_ID='$employee_id'";
        }
        if(!empty($transaction)){
            $transaction = $_GET['transaction'];
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
          if($payment_direction == 'to_nmb'){
            $payment_direction='NMB';
          }elseif($payment_direction == 'to_crdb'){
            $payment_direction='CRDB';
          }elseif($payment_direction == 'to_azania'){
            $payment_direction='AZANIA';
          }
        // if(isset($_GET['Registration_ID']) && !empty($_GET['Registration_ID'])){
        //     $Registration_ID = $_GET['Registration_ID'];
        //     $filter .= " AND tbl_card_and_mobile_payment_transaction.Registration_ID='$Registration_ID'";
        // }
        // if(isset($_GET['Patient_Name']) && !empty($_GET['Patient_Name'])){
        //     $Patient_Name = $_GET['Patient_Name'];
        //     $filter .="AND tbl_patient_registration.Patient_Name LIKE '%$Patient_Name%'";
        // }
        // if(isset($_GET['sangira_code']) && !empty($_GET['sangira_code'])){
        //     $sangira_code = $_GET['sangira_code'];
        //     $filter .= "AND  tbl_card_and_mobile_payment_transaction.bill_payment_code ='$sangira_code'";
        // }
        // if(isset($_GET['payment_direction']) && !empty($_GET['payment_direction'])){
        //     $payment_direction = $_GET['payment_direction'];
        //     $filter .= " AND tbl_card_and_mobile_payment_transaction.payment_direction='$payment_direction'";
        // }
        // if(($_GET['transaction']) !='All'){
        //     $transaction = $_GET['transaction'];
        //     $filter .=  $filter.="AND tbl_card_and_mobile_payment_transaction.payment_direction = '$payment_direction'";
        // }

               $obj  = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$cashier'") or die(mysqli_error($conn));
       $name = mysqli_fetch_assoc($obj)['Employee_Name'];
    $htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>CASHIER REVENUE COLLECTION REPORT BY SANGIRA NUMBER</b></td></tr>
                <tr><td style="text-align:center"><b>START DATE : '.$start_date.'</b></td></tr>
                <tr><td style="text-align:center"><b>END DATE : '.$end_date.'</b></td></tr>
                <tr><td style="text-align:center"><b>CASHIER NAME: '.ucfirst($name).'</b></td></tr>
                <tr><td style="text-align:center"><b>BANK: '.ucfirst($payment_direction).'</b></td></tr>
            </table>';
    
    
    
    $htm .= '<table width=100% border=1 style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <td style="width:50px"><b>SN</b></td>
                        <td><b>Patient Name</b></td>
                        <td><b>Patient Number</b></td>
                        <td><b>Gender</b></td>
                        <td><b>Employee</b></td>
                        <td><b>Sangira Number</b></td>
                        <td><b>Amount</b></td>
                        <td><b>Created Date</b></td>
                        <td><b>Bank</b></td>
                        <td><b>Transaction Status</b></td>
                    </tr>
                </thead>';
        
        
$query = "SELECT * FROM tbl_card_and_mobile_payment_transaction,tbl_employee,tbl_patient_registration WHERE
      tbl_card_and_mobile_payment_transaction.Registration_ID=tbl_patient_registration.Registration_ID AND tbl_card_and_mobile_payment_transaction.Employee_ID=tbl_employee.Employee_ID 
      AND tbl_card_and_mobile_payment_transaction.transaction_date_time BETWEEN '$start_date' AND '$end_date' $filter";
// die($query);
$sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Gender=$patient_list_rows['Gender'];
         $Guarantor_Name=$patient_list_rows['Employee_Name'];
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
         
         $htm .="
                <tr class='rows_list' >
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Gender</td>
                        <td>$Guarantor_Name</td>
                        <td>$bill_payment_code</td>
                        <td>".number_format($payment_amount,2)."</td>
                        <td>$Payment_Date_And_Time</td>
                        <td>$bank</td>
                        <td  $change_color_style>$transaction_status</td>
                        
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
      
      $htm .="
            <tr class='rows_list' style='font-size:18px;'>
                    <td></td>
                    <td colspan='3'><b>Total Pending Transaction : </b></td>
                    <td>". number_format($pendingtransaction,2)."</td>
                    <td colspan='4'><b>Total Complete Transaction</b></td>
                    <td>".number_format($complete,2)."</td>
                </a>
            </tr>
            ";
  }
    
    
    $htm .= "</table>";
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($E_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By eHMS');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;