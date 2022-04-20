<?php

@session_start();
include("./includes/connection.php");


$total = 0;
$temp = 1;
$data = '';
$sqlq = '';
$totalItem = '';
$totalDone = '';
$dataAmount = '';

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$filter = " AND DATE(Date_Time) = DATE(NOW())  AND ilc.Sub_Department_ID='$Sub_Department_ID'";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Date_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'   AND ilc.Sub_Department_ID='$Sub_Department_ID'";
}

if (!empty($SubCategory) && $SubCategory != 'All') {
    $filter .=" AND its.Item_Subcategory_ID='$SubCategory' ";
}


echo '<table width ="100%"  border="0" >
            <thead >
                <tr>
                    <td ><b>SN</b></td>
                    <td width="30%"><b>TEST NAME</b></td>
                    <td><b>SENT</b></td>
                    <td width="10%"><b>DOCT. REMARKS</b></td>
                    <td><b>PROGRESS</b></td>
                    <td><b>REMARKS</b></td>
                    <td width="15%"><b>CLASSIFICATION</b></td>
                    <td width="15%"><b>IMAGING</b></td>
                    <td width="15%"><b>RADIOLOGIST</b></td>
                    <td width="15%"><b>REPORTEUR</b></td>
                    <td width="15%"><b>PARAMETERS</b></td>
                </tr>
            </thead>';

$sqlq = " SELECT ilc.Transaction_Date_And_Time,ilc.Status,its.Item_ID, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID,ilc.Doctor_Comment, ilc.Payment_Cache_ID,ilc.remarks,pc.Billing_Type,ilc.Transaction_Type
		from tbl_item_list_cache ilc,tbl_payment_cache pc, tbl_items its, tbl_radiology_patient_tests rad 		  
                where ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                    ilc.item_id = its.item_id and
                    ilc.status = 'served'  and
                    rad.Patient_Payment_Item_List_ID = ilc.Payment_Item_Cache_List_ID and
                    pc.Registration_ID='$Registration_ID' and
                ilc.Check_In_Type = 'Radiology' $filter
		 ";

//die($sqlq);

$select_Transaction_Items_Served = mysqli_query($conn,$sqlq) or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Transaction_Items_Served);

$transStatust = false;
while ($row = mysqli_fetch_array($select_Transaction_Items_Served)) {
    $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $datetime = $row['Transaction_Date_And_Time'];
    $The_Item_ID = $row['Item_ID'];
    $displ = '';

    //Getting the Old Values *****************//
    $select_rpt = "SELECT * FROM tbl_radiology_patient_tests WHERE Item_ID = '$The_Item_ID' AND Patient_Payment_Item_List_ID='" . $row["Payment_Item_Cache_List_ID"] . "'  AND Registration_ID = '$Registration_ID' AND Patient_Payment_Item_List_ID='" . $row['Payment_Item_Cache_List_ID'] . "'";
    $select_rpt_qry = mysqli_query($conn,$select_rpt) or die(mysqli_error($conn));
    $Sonographer_ID = 0;
    $Radiologist_ID = 0;
    $Reporteur_ID = 0;
    $oldstatus = '';
    $oldremarks = '';
    $oldclas = '';
    while ($old_rpt = mysqli_fetch_assoc($select_rpt_qry)) {
        $oldstatus = $old_rpt['Status'];
        $oldremarks = $old_rpt['Remarks'];
        $oldclas = $old_rpt['Classification'];
        $Sonographer_ID = $old_rpt['Sonographer_ID'];
        $Radiologist_ID = $old_rpt['Radiologist_ID'];
        $Reporteur_ID = $old_rpt['Reporteur'];
    
    $displ = '<td style="text-align:center"><select class="Procedureprogress" id="' . $row["Payment_Item_Cache_List_ID"] . '" name="status_' . $row["Payment_Item_Cache_List_ID"] . '" style="padding:5px;">
					                 ';
    if ($status == 'pending') {
        $displ .= '<option value="pending" selected="selected">Pending</option>'
                . '<option value="served">Done</option>';
        $displ .= '<option value="not done">Not Done</option>';
    } else if ($status == 'not done') {
        $displ .= '<option value="pending">Pending</option>'
                . '<option value="served">Done</option>';
        $displ .= ' <option value="not done" selected="selected">Not Done</option>';
    } else if ($status == 'served') {
        $displ .= '<option value="pending">Pending</option>'
                . '<option value="served" selected="selected">Done</option>';
        $displ .= ' <option value="not done" >Not Done</option>';
    }
    $displ .= '</select> ';

    $displ .= ' <input type="hidden" name="status_pro_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $status . '"/>
                  <input type="hidden" name="billing_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $billing_Type . '"/> 
                  <input type="hidden" name="transaction_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $transaction_Type . '"/> 
                              
                  </td>';
    $displ .= "<td width='30%'><input type='hidden' value='" . $row['Payment_Item_Cache_List_ID'] . "' name='paymentItermCache[]'><textarea type='text' name='remarks_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row["Payment_Item_Cache_List_ID"] . "' style='' cols='8' rows='1'>" . ($row['remarks']) . "</textarea></td>";

    //classification
    $displ.="<td ><select name='classification_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "classification' >
				<option></option>";

    $ord = "";
    $sp = "";
    $eeg = "";
    $echo = "";
    $ct = "";
    $ct = "";
    $ecg = "";
    $other = "";
    $ultr = "";

    if ($oldclas == 'Ordinary Xray Register') {
        $ord = "selected";
    } elseif ($oldclas == 'Special Xray Register') {
        $sp = "selected";
    } elseif ($oldclas == 'Eeg Register') {
        $eeg = "selected";
    } elseif ($oldclas == 'Ultrasound Register') {
        $ultr = "selected";
    } elseif ($oldclas == 'Echo Cardiogram Register') {
        $echo = "selected";
    } elseif ($oldclas == 'Ct-Scan') {
        $ct = "selected";
    } elseif ($oldclas == 'Ecg') {
        $ecg = "selected";
    } elseif ($oldclas == 'Other') {
        $other = "selected";
    } //else {
    $displ.="  <option " . $ord . ">Ordinary Xray Register</option>
                                        <option " . $sp . ">Special Xray Register</option>
                                        <option " . $eeg . ">Eeg Register</option>
                                        <option " . $ultr . ">Ultrasound Register</option>
                                        <option " . $echo . ">Echo Cardiogram Register</option>
                                        <option " . $ecg . ">Ecg</option>
                                        <option " . $ct . ">Ct-Scan</option>
                                        <option " . $other . ">Other</option> ";
    // }
    $displ.="</select></td>";

    //Sonographer Select
    $displ.="<td ><select name='sonographer_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "sonographer' style='width:100%' >
				<option value=''>--Select--</option>";

    $select_sonographer = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND  Employee_Job_Code LIKE '%Sonographer%'";
    $select_sonographer_qry = mysqli_query($conn,$select_sonographer) or die(mysqli_error($conn));

    while ($sonog = mysqli_fetch_assoc($select_sonographer_qry)) {
        $sonogname = $sonog['Employee_Name'];
        $sonogid = $sonog['Employee_ID'];
        if ($Sonographer_ID == $sonogid) {
            $displ.= "<option value='" . $sonogid . "' selected='selected'>" . strtoupper($sonogname) . "</option>";
        } else {
            $displ.= "<option value='" . $sonogid . "'>" . strtoupper($sonogname) . "</option>";
        }
    }
    $displ.= '</select></td>';

    //Radiologist Select
    $displ.="<td ><select name='radiologist_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "radiologist' style='width:100%'  >";

    $select_radiologist = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND  Employee_Job_Code LIKE '%Radiologist%'";
    $select_radiologist_qry = mysqli_query($conn,$select_radiologist) or die(mysqli_error($conn));
  
            $displ.= "<option value='" . $Employee_ID . "'>" . strtoupper($Employee_Name) . "</option>";


    // if (is_null($Radiologist_ID)) {
    //     $displ.= "<option value='NULL' selected>NOT APPLICABLE</option>";
    // } else {
    //     $displ.= "<option value='NULL'>NOT APPLICABLE</option>";
    // }

    $displ.= '</select></td>';

    //Reporteur Select
    $displ.="<td ><select name='reporteur_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "reporteur' style='width:100%'  >
				<option value=''>--Select--</option>";

    $select_report = "SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE Account_Status = 'active' AND (Employee_Job_Code  NOT LIKE '%Radiologist%' OR Employee_Job_Code  NOT LIKE '%Sonographer%')";
    $select_report_qry = mysqli_query($conn,$select_report) or die(mysqli_error($conn));
    while ($report = mysqli_fetch_assoc($select_report_qry)) {
        $employeename = $report['Employee_Name'];
        $empid = $report['Employee_ID'];

        if ($Reporteur_ID == $empid) {
            $displ.= "<option value='" . $empid . "' selected>" . strtoupper($employeename) . "</option>";
        } else {
            $displ.= "<option value='" . $empid . "'>" . strtoupper($employeename) . "</option>";
        }
    }

    if (is_null($Reporteur_ID)) {
        $displ.= "<option value='NULL' selected>NOT APPLICABLE</option>";
    } else {
        $displ.= "<option value='NULL'>NOT APPLICABLE</option>";
    }

    $displ.= '</select></td>';

    //Buttons clicks

    $Item_ID = $row['Item_ID'];
    $Patient_Payment_Item_List_ID = $row['Payment_Item_Cache_List_ID'];
    $Patient_Payment_ID = $row['Payment_Cache_ID'];
    $test_name = $row['Product_Name'];

    $href = "radiologyviewimage.php?II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . '&RI=' . $Registration_ID;

    $href = "II=" . $Item_ID . "&PPILI=" . $Patient_Payment_Item_List_ID . "&PPI=" . $Patient_Payment_ID . '&RI=' . $Registration_ID .'&Employee_ID='.$Employee_ID;

    $add_parameters = '<button style="width:60%;" type="button" class="art-button-green" onclick="radiologyviewimage(\'' . $href . '\',\'' . $test_name . '\')">Imaging</button>';
    $commentsDescription = '<button style="width:60%;" type="button" class="art-button-green" onclick="commentsAndDescription(\'' . $href . '\',\'' . $test_name . '\')">Comments</button>';

    $displ.= '<td>' . $add_parameters . $commentsDescription . '</td>';



    echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
    echo "<td>" . $row['Product_Name'] . "</td>";
    echo "<td>" . $datetime . "</td>";
    echo "<td>" . $row['Doctor_Comment'] . "</td>";
    echo $displ;
    echo "</tr>";
    // }


    $temp++;
    $transStatust = false;
}
}
echo "</table>";
