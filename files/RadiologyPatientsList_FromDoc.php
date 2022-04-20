<?php

require_once('includes/connection.php');
if (isset($_GET['Sub_Department_ID']))
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
if (isset($_GET['Patient_Name'])) {
    $Patient_Name = $_GET['Patient_Name'];
} else {
    $Patient_Name = '';
}
if (isset($_GET['DateFrom'])) {
    $DateFrom = $_GET['DateFrom'];
} else {
    $DateFrom = '';
}
if (isset($_GET['DateTo'])) {
    $DateTo = $_GET['DateTo'];
} else {
    $DateTo = '';
}
if (isset($_GET['PatientType'])) {
    $PatientType = $_GET['PatientType'];
} else {
    $PatientType = '';
}
if (isset($_GET['SI'])) {
    $Supervisor_ID = $_GET['SI'];
} else {
    $Supervisor_ID = 0;
}

if (isset($_GET['Sponsor'])) {
    $Sponsor = $_GET['Sponsor'];
} else {
    $Sponsor = 0;
}


$listtype = 'FromDoc';
$apID = 0;
$filter = ' AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) ';

//echo $Supervisor_ID; 
if ($DateFrom != '' && $DateTo != '') {
    $filter .= $filter . " AND ppil.Transaction_Date_And_Time BETWEEN '$DateFrom' AND '$DateTo' ";
}

if ($Patient_Name != '') {
    $filter .= $filter . " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if($Sponsor != 'All'){
             $filter .=" AND sp.Sponsor_ID='$Sponsor'";
}




$select_patients = "
				SELECT
				ppil.Payment_Item_Cache_List_ID,				ppil.Status,pr.Patient_Name,pr.Registration_ID,pp.Billing_Type,ppil.Transaction_Type,pp.Sponsor_Name,i.Product_Name,pr.Phone_Number,pr.Gender,tis.Item_Subcategory_Name,ppil.Transaction_Date_And_Time FROM tbl_item_list_cache ppil 
								INNER JOIN tbl_payment_cache pp ON ppil.Payment_Cache_ID = pp.Payment_Cache_ID 
				JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID 
				JOIN tbl_sponsor sp ON sp.Sponsor_ID = pp.Sponsor_ID 
				JOIN tbl_items i ON ppil.Item_ID = i.Item_ID
				JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID = tis.Item_Subcategory_ID
				WHERE 
					ppil.Status NOT IN  ('served','removed') AND
					ppil.Check_In_Type = 'Radiology' 
				";

//Filtering By Sub_Department
if ($Sub_Department_ID != 0) {
    $select_patients .= " AND i.Item_Subcategory_ID = '$Sub_Department_ID' ";
} elseif ($Sub_Department_ID == 0) {
    //$select_patients .= " AND i.Item_Subcategory_ID <> '21' AND i.Item_Subcategory_ID <> '22'";
}

//echo ($select_patients);exit;
//Filtering Outpatient and Inpatient
if ($PatientType == 'out') {
    $select_patients .= " AND pp.Billing_Type LIKE '%Outpatient%' ";
} elseif ($PatientType == 'in') {
    $select_patients .= " AND pp.Billing_Type LIKE '%Inpatient%' ";
}

//$group_by = '';
$group_by = 'GROUP BY ppil.Payment_Cache_ID';

if ($filter != '') {
    $select_patients_new = $select_patients . $filter . $group_by;
} else {
    $select_patients_new = $select_patients . $group_by;
}

echo '<table width="100%" id="radPatientList">';
echo '<thead>
         <tr style="text-transform:uppercase; font-weight:bold;">

            <th style="width:5%;">SN</th>
            <th>Payment Status</th>
            <th>Patient Name</th>
            <th>Patient No</th>
            <th>Sponsor</th>
            <th>Gender</th>
            <th>Sub Department</th>
            <th>Sent Date and Time</th>

         </tr>

       </thead>
        ';

$select_patients_qry = mysqli_query($conn,$select_patients_new) or die(mysqli_error($conn));
$sn = 1;
while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
    $patient_name = $patient['Patient_Name'];
    $patient_numeber = $patient['Registration_ID'];
    $Billing_Type = $patient['Billing_Type'];
    $Transaction_Type = $patient['Transaction_Type'];
    $Payment_Item_Cache_List_ID = $patient['Payment_Item_Cache_List_ID'];
    $Payment_Status = '';
    $Status = $patient['Status'];

    //Check if Patient is Admitted
    $admitted = "SELECT Registration_ID FROM tbl_admission WHERE Admission_Status = 'Admitted' AND Registration_ID = $patient_numeber";
    $admitted_qry = mysqli_query($conn,$admitted) or die(mysqli_error($conn));
    while ($theadmitted = mysqli_fetch_assoc($admitted_qry)) {
        $apID = $theadmitted['Registration_ID'];
    }

    $sponsor = $patient['Sponsor_Name'];
    $test_name = $patient['Product_Name'];
    $phone_number = $patient['Phone_Number'];
    $gender = $patient['Gender'];
    $subcat = $patient['Item_Subcategory_Name'];
    $Registration_ID = $patient['Registration_ID'];
    $sent_date = $patient['Transaction_Date_And_Time'];
    $hide = "<button>Hide</button>";
    $href = "RadiologyPatientTests.php?Registration_ID=" . $Registration_ID . "&PatientType=" . $PatientType . "&listtype=FromDoc target='_parent'";
    $style = 'style="text-decoration:none;"'; //Outpatient Cash
    $onclick = '';


    if ($Billing_Type == 'Outpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'paid') {
        $Payment_Status = 'Paid';
    } elseif ($Billing_Type == 'Outpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'active') {
        $Payment_Status = 'Not Paid';
        $onclick = 'onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
        $href = '#';
    } elseif ($Billing_Type == 'Inpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'paid') {
        $Payment_Status = 'Paid';
    } elseif ($Billing_Type == 'Inpatient Credit' && $Transaction_Type == 'Cash' && $Status == 'active') {
        $Payment_Status = 'Not Paid';
        $href = '#';
        $onclick = 'onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
    } elseif ($Billing_Type == 'Outpatient Cash' && $Transaction_Type == 'Cash' && $Status == 'active') {
        $Payment_Status = 'Not Paid';
        $href = '#';
        $onclick = 'onclick=\'alert("Patient has not paid yet. Please advice patient to go to cashier for payment!")\'';
    } elseif ($Billing_Type == 'Outpatient Cash' && $Transaction_Type == 'Cash' && $Status == 'paid') {
        $Payment_Status = 'Paid';
    } else {
        $Payment_Status = 'Not Billed';
    }

    //$Payment_Status=$Billing_Type.' '.$Transaction_Type.' '.$Status;
    echo '<tr>';
    echo '<td id="thead">' . $sn . '</td>';
    echo '<td id="thead">' . $Payment_Status . '</td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . ucwords(strtolower($patient_name)) . '</a></td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . $patient_numeber . '</a></td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . $sponsor . '</a></td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . $gender . '</a></td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . $subcat . '</a></td>';
    echo '<td><a ' . $onclick . ' href = ' . $href . ' ' . $style . '>' . $sent_date . '</a></td>';
    echo '</tr>';
    $sn++;
}
echo '</table>';
?>	