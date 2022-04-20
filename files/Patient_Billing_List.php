<?php
session_start();
include("./includes/connection.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";


$admin_status = " and (ad.Admission_Status = 'Pending' or ad.Admission_Status = 'Admitted')";

if (isset($_GET['Patient_Name'])) {
    $Patient_Name = mysqli_real_escape_string($conn,$_GET['Patient_Name']);
} else {
    $Patient_Name = '';
}

if (isset($_GET['Patient_Number'])) {
    $Patient_Number = mysqli_real_escape_string($conn,$_GET['Patient_Number']);
} else {
    $Patient_Number = '';
}

if (isset($_GET['start_date'])) {
    $start_date = mysqli_real_escape_string($conn,$_GET['start_date']);
} else {
    $start_date = '';
}

if (isset($_GET['end_date'])) {
    $end_date = mysqli_real_escape_string($conn,$_GET['end_date']);
} else {
    $end_date = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = mysqli_real_escape_string($conn,$_GET['Sponsor_ID']);
} else {
    $Sponsor_ID = 0;
}

if (isset($_GET['Hospital_Ward_ID'])) {
    $Hospital_Ward_ID = mysqli_real_escape_string($conn,$_GET['Hospital_Ward_ID']);
} else {
    $Hospital_Ward_ID = 0;
}

if (isset($_GET['patient_type'])) {
    $patient_type = mysqli_real_escape_string($conn,$_GET['patient_type']);
}

$subtitle = '<td width="12%"><b>WARD</b></td>'
        . ' <td width="6%"><b>NO. DAYS</b></td>';
if (!empty($patient_type) && $patient_type !='All') {
    $admin_status = " and ad.Admission_Status = '$patient_type'";

    if ($patient_type == 'pending') {
        $subtitle = '<td width="6%"><b>WARD</b></td>
                     <td width="6%"><b>NO. DAYS</b></td>
                     <td width="6%"><b>SET TIME</b></td>
                     <td width="7%"><b>SETTER</b></td>';
    }
}


if(isset($start_date) && !empty($start_date) && isset($end_date) && !empty($end_date)){
    $filter = " AND ad.pending_set_time BETWEEN '".$start_date."' AND '".$end_date."'";
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}



$Title = '
        <tr style="background-color: #ccc;">
            <td width="5%"><b>SN</b></td>
            <td width="13%"><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="12%"><b>SPONSOR NAME</b></td>
            <td width="14%"><b>PATIENT AGE</b></td>
            <td width="9%"><b>GENDER</b></td>
            <td width="12%"><b>MEMBER NUMBER</b></td>
            '
. $subtitle . "</tr></thead>";
?>
<legend style="background-color:#006400;color:white" align="right"><b>PENDING BILLS ~ INPATIENTS LIST</b></legend>
<center><table width =100% border=0 class="table table-condensed fixTableHead">
        <thead>
        <?php
        $temp = 0;
        echo $Title;
        if ($Hospital_Ward_ID == 0) {
            if ($Sponsor_ID == 0) {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared'  $filter GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn,"SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn,"SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Registration_ID like '%$Patient_Number%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                }
            }
        } else {
            if ($Sponsor_ID == 0) {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                                pr.Registration_ID=cd.Registration_ID and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
                                                pr.Registration_ID=cd.Registration_ID and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                }
            } else {
                if (isset($_GET['Patient_Name'])) {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Patient_Name like '%$Patient_Name%' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else if (isset($_GET['Patient_Number']) && $Patient_Number != null && $Patient_Number != '') {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Registration_ID = '$Patient_Number' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn, "SELECT ad.Doctor_Status,ad.Discharge_Reason_ID,ad.Admision_ID,ad.Admission_Status,pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, cd.Check_In_ID, hw.Hospital_Ward_Name,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,pending_set_time,pending_setter
		                                FROM tbl_admission ad, tbl_check_in_details cd, tbl_patient_registration pr, tbl_sponsor sp, tbl_hospital_ward hw WHERE
		                                cd.Admission_ID = ad.Admision_ID and
		                                pr.Sponsor_ID = sp.Sponsor_ID and
                                                pr.Registration_ID=cd.Registration_ID and
		                                pr.Registration_ID = ad.Registration_ID and
		                                pr.Sponsor_ID = '$Sponsor_ID' and
                                		hw.Hospital_Ward_ID = ad.Hospital_Ward_ID and
                                		hw.Hospital_Ward_ID = '$Hospital_Ward_ID' $admin_status and
                                		ad.Discharge_Clearance_Status = 'not cleared' $filter  GROUP BY Admission_ID asc limit 50") or die(mysqli_error($conn));
                }
            }
        }

        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($row = mysqli_fetch_array($select)) {
                //calculate age
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";  //pending_set_time,NoOfDay
                $pending_set_time = $row['pending_set_time'];
                $NoOfDay = $row['NoOfDay'];
                $pending_setter = $row['pending_setter'];
                $pendingSetter = '';

                if (!empty($pending_setter) || !is_null($pending_setter)) {
                    $pendingSetter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$pending_setter'"))['Employee_Name'];
                }
                 $Discharge_Reason_ID=$row['Discharge_Reason_ID'];
                 $Doctor_Status=$row['Doctor_Status'];
                 $Admission_Status=$row['Admission_Status'];
                   $sql_select_discharge_reason_result=mysqli_query($conn, "SELECT discharge_condition FROM tbl_discharge_reason WHERE Discharge_Reason_ID='$Discharge_Reason_ID' AND discharge_condition='dead'") or die(mysqli_error($conn));
                   if(mysqli_num_rows($sql_select_discharge_reason_result)>0){
                     $back_color="red;color:#FFFFFF";  
                   }else{
                     $back_color="green;color:#FFFFFF";
                   }
                   $num_of_rows=mysqli_num_rows($sql_select_discharge_reason_result);
                   if($Doctor_Status=="initial_pending"&&$Admission_Status!='pending'){
                      $back_color="greenyellow;color:#000000;font-size:14px"; 
                   } 
//                   $back_color="greenyellow"; 
                ?>
                <tr id="thead"><td style="width:5%;"><?php echo ++$temp; ?><b>.</b></td>
                    <td width="20%"><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><span <?php if($row['Admission_Status']=="pending"||$Doctor_Status=="initial_pending"){echo "style='background:$back_color;padding:5px;'"; } ?>><b><?php echo ucwords(strtolower($row['Patient_Name'])); ?></b></span></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Registration_ID']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Guarantor_Name']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Gender']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Member_Number']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $row['Hospital_Ward_Name']; ?></a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=<?php echo $row['Check_In_ID']; ?>&Registration_ID=<?php echo $row['Registration_ID']; ?>&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;"><?php echo $NoOfDay; ?></a></td>
                        <?php
                        if (!empty($patient_type)) {

                            if ($patient_type == 'pending') {
                                echo ' <td><a href="previewpatientbilldetails.php?Check_In_ID=' . $row['Check_In_ID'] . '&Registration_ID='. $row['Registration_ID'].'&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;">' . $pending_set_time . '</a></td>
                    <td><a href="previewpatientbilldetails.php?Check_In_ID=' . $row['Check_In_ID'] . '&Registration_ID='. $row['Registration_ID'].'&PreviewPatientBillDetails=PreviewPatientBillDetailsThisPage" style="text-decoration: none;">' . $pendingSetter . '</a></td>
';
                            }
                        }
                        ?>             
                </tr>
                <?php
                if (($temp % 31) == 0) {
                    echo $Title;
                }
            }
        }
        echo "</table>";
        ?>