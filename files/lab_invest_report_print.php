
<?php

include("./includes/connection.php");


$Guarantor_Name = "All";

$fromDate = DATE('Y-m-d H:m:s');
$toDate = DATE('Y-m-d H:m:s');
$filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Status='Sample Collected'";

$filterSub = '';

if (isset($_GET['fromDate']) ) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $sponsorName = $_GET['Sponsor'];
    $SubCategory = $_GET['SubCategory'];
    $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Status='Sample Collected'";
    if ($sponsorName != 'All') {
        $filter .=" AND pp.Sponsor_ID='$sponsorName'";
        $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$sponsorName'") or die(mysqli_error($conn));

        $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
    }

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
}
//$htm = "<center><img src='branchBanner/branchBanner1.png' width='100%' ></center>";
//$htm.="<p align='center'><b>LABORATORY NUMBER TESTS TAKEN REPORT <br/><br/>FROM</b> " . $fromDate . " <b>TO</b> " . $toDate . ""
//        . "<br/><br/>"
//        . "<b>Sponsor:</b>$Guarantor_Name"
//        . "</p>";

$htm = "<table width ='100%'>
		    <tr><td style='text-align: center;'><span><b>LABORATORY INVESTIGATION REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($fromDate)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($toDate)) . "</b></td></tr>
                    <tr><td style='text-align: center;'><span><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";


$sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter $filterSub AND i.Consultation_Type='Laboratory' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
// echo $sqlCat;exit; 
$querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn));

while ($row1 = mysqli_fetch_array($querySubcategory)) {
    $subcategory_name = $row1['Item_Subcategory_Name'];
    $subcategory_id = $row1['Item_Subcategory_ID'];

    $notIn = array('-1');

    $selectPatients = "SELECT pr.Registration_ID,Patient_Name,Gender,Date_Of_Birth,Guarantor_Name,Employee_Name AS Consultant,ilc.Consultant_ID FROM tbl_item_list_cache ilc JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pp.Registration_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_employee e ON e.Employee_ID=ilc.Consultant_ID $filter  AND i.Item_Subcategory_ID='$subcategory_id' AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

    $select_data_patient_result = mysqli_query($conn,$selectPatients) or die(mysqli_error($conn));
    $noOfPatient = mysqli_num_rows($select_data_patient_result);

    $htm .= "<div style='margin:10px 0px 10px 0px;width:100%;text-align:left;font-family: times;font-size: large;font-weight: bold;background-color:#ccc;padding:4px'>" . ucfirst(strtolower($subcategory_name)) . "<span style='float:right'> </span></div>";
    $htm .= '<center><table width ="100%" border="0" id="patientspecimenCollected" class="display">';
    $htm .= "<thead>
                 <tr><td colspan='8' style='width:100%;border-bottom:1px solid #000'></td></tr>
	            <tr class='headerrow'>
			    <th  style='text-align: left; ' width='3%'>SN</th>
			    <th style='text-align: left;' width='27%'><b>PATIENT NAME</th>
			    <th style='text-align: left;' >REG #</th>
			    <th style='text-align: left;' >SPONSOR</th>
                             <th style='text-align: left;' >GENDER</th>
			    <th style='text-align: left;' >AGE</th>
                            <th style='text-align: left;' width='13%'>REQ. DOCTOR</th>
                            <th style='text-align: left;' width='30%'>EXAM & RESULT</th>
		     </tr>
                     <tr><td colspan='8' style='width:100%;border-bottom:1px solid #000'></td></tr>
           </thead>";

    $count = 1;
    while ($row = mysqli_fetch_array($select_data_patient_result)) {
        $registration_ID = $row['Registration_ID'];
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Consultant = $row['Consultant'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $Consultant_ID = $row['Consultant_ID'];

        //these codes are here to determine the age of the patient
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";

        //get its patient items

        $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND pp.Registration_ID='$Registration_ID' AND i.Item_Subcategory_ID='$subcategory_id' AND ilc.Consultant_ID='$Consultant_ID'  AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

        $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));

        $products = '';
        $numberOfItem = mysqli_num_rows($selected_items);
        $track = 1;
        while ($rowdata = mysqli_fetch_array($selected_items)) {
            $Product_Name = $rowdata['Product_Name'];
            $ppil = $rowdata['Payment_Item_Cache_List_ID'];

            $notIn[] = $ppil;

            if ($numberOfItem == 1) {
                $products = $Product_Name ;
            } else {
                if ($track < $numberOfItem) {
                    $products .=$Product_Name  . ',  ';
                } else {
                    $products .=$Product_Name  . '.';
                }
            }

            $track++;
        }

        if (!empty($products)) {

            $htm .= "<tr><td>" . $count++ . "</td>";
            $htm .= "<td style='text-align:left; '>" . $patientName . "</td>";
            $htm .= "<td style='text-align:left; '>" . $row['Registration_ID'] . "</td>";
            $htm .= "<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
            $htm .= "<td style='text-align:left; '>" . $Gender . "</td>";
            $htm .= "<td style='text-align:left; '>" . $age . "</td>";
            $htm .= "<td style='text-align:left; '>" . $Consultant . "</td>";
            $htm .= "<td style='text-align:left; '>" . $products . "</td>";
            $htm .= " </tr>";
            $htm .= "<tr><td colspan='8' style='width:100%;border-bottom:1px solid #000'></td></tr>";
        }
    }

    $htm .= "</table></center><br/><br/>";
}



header("Content-Type:application/xls");
header("content-Disposition: attachement; filename=Lab Investigation Report.xls");
echo $htm;
exit;
?>



