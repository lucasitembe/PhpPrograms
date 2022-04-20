<!--<link rel="stylesheet" href="table.css" media="screen">--> 

<?php
@session_start();
require_once('includes/connection.php');
$Date_From = '';
$Date_To = '';
//if(isset($_GET['Sub_Department_ID'])) $Sub_Department_ID = $_GET['Sub_Department_ID'];
if (isset($_GET['Patient_Name'])) {
    $Patient_Name = $_GET['Patient_Name'];
} else {
    $Patient_Name = '';
}
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $DateFrom = '';
}
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $DateTo = '';
}
//	if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }
//	if(isset($_GET['SI'])) { $Supervisor_ID = $_GET['SI']; } else { $Supervisor_ID = 0; }
//	if(isset($_GET['listtype'])) { $listtype = $_GET['listtype']; } else { $listtype = 'FromRec'; }
//	
$apID = 0;
$filter = ' AND DATE(rpt.Date_Time) = DATE(NOW()) ';
$filternoem = ' AND DATE(rpt.Date_Time) = DATE(NOW()) ';
$MaximumTimeToSeePatientHistory = $_SESSION['configData']['MaximumTimeToSeePatientHistory'];



if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND rpt.Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    $filternoem = "  AND rpt.Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    
    if ($MaximumTimeToSeePatientHistory > 0) {
        $filter .= "  AND TIMESTAMPDIFF(HOUR,rpt.Date_Time,NOW())  <= $MaximumTimeToSeePatientHistory ";
        $filternoem .= "  AND TIMESTAMPDIFF(HOUR,rpt.Date_Time,NOW())  <= $MaximumTimeToSeePatientHistory ";
    }
}

if ($Patient_Name != '') {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
    $filternoem .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}


$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$emp = '';

if ($hospitalConsultType == 'One patient to one doctor') {
    $emp = "AND tlc.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";

    $filter .="  AND tlc.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
    $filternoem .="  AND tlc.Consultant_ID !=" . $_SESSION['userinfo']['Employee_ID'] . " ";
}
//SELECTING PATIENTS LIST
$select_patients = "
		SELECT *			
			FROM
			tbl_radiology_patient_tests rpt, 
			tbl_items i,
			tbl_patient_registration pr,
			tbl_patient_payment_item_list tp,
			tbl_consultation tc,
			tbl_payment_cache pc,
			tbl_item_list_cache tlc
				WHERE
				rpt.Item_ID = i.Item_ID AND
				tlc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND
				tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
				tc.consultation_ID= pc.consultation_id AND
				tc.Patient_Payment_Item_List_ID= tp.Patient_Payment_Item_List_ID
				AND
				rpt.Registration_ID = pr.Registration_ID AND
				rpt.Status = 'done' AND tc.consultation_id NOT IN (select consultation_id from tbl_consultation c join tbl_patient_payment_item_list_transfer p ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID) $filter GROUP BY Patient_Name order by rpt.Radiology_Test_ID";

$group_by = '';


//echo $select_patients;exit;
// if($filter != ''){
// $select_patients_new = $select_patients.$filter.$group_by;
// } else {
// $select_patients_new = $select_patients.$group_by;		
// }

echo '<table width="100%" id="patientsResultInfo">';
echo '<thead><tr style="text-transform:uppercase; font-weight:bold;" id="thead">';
echo '<th style="width:5%;">SN</th>';
echo '<th>Patient Name</th>';
echo '<th>Test Name</th>';
echo '<th>Gender</th>';
echo '<th>Radiologist</th>';
echo '<th>Sonographer</th>';
echo '<th>Date</th>';
echo '</tr></thead>';

$select_patients_qry = mysqli_query($conn,$select_patients) or die(mysqli_error($conn));
$sn = 1;
while ($patient = mysqli_fetch_assoc($select_patients_qry)) {
    $patient_name = $patient['Patient_Name'];
    $patient_numeber = $patient['Registration_ID'];
    $test_name = $patient['Product_Name'];
    $gender = $patient['Gender'];
    $Registration_ID = $patient['Registration_ID'];
    $served_date = $patient['Date_Time'];
    $Radiologist = $patient['Radiologist_ID'];
    $Sonographer = $patient['Sonographer_ID'];
    $Patient_Payment_Item_List_ID = $patient['Patient_Payment_Item_List_ID'];

    $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

    $ppID = mysqli_fetch_assoc($rs);
    $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
    if (mysqli_num_rows($rs) == 0) {
        $Patient_Payment_ID = 0;
    }

    $select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
    $select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
    while ($radist = mysqli_fetch_assoc($select_radi_qry)) {
        $Radiologist_Name = $radist['Employee_Name'];
    }

    $select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
    $select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
    $Sonographer_Name = '';
    while ($sonog = mysqli_fetch_assoc($select_sono_qry)) {
        $Sonographer_Name = $sonog['Employee_Name'];
    }

    $href = "'RadiologyPatientTests_Doctor.php?Registration_ID=$Registration_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&Date_From=$Date_From&Date_To=$Date_To'";
    $style = 'style="text-decoration:none;"';

    echo '<tr>';
    echo '<td id="thead">' . $sn . '</td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $patient_name . '</a></td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $test_name . '</a></td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $gender . '</a></td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $Radiologist_Name . '</a></td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $Sonographer_Name . '</a></td>';
    echo '<td><a href = ' . $href . ' ' . $style . '>' . $served_date . '</a></td>';
    echo '</tr>';
    ;
    $sn++;
}

if ($hospitalConsultType == 'One patient to one doctor') {

    $select_radiologyOne = "
		SELECT *			
			FROM
			tbl_radiology_patient_tests rpt, 
			tbl_items i,
			tbl_patient_registration pr,
			tbl_patient_payment_item_list tp,
			tbl_consultation tc,
			tbl_payment_cache pc,
			tbl_item_list_cache tlc
				WHERE
				rpt.Item_ID = i.Item_ID AND
				tlc.Payment_Item_Cache_List_ID=rpt.Patient_Payment_Item_List_ID AND
				tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
				tc.consultation_ID= pc.consultation_id AND
				tc.Patient_Payment_Item_List_ID= tp.Patient_Payment_Item_List_ID AND
				rpt.Registration_ID = pr.Registration_ID AND
				rpt.Status = 'done' $filternoem GROUP BY Patient_Name order by rpt.Radiology_Test_ID";
    
    //echo $select_radiologyOne;

    $select_patients_one_qry = mysqli_query($conn,$select_radiologyOne) or die(mysqli_error($conn));
    $arrayInRad = array(0);

    while ($patient2 = mysqli_fetch_array($select_patients_one_qry)) {

        if (!in_array($patient2['consultation_id'], $arrayInRad)) {
          //echo $patient2['consultation_id'];
            //check if is transfered
             $qrytrn2 = mysqli_query($conn,"SELECT c.Patient_Payment_Item_List_ID FROM tbl_consultation c JOIN tbl_patient_payment_item_list_transfer p
                                  ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID 
                                  JOIN tbl_patient_payment_item_list pl
                                  ON pl.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID 
                                  where c.consultation_id='" . $patient2['consultation_id'] . "' AND pl.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'
                                  ") or die(mysqli_error($conn));

            if (mysqli_num_rows($qrytrn2) > 0) {
                $patient_name = $patient2['Patient_Name'];
                $patient_numeber = $patient2['Registration_ID'];
                $test_name = $patient2['Product_Name'];
                $gender = $patient2['Gender'];
                $Registration_ID = $patient2['Registration_ID'];
                $served_date = $patient2['Date_Time'];
                $Radiologist = $patient2['Radiologist_ID'];
                $Sonographer = $patient2['Sonographer_ID'];
                $Patient_Payment_Item_List_ID = $patient2['Patient_Payment_Item_List_ID'];

                $rs = mysqli_query($conn,"SELECT Patient_Payment_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));

                $ppID = mysqli_fetch_assoc($rs);
                $Patient_Payment_ID = $ppID['Patient_Payment_ID'];
                if (mysqli_num_rows($rs) == 0) {
                    $Patient_Payment_ID = 0;
                }

                $select_radi = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist'";
                $select_radi_qry = mysqli_query($conn,$select_radi) or die(mysqli_error($conn));
                while ($radist = mysqli_fetch_assoc($select_radi_qry)) {
                    $Radiologist_Name = $radist['Employee_Name'];
                }

                $select_sono = "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer'";
                $select_sono_qry = mysqli_query($conn,$select_sono) or die(mysqli_error($conn));
                $Sonographer_Name = '';
                while ($sonog = mysqli_fetch_assoc($select_sono_qry)) {
                    $Sonographer_Name = $sonog['Employee_Name'];
                }

                $href = "'RadiologyPatientTests_Doctor.php?Registration_ID=$Registration_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&Date_From=$Date_From&Date_To=$Date_To'";
                $style = 'style="text-decoration:none;"';

                echo '<tr>';
                echo '<td id="thead">' . $sn . '</td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $patient_name . '</a></td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $test_name . '</a></td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $gender . '</a></td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $Radiologist_Name . '</a></td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $Sonographer_Name . '</a></td>';
                echo '<td><a href = ' . $href . ' ' . $style . '>' . $served_date . '</a></td>';
                echo '</tr>';
                ;
                $sn++;
                $totalrad++;
            }
        }
        $arrayInRad[] = $patient2['consultation_id'];
    }
}

echo '</table>';
?>	