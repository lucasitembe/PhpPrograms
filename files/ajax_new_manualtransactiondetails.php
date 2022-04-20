<?php
session_start();
include './includes/constants.php';
include("./includes/connection.php");
//include("./includes/connection_epayment.php");
if (isset($_GET['kutokaphamacy'])) {
    $kutokaphamacy = $_GET['kutokaphamacy'];
    if($kutokaphamacy!="yes")$kutokaphamacy="no";
} else {
    $kutokaphamacy = 'no';
}
if (isset($_GET['from_revenue_phamacy'])) {
    $from_revenue_phamacy = $_GET['from_revenue_phamacy'];
    if($from_revenue_phamacy!="yes")$from_revenue_phamacy="no";
} else {
    $from_revenue_phamacy = 'no';
}
if (isset($_SESSION['Transaction_ID'])) {
    $Transaction_ID = $_SESSION['Transaction_ID'];
} else {
    $Transaction_ID = 0;
}

$pay_code = '';
$epaycode = '';
$select = mysqli_query($conn,"select pr.Registration_ID, bt.Payment_Code, bt.Amount_Required,Guarantor_Name, bt.Employee_ID, bt.Transaction_Status, bt.Transaction_ID,
							bt.Transaction_Date_Time, bt.Transaction_Date, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Registration_Date_And_Time
							from tbl_bank_transaction_cache bt,  tbl_sponsor sp, tbl_patient_registration pr where
							pr.Registration_ID = bt.Registration_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
							Transaction_ID = '$Transaction_ID' ") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);

$remoteTransID = 0;
$Payment_Code = 0;
$Registration_ID = 0;

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Transaction_ID = $data['Transaction_ID'];
        $Registration_ID = $data['Registration_ID'];
        $Payment_Code = $data['Payment_Code'];
        $Amount_Required = $data['Amount_Required'];
        $Employee_ID = $data['Employee_ID'];
        $Transaction_Date_Time = $data['Transaction_Date_Time'];
        $Transaction_Date = $data['Transaction_Date'];
        $Transaction_Status = $data['Transaction_Status'];
        $Patient_Name = htmlspecialchars($data['Patient_Name'],ENT_QUOTES);
        $Date_Of_Birth = $data['Date_Of_Birth'];
        $Gender = $data['Gender'];
        $Phone_Number = $data['Phone_Number'];
        $Registration_Date_And_Time = $data['Registration_Date_And_Time'];
        $Guarantor_Name = $data['Guarantor_Name'];

       echo $epaycode = $Payment_Code;

//        if ($Transaction_Status == 'pending') {
//            $sql_epay = "insert into tbl_bank_transaction_cache(P_Name, Registration_ID, Payment_Code, 
//										Amount_Required, Employee_ID, Transaction_Date_Time, 
//										Transaction_Date, Transaction_Status, Phone_Number)
//										values('$Patient_Name','$Registration_ID','$Payment_Code',
//												'$Amount_Required','$Employee_ID','$Transaction_Date_Time',
//												'$Transaction_Date','$Transaction_Status','$Phone_Number')";
//
//            if (saveInfo($sql_epay)) {
//
//                $results = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'uploaded' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
//
//              echo  $pay_code = $Payment_Code;
//
////                $sqlTrans = "SELECT Transaction_ID FROM tbl_bank_transaction_cache ORDER BY Transaction_ID DESC LIMIT 1";
////                $rs = getRecord($sqlTrans)[0];
////                $remoteTransID = $rs['Transaction_ID'];
//            } else {
//                echo "fail";
//            }
//        }
    }
}else{
    echo "failff";
}