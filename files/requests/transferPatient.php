<?php

session_start();
include("../includes/connection.php");
$data = '';
$datamsg = '';
$patient_direction = "";
$patient_direction_select = "";

$Employee_ID=$_SESSION['userinfo']['Employee_ID'];


$users = isset($_POST['user']) ? $_POST['user'] : '';
$dataInfo = isset($_POST['dataInfo']) ? $_POST['dataInfo'] : '';
//echo $dataInfo;exit;
$s = explode('us', $users);
$dti = explode('^$*^%$', $dataInfo);

$transfers_To = $s[0];
$transfers_From = $s[1];

if (isset($_POST['transfer_type'])) {
    $transfer_type = $_POST['transfer_type'];
} else {
    $transfer_type = "";
}

$consultant="";
$transferToId='';

if ($transfer_type == 'Doctor_To_Doctor') {
    $patient_direction = "Direct To Doctor";
    $patient_direction_select = "Direct To Doctor";
    $transferToId="Consultant_ID='$transfers_To',Clinic_ID = NULL";
    $consultant=" and pl.Consultant_ID = " . $transfers_From . " ";
} else if ($transfer_type == 'Doctor_To_Clinic') {
    $patient_direction = "Direct To Clinic";
    $patient_direction_select = "Direct To Doctor";
    $transferToId="Consultant_ID = NULL,Clinic_ID='$transfers_To'";
    $consultant=" and pl.Consultant_ID = " . $transfers_From . " ";
} else if ($transfer_type == 'Clinic_To_Clinic') {
    $patient_direction = "Direct To Clinic";
    $patient_direction_select = "Direct To Clinic";
    $transferToId="Consultant_ID = NULL,Clinic_ID='$transfers_To'";
    $consultant=" and pl.Clinic_ID = " . $transfers_From . " ";
} else if ($transfer_type == 'Clinic_To_Doctor') {
    $patient_direction = "Direct To Doctor";
    $patient_direction_select = "Direct To Clinic";
    $transferToId="Consultant_ID='$transfers_To',Clinic_ID = NULL";
    $consultant=" and pl.Clinic_ID = " . $transfers_From . "";
}

$filter = ' AND DATE(pl.Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) ';

$Date_From = filter_input(INPUT_POST, 'Date_From');
$Date_To = filter_input(INPUT_POST, 'Date_To');


if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}

foreach ($dti as $data) {
    $values = explode('~~', $data);

    $ppil = $values[0];
    $reg_id = $values[1];
    $reason = $values[2];
    $ckid = $values[3];
    $consIDTrue = $consID = $values[4];

    // $cnslRs=mysqli_query($conn,"SELECT * FROM tbl_consultation WHERE Patient_Payment_Item_List_ID ='$ppil'") or die(mysqli_error($conn));
    // $consultation_id=mysqli_fetch_assoc($cnslRs)['consultation_ID'];
    //echo $consultation_id."  ".$consID;exit;
    //get payment cache id
//
//    if ($consID == 0) {
//        $cnslRs = mysqli_query($conn,"SELECT * FROM tbl_consultation WHERE Patient_Payment_Item_List_ID ='$ppil'") or die(mysqli_error($conn));
//
//        $consultation_id = mysqli_fetch_assoc($cnslRs)['consultation_ID'];
//
//        $consID = $consultation_id;
//    }

//    $pcid = mysqli_query($conn,"SELECT * FROM tbl_item_list_cache ilc LEFT JOIN tbl_payment_cache pc ON ilc.Payment_Cache_ID=pc.Payment_Cache_ID WHERE pc.consultation_id='$consID' AND pc.Registration_ID='$reg_id'") or die(mysqli_error($conn));
//
//    while ($row = mysqli_fetch_array($pcid)) {
//
//        $qr = mysqli_query($conn,"INSERT INTO  tbl_item_list_cache_transfer SELECT * FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='" . $row['Payment_Item_Cache_List_ID'] . "'") or die(mysqli_error($conn));
//        $qr = mysqli_query($conn,"UPDATE  tbl_item_list_cache SET Consultant_ID='$transfers_To' WHERE Payment_Item_Cache_List_ID='" . $row['Payment_Item_Cache_List_ID'] . "'") or die(mysqli_error($conn));
//    }

   // echo "UPDATE tbl_patient_payment_item_list SET $transferToId,Patient_Direction='$patient_direction' WHERE Patient_Payment_Item_List_ID='$ppil'";exit;
    $qr = mysqli_query($conn,"INSERT INTO  tbl_patient_payment_item_list_transfer SELECT * FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));
    $qr = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET $transferToId,Patient_Direction='$patient_direction' WHERE Patient_Payment_Item_List_ID='$ppil'") or die(mysqli_error($conn));

	
    $insertatin_querry = mysqli_query($conn,"INSERT INTO tbl_transfer(Patient_Payment_Item_List_ID,Employee_ID_Do_Transfer,Registration_ID,Transfer_From,Transfer_To,Transfer_Date,
											Reason)
				VALUES ('$ppil'," . $Employee_ID . ",'$reg_id','$transfers_From','$transfers_To',NOW(),'$reason') ") or die(mysqli_error($conn));
    //exit;
    //echo $ppil.' reg_id='.$reg_id.' reason='.$reason.' ckid'.$ckid.'<br/>';
}

if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {

    $qr = "
	   select pr.Registration_ID,pr.Patient_Name,Check_In_ID,Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,'0' AS consultationID from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp
		      where sp.sponsor_id = pr.sponsor_id and
		      pp.Registration_ID = pr.Registration_ID and
		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and pl.Process_Status <> 'signedoff' and
                      pl.Patient_Direction = 'Direct To Doctor' and pl.Consultant_ID = " . $_SESSION['userinfo']['Employee_ID'] . " and
		      pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " $filter GROUP BY pp.Registration_ID order by pl.Transaction_Date_And_Time
	
		  "
    ;
} else {

    $qr = "
	   select pr.Registration_ID,pr.Patient_Name,Check_In_ID,Transaction_Date_And_Time,pl.Patient_Payment_Item_List_ID,'0' AS consultationID from tbl_patient_registration pr,tbl_patient_payments pp, tbl_patient_payment_item_list pl, tbl_sponsor sp
		      where sp.sponsor_id = pr.sponsor_id and
		      pp.Registration_ID = pr.Registration_ID
               and
		      pp.Patient_Payment_ID = pl.Patient_Payment_ID and pl.Process_Status NOT IN ('signedoff','no show') and
                      pl.Patient_Direction = '$patient_direction_select' $consultant and
		      pp.Branch_ID = " . $_SESSION['userinfo']['Branch_ID'] . " $filter GROUP BY pp.Registration_ID order by pl.Transaction_Date_And_Time

		  "
    ;
}

$Select_patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));

//  $result = mysqli_query($conn,$Select_patients);



$no = mysqli_num_rows($Select_patients);
$datad = '';

if ($no > 0) {
    $datad .= '<center><table width =90% border="1px">';
    $datad .= '<tr>	<td width ="3%" style="text-align:center;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td width ="10%"><b>PATIENT NO</b></td>
				<td><b>Consultation Date</b></td>
				
				<td><b>Reason</b></td>
				<td style="text-align:right;">
				  <input type="checkbox" name="transferAll"  id="transferAll" style="margin-right:10px;" onclick="checktransferAll(this)"/><label for="transferAll"><b>All</b></label></td>
		</tr><tr><td colspan="6"><hr/></td></tr>';
    $temp = 1;
    while ($row = mysqli_fetch_array($Select_patients)) {

        //Get check-in ID
        $Registration_ID = $row['Registration_ID'];
        $select_checkin = "SELECT Check_In_ID,Type_Of_Check_In FROM tbl_check_in WHERE Registration_ID = '$Registration_ID' AND Check_In_ID='" . $row['Check_In_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
        //echo $select_checkin;exit;
        $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));

        $date = date('Y-m-d', strtotime($row['Transaction_Date_And_Time']));
        if (mysqli_num_rows($select_checkin_qry) > 0) {
            $datad .= "<tr><td style='text-align:center;'>" . $temp . "</td><td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";

            $datad .= "<td style='text-align:center;'>" . $row['Registration_ID'] . "</td>";

            $temp++;

            $datad .= "<td>
			" . $date . "
	     </td>
		 <td>
		   <textarea style='width:100%;height:15px' id='reason_" . $row['Registration_ID'] . "' name='" . $row['Registration_ID'] . "'></textarea>
		  </td>
		 <td style='text-align:center;'>
			<input type='checkbox' ppil='" . $row['Patient_Payment_Item_List_ID'] . "' ckid='" . $row['Check_In_ID'] . "' class='tansfersPatient patient_id_" . $row['Registration_ID'] . "' id='" . $row['Registration_ID'] . "'>
		 </td>";
        }
    }
}
echo "PATIENT SUCCESSFULLY TRANSFERED!$$$$$" . $datad;
