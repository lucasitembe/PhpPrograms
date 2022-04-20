<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    .idadi:hover{
        background: #eee;
        cursor: pointer;
        width: 100% important;
    }
    .row tr:nth-child(even){
        background-color: #eee;
    }
    .row tr:nth-child(odd){
        background-color: #fff;
        
    }
    .row tr:hover{
        background-color: #ccc;
        
    }
</style>
<link rel='stylesheet' href='fixHeader.css'>

<?php
include("./includes/connection.php");
$Service_type = '';
$end_date = '';
$start_date = '';

// $filter = " AND DATE(en.date_of_requisition)= CURDATE()";

if (isset($_GET['filter_works'])) {
    $Date_From = filter_input(INPUT_GET, 'Date_From');
    $Date_To = filter_input(INPUT_GET, 'Date_To');
    $Service_type = filter_input(INPUT_GET, 'Service_type');
}


if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = " AND Transaction_Date_And_Time BETWEEN '$Date_From' AND  '$Date_To' ";
}else{
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;

    $filter = " AND Transaction_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'";

}

if ($Service_type != 'All') {
    $filter .=" AND Check_In_Type = '$Service_type'";
    $filterigtem =" AND Check_In_Type = '$Service_type'";
}

$Ward_ID = $_GET['Ward_ID'];
// echo $Ward_ID;
// exit();

if($Ward_ID != 'All'){
    $filter_in = " AND ad.Hospital_Ward_ID = '$Ward_ID' ";
}else{
    $filter_in = '';
}



echo '<center><table width ="100%" style="background-color:white;" id="patients-list" class="row fixTableHead">';
echo '<thead>
                <tr style="font-size:13px; float: postition: static;">
                    <td width=2%><b>SN</b></td>
                    <td width="8%"><b>Patient Name</b></td>
                    <td width="4%" style="text-align: center;"><b>Patient Number</b></td>
                    <td width="15%"><b>Service Name</b></td>
                    <td width="5%"><b>Service Type</b></td>
                    <td width="5%"><b>Requested Date</b></td>
                    <td width="6%"><b>Requested By</b></td>
                    <td width="6%"><b>Payment Status</b></td>
                    <td width="7%"><b>Location/Ward</b></td>
		        </tr>
                </thead>
                ';
            
                
$admission_details = mysqli_query($conn, "SELECT pc.Payment_Cache_ID, pr.Patient_Name, pr.Registration_ID, em.Employee_Name, hw.Hospital_Ward_Name, ad.Bed_Name FROM tbl_admission ad, tbl_payment_cache pc, tbl_hospital_ward hw, tbl_employee em, tbl_patient_registration pr WHERE hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND em.Employee_ID = pc.Employee_ID AND ad.Admission_Status <> 'Discharged' AND pr.Registration_ID = pc.Registration_ID AND ad.Registration_ID = pc.Registration_ID  AND pc.Payment_Cache_ID IN(SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Status IN('paid','active') $filter) $filter_in") or die(mysqli_error($conn));

$temp = 1;

while($row = mysqli_fetch_assoc($admission_details)){
    $Payment_Cache_ID = $row['Payment_Cache_ID'];
    $Patient_Name = $row['Patient_Name'];
    $Registration_ID = $row['Registration_ID'];
    $Hospital_Ward_Name = $row['Hospital_Ward_Name'];
    $Bed_Name = $row['Bed_Name'];
    $Employee_Name = $row['Employee_Name'];

    $select_items = mysqli_query($conn, "SELECT Product_Name, Price, Quantity, Check_In_Type, ilc.Status, Transaction_Type, Transaction_Date_And_Time, Consultant_ID FROM  tbl_item_list_cache ilc, tbl_items i WHERE Payment_Cache_ID='$Payment_Cache_ID'  AND ilc.Item_ID= i.Item_ID $filterigtem") or die(mysqli_error($conn));
    $products = '';		
    $Total_amount = 0;
    $num=1;
    $Sn=1;
    $Type='';
    $Date='';
    $malipo='';
    $Billing = '';
    while($rowD = mysqli_fetch_assoc($select_items)){
        $Product_Name = $rowD['Product_Name'];
        // $Price = $rowD['Price'];
        $Transaction_Date_And_Time = $rowD['Transaction_Date_And_Time'];
        $Check_In_Type = $rowD['Check_In_Type'];
        $Transaction_Type = $rowD['Transaction_Type'];
        $Status = $rowD['Status'];
        // $Amount = ($Price * $Quantity);


        if($Transaction_Type == 'Cash'){
            if($Status == 'paid'){
                $malipo = 'Cash Paid';
            }else{
                $malipo = 'Not Paid';
            }
        }else{
            if($Status == 'paid'){
                $malipo = 'Billed';
            }else{
                $malipo = 'Not Billed';
            }
        }
        $j++;
        if ($numberOfItem == 1) {
            $products ='<b>'. $num.'. </b> '.$Product_Name;
        } else {
            if ($track < $numberOfItem) {
                $products .= '<b>'.$num.'.</b>  '.$Product_Name.'<br/> ';
            } else {
                $products .='<b>'. $num.'. </b> '.$Product_Name .'<br/> ';				
            }
        }

        if ($numberOfcheck == 1) {
            $Type ='<b>'. $Sn.'. </b> '.$Check_In_Type;
        } else {
            if ($trackss < $numberOfcheck) {
                $Type = '<b>'.$Sn.'.</b>  '.$Check_In_Type.'<br/> ';
            } else {
                $Type .='<b>'. $Sn.'. </b> '.$Check_In_Type .'<br/> ';				
            }
        }

        if ($numberOfWard == 1) {
            $Date ='<b>  '.$malipo.'</b>';
        } else {
            if ($trackss < $numberOfWard) {
                $Date .= '<b> '.$malipo.'</b><br/> ';
            } else {
                $Date .='<b>'.$malipo .'</b><br/> ';				
            }
        }

        if ($numberOfBill == 1) {
            $Billing =' '.$Transaction_Date_And_Time;
        } else {
            if ($trackss < $numberOfBill) {
                $Billing .=$Transaction_Date_And_Time.'<br/> ';
            } else {
                $Billing .=$Transaction_Date_And_Time .'<br/> ';				
            }
        }

        $num++;
        $Sn++;
        // $Total_amount +=$Amount;
    }

        echo "<tr class='idadi'><td>" . $temp . "</td>";
        echo "<td>" . $Patient_Name . "</td>";
        echo "<td>" . $Registration_ID . "</td>";
        echo "<td>" . $products. "</td>";
        echo "<td>" . $Type . "</td>";
        echo "<td>" . $Billing."</td>";
        echo "<td>" . $Employee_Name."</td>";
        echo "<td>" . $Date."</td>";
        echo "<td>" . $Hospital_Ward_Name . "/".$Bed_Name."</td></tr>";

        $temp++;

}
// $results = mysqli_query($conn, "SELECT  ilc.Item_ID, ilc.Check_In_Type, ilc.Consultant_ID, ilc.Transaction_Date_And_Time, pr.Patient_Name, pr.Registration_ID, i.Product_Name, e.Employee_Name FROM tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_employee e, tbl_payment_cache pc, tbl_items i WHERE pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND pr.Registration_ID = pc.Registration_ID AND e.Employee_ID = ilc.Consultant_ID AND i.Item_ID = ilc.Item_ID AND ilc.Status IN ('active' , 'paid') AND pc.Billing_Type IN('Inpatient Cash', 'Inpatient Credit') $filter ORDER BY pc.Payment_Cache_ID, pc.Registration_ID") or die(mysqli_error($conn));
// $temp = 1;



// while ($row = mysqli_fetch_array($results)) {
   

// }

echo '</table></center>';

mysqli_close($conn);
?>
