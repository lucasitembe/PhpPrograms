<style>
    #tabsInfo ul li,h3{
        list-style: none;
        display:inline-block; 
    }
</style>
<?php
include("./includes/connection.php");
@session_start();

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
$consultation_ID = '';
$Registration_ID = '';
$Today = '';

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

//GET LAST CHECLING PAYMENTS

$sql = "SELECT Product_Name,Price,Ward_Round_Date_And_Time FROM tbl_patient_payment_item_list ppl
       JOIN tbl_items i ON i.Item_ID=ppl.Item_ID
       JOIN tbl_ward_round wr ON ppl.Patient_Payment_ID=wr.Patient_Payment_ID
       WHERE wr.Process_Status='served' AND wr.consultation_ID='" . $consultation_ID . "' AND Registration_ID='" . $Registration_ID. "' AND DATE(Ward_Round_Date_And_Time)=DATE(NOW()) ORDER BY wr.Round_ID DESC LIMIT 10";
//die($sql);
$getResultPay = mysqli_query($conn,$sql) or die(mysqli_error($conn));

$lasPayID = '';
$lasPayPrice = '';
$thereIsPay = false;
$clinicalDisplay = "style='display:none'";

$data='<table width="100%" >'
        . '<tr><th>SN</th><th style="text-align:left">DOCTOR TYPE</th><th style="text-align:right">PRICE</th><th style="text-align:center">ROUND DATE</th></tr>';
$sn=1;
while ($row = mysqli_fetch_array($getResultPay)) {
    $data .='<tr><td>'.$sn++.'</td><td>'.$row['Product_Name'].'</td><td style="text-align:right">'.number_format($row['Price']).'</td><td style="text-align:center">'.$row['Ward_Round_Date_And_Time'].'</td></tr>';
}

 $data .='</table>';
echo $data;


