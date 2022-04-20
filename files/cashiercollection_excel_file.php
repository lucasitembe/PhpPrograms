<?php         
$filename = "Cashier collection";         
include("./includes/connection.php");

$filter = '';
if(isset($_GET['cashier']) && !empty($_GET['cashier'])){
    $employee_id = $_GET['cashier'];
    $filter = " AND tbl_card_and_mobile_payment_transaction.Employee_ID='$employee_id'";
}
$transaction = $_GET['transaction'];
$Registration_ID=$_GET['Registration_ID'];
$Patient_Name=$_GET['Patient_Name'];
$sangira_code=$_GET['sangira_code'];
$sangira_status=$_GET['sangira_status'];
$payment_direction=$_GET['payment_direction'];
if(isset($_GET['start_date'])){
    $start_date = $_GET['start_date'];
}else{
    $start_date = '';
}

if(isset($_GET['end_date'])){
    $end_date = $_GET['end_date'];
}else{
    $end_date = '';
}
if(isset($_GET['cashier']) && !empty($_GET['cashier'])){
    $employee_id = $_GET['cashier'];
    $filter .= " AND tbl_card_and_mobile_payment_transaction.Employee_ID='$employee_id'";
}
// if(isset($_GET['transaction']) && !empty($_GET['transaction'])){
//     $transaction = $_GET['transaction'];
//     $filter .= " AND tbl_card_and_mobile_payment_transaction.transaction_status='$transaction'";
// }
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

$sql = "SELECT Patient_Name as Name,tbl_patient_registration.Registration_ID,patient_phone As Phone,Gender,Employee_Name AS Employee,transaction_date_time,bill_payment_code as Sangira,payment_amount AS Amount,transaction_status As Status,payment_direction as Bank FROM 
    tbl_card_and_mobile_payment_transaction,tbl_employee,tbl_patient_registration WHERE
      tbl_card_and_mobile_payment_transaction.Registration_ID=tbl_patient_registration.Registration_ID AND tbl_card_and_mobile_payment_transaction.Employee_ID=tbl_employee.Employee_ID 
      AND tbl_card_and_mobile_payment_transaction.transaction_date_time BETWEEN '$start_date' AND '$end_date' AND bill_payment_code !='' $filter ";

$result = mysqli_query($conn,$sql) or die("Couldn't execute query:<br>" . mysqli_error($conn). "<br>" . mysqli_errno($conn)); 
$file_ending = "xls";

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

$sep = "\t"; 
while ($property = mysqli_fetch_field($result)) {
    echo $property->name. "\t";
}

print("\n");    
    while($row = mysqli_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysqli_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }   
?>