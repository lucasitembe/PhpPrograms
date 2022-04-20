<?php
include("./includes/connection.php");
session_start();
if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
  }
  if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
  }
  if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
  }

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']) && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes') {
        
    } else if (isset($_SESSION['userinfo']['Mtuha_Reports']) && $_SESSION['userinfo']['Mtuha_Reports'] == 'yes') {
        
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
}else{
    $E_Name = '';
}


    $rs = mysqli_query($conn,"SELECT sp.Guarantor_Name FROM tbl_sponsor as sp,tbl_patient_registration as reg WHERE Registration_ID='$Registration_ID' and sp.Sponsor_ID=reg.Sponsor_ID") or die(mysqli_error($conn));
    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
$name = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

    $Patient_Name = mysqli_fetch_assoc($name)['Patient_Name'];

$htm = "<table width ='100%' class='nobordertable'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;color:#002166;'><b>LABOUR RECORD REPORT  OF: ".strtoupper($Patient_Name)."</b></td></tr>
                    <tr><td style='text-align: center;'></td></tr>
                    <tr><td style='text-align: center;'><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
        </table><br/>";



    $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";


    $select_doctor_result = mysqli_query($conn,$select_addmissions1);
    while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
        $date_time = $select_admission_row['date_time'];
        $htm .= "<h4 style='text-align:center;background-color:blue;font-size:20px;color:white;' >" . $date_time . "</h4>";
    }

$htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
$htm .= "<thead>
    <tr><td colspan='7' style='text-align:center'>ADMISSION</td></tr>
        <tr>
        <td style='text-align:center;font-size:20px;'>Admission Reason</td>
        <td style='text-align:center;font-size:20px;'>Admission From</td>
        <td style='text-align:center;font-size:20px;'>Summary Of Antenatal</td>
        <td style='text-align:center;font-size:20px;'>Abnormalities</td>
  </tr>
                      </thead>";


    $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";


    $select_doctor_result = mysqli_query($conn,$select_addmissions1);
    $htm .= "<tbody>";
    while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
        $admission_reason = $select_admission_row['admission_reason'];
        $abnormalities = $select_admission_row['abnormalities'];
        $admission_from = $select_admission_row['admission_from'];
        $summary_Antenatal = $select_admission_row['summary_Antenatal'];
        $htm .= "<tr><td style='text-align:left;font-size:20px;' >" . $admission_reason . "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" . $admission_from. "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" .$summary_Antenatal. "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" . $abnormalities. "</td>";
        $htm .= "</tr>";
    }
    $htm.="</tbody>";
    $htm .= ' </table></center>';


    $htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
$htm .= "<thead>
    <tr><td colspan='17' style='text-align:center'>EXAMINATION</td></tr>
                       
                        <tr>
                        <td style='text-align:center;font-size:20px;'>General Condition</td>
                        <td style='text-align:center;font-size:20px;'>Fundamental Height</td>
                        <td style='text-align:center;font-size:20px;'>Temperature</td>
                        <td style='text-align:center;font-size:20px;'>Blood Pressure</td>
                      </thead>";
//run the query to select all data from the database according to the branch id
    $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";


    $select_doctor_result = mysqli_query($conn,$select_addmissions1);
    $htm .= "<tbody>";
    while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
        $general_condition = $select_admission_row['general_condition'];
        $fundamental_height = $select_admission_row['fundamental_height'];
        $temperature = $select_admission_row['temperature'];
        $blood_pressure = $select_admission_row['blood_pressure'];
        $htm .= "<tr><td style='text-align:left;font-size:20px;' >" . $general_condition . "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" . $fundamental_height. "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" .$temperature. "</td>";
        $htm .= "<td style='text-align:left;font-size:20px;'>" . $blood_pressure. "</td>";
        $htm .= "</tr>";
    }
    $htm.="</tbody>";
    $htm .= ' </table></center>';

    $htm .= '<center><table width ="100%"  class="display" border="1" style="border-collapse: collapse;">';
    $htm .= "<thead>
        <tr><td colspan='4' style='text-align:center'>EXAMINATION</td></tr>
                           
                            <tr>
                            <td style='text-align:center;font-size:20px;'>Oedema</td>
                            <td style='text-align:center;font-size:20px;'>Acetone</td>
                            <td style='text-align:center;font-size:20px;'>Protein</td>
                            <td style='text-align:center;font-size:20px;'>Height</td>
                          </tr>
                          </thead>";
    //run the query to select all data from the database according to the branch id
        $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";
    
    
        $select_doctor_result = mysqli_query($conn,$select_addmissions1);
        $htm .= "<tbody>";
        while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $oedema = $select_admission_row['oedema'];
            $acetone = $select_admission_row['acetone'];
            $protein = $select_admission_row['protein'];
            $Height = $select_admission_row['Height'];
            $htm .= "<tr><td style='text-align:left;font-size:20px;' >" . $oedema . "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $acetone. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$protein. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $Height. "</td>";
            $htm .= "</tr>";
        }
        $htm.="</tbody>";
        $htm .= ' </table></center>';
        $htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
        $htm .= "<thead>
        <tr><td colspan='17' style='text-align:center'>EXAMINATION</td></tr>
                           
                            <tr>
                            <td style='text-align:center;font-size:20px;'>Ho Estimation Of Presentation</td>
                            <td style='text-align:center;font-size:20px;'>Last Recorded A/N</td>
                            <td style='text-align:center;font-size:20px;'>Size Of Fetus</td>
                            <td style='text-align:center;font-size:20px;'>Lie</td>
                          </tr>
                          </thead>";
    //run the query to select all data from the database according to the branch id
        $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";
    
    
        $select_doctor_result = mysqli_query($conn,$select_addmissions1);
        $htm .= "<tbody>";
        while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $estimation_presentation = $select_admission_row['estimation_presentation'];
            $last_recorded = $select_admission_row['last_recorded'];
            $size_fetus = $select_admission_row['size_fetus'];
            $lie = $select_admission_row['lie'];
            $htm .= "<tr><td style='text-align:left;font-size:20px;' >" . $estimation_presentation . "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $last_recorded. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$size_fetus. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $lie. "</td>";
            $htm .= "</tr>";
        }
        $htm.="</tbody>";
        $htm .= ' </table></center>';
        $htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
        $htm .= "<thead>
        <tr><td colspan='17' style='text-align:center'>EXAMINATION</td></tr>
                           
                            <tr>
                            <td style='text-align:left;font-size:20px;'>presentation</td>
                            <td style='text-align:left;font-size:20px;'>Liquor</td>
                            <td style='text-align:left;font-size:20px;'>Meconium Stained</td>
                            <td style='text-align:left;font-size:20px;'>If Membrane Ruptured</td>
                            <td style='text-align:left;font-size:20px;'>Blood Group</td>
                          </tr>
                          </thead>";
    //run the query to select all data from the database according to the branch id
        $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, position, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";
    
    
        $select_doctor_result = mysqli_query($conn,$select_addmissions1);
        $htm .= "<tbody>";
        while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $presentation = $select_admission_row['presentation'];
            $liquor = $select_admission_row['liquor'];
            $meconium = $select_admission_row['meconium'];
            $membrane = $select_admission_row['membrane'];
            $blood_group = $select_admission_row['blood_group'];
            $htm .= "<tr><td style='text-align:left;font-size:20px;' >" . $presentation . "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $liquor. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$meconium. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $membrane. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $blood_group. "</td>";
            $htm .= "</tr>";
        }
        $htm.="</tbody>";
        $htm .= ' </table></center>';







    $htm .= '<center><table width ="100%" border="1" class="display" style="border-collapse: collapse;">';
    $htm .= "<thead>
        <tr><td colspan='17' style='text-align:center'>INITIAL VAGINAL EXAMINATION AND PELVIC ASSESSMENT</td></tr>
                           
                            <tr>
                            <td style='text-align:left;font-size:20px;'>DATE AND TIME </td>
                            <td style='text-align:left;font-size:20px;'>Bony Pelvis</td>
                            <td style='text-align:left;font-size:20px;'>Cervic:state</td>
                            <td style='text-align:left;font-size:20px;'>Blood Pressure</td>
                            <td style='text-align:left;font-size:20px;'>Sacral curve</td>
                            <td style='text-align:left;font-size:20px;'>Sacral promontory</td>
                            <td style='text-align:left;font-size:20px;'>Dilation</td>
                            <td style='text-align:left;font-size:20px;'>Presenting Part</td>
                            <td style='text-align:left;font-size:20px;'>Lachial Spines</td>
                            <td style='text-align:left;font-size:20px;'>Level</td>
                            <td style='text-align:left;font-size:20px;'>Subpubic Angle</td>
                            <td style='text-align:left;font-size:20px;'>Position</td>
                            <td style='text-align:left;font-size:20px;'>Sacral Tuberosites</td>
                            <td style='text-align:left;font-size:20px;'>Moulding</td>
                            <td style='text-align:left;font-size:20px;'>Summary</td>
                            <td style='text-align:left;font-size:20px;'>Caput</td>
                            <td style='text-align:left;font-size:20px;'>Membranes/Liquor</td>
                            <td style='text-align:left;font-size:20px;'>Consultants's/Registra's Opinion</td>
                          </tr>
                          </thead>";
    //run the query to select all data from the database according to the branch id
        $select_addmissions1 = "SELECT lb.admission_id,reg.Patient_Name,lb.Employee_ID,emp.Employee_Name,lb.date_time, lb.today_date, lb.summary_Antenatal, lb.abnormalities, lb.lmp, lb.edd, lb.ga, lb.general_condition, lb.fundamental_height, lb.blood_pressure, lb.size_fetus, lb.lie, oedema, lb.presentation, lb.acetone, lb.protein, lb.liquor, lb.height, lb.meconium, lb.estimation_presentation, lb.membrane, lb.last_recorded, lb.blood_group, lb.cervic_state, lb.presenting_part, lb.levels, lbposition, lb.moulding, lb.caput, lb.bony, lb.membranes_liquor, lb.sacral_promontory, lb.sacral_curve, lb.Lachial_spines, lb.subpubic_angle, lb.sacral_tuberosites, lb.summary, lb.remarks,lb.dilation, lb.temperature,lb.admission_reason,lb.admission_from FROM tbl_labour_record2 as lb,tbl_patient_registration as reg,tbl_employee as emp WHERE lb.Registration_ID='$Registration_ID' and reg.Registration_ID=lb.Registration_ID and emp.Employee_ID=lb.Employee_ID";
    
    
        $select_doctor_result = mysqli_query($conn,$select_addmissions1);
        $htm .= "<tbody>";
        while ($select_admission_row = mysqli_fetch_array($select_doctor_result)) {
            $date_time = $select_admission_row['date_time'];
            $bony = $select_admission_row['bony'];
            $cervic_state = $select_admission_row['cervic_state'];
            $blood_pressure = $select_admission_row['blood_pressure'];
            $sacral_curve = $select_admission_row['sacral_curve'];
            $sacral_promontory = $select_admission_row['sacral_promontory'];
            $dilation = $select_admission_row['dilation'];
            $presenting_part = $select_admission_row['presenting_part'];
            $Lachial_spines = $select_admission_row['Lachial_spines'];
            $levels = $select_admission_row['levels'];
            $subpubic_angle = $select_admission_row['subpubic_angle'];
            $position = $select_admission_row['position'];
            $sacral_tuberosites = $select_admission_row['sacral_tuberosites'];
            $moulding = $select_admission_row['moulding'];
            $summary = $select_admission_row['summary'];
            $caput = $select_admission_row['caput'];
            $membranes_liquor = $select_admission_row['membranes_liquor'];
            $remarks = $select_admission_row['remarks'];

            $htm .= "<tr><td style='text-align:left;font-size:20px;'>" . $date_time . "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $bony. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$cervic_state. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$blood_pressure. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_curve. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_promontory. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$dilation. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$presenting_part. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$Lachial_spines. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$levels. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$subpubic_angle. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$position. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_tuberosites. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$moulding. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$membranes_liquor. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$remarks. "</td>";
            $htm .= "</tr>";
        }
        $htm .= "<tr><td style='text-align:left;font-size:20px;'>" . $date_time . "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" . $bony. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$cervic_state. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$blood_pressure. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_curve. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_promontory. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$dilation. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$presenting_part. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$Lachial_spines. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$levels. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$subpubic_angle. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$position. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$sacral_tuberosites. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$moulding. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$membranes_liquor. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$remarks. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$remarks. "</td>";
            $htm .= "<td style='text-align:left;font-size:20px;'>" .$remarks. "</td>";
            $htm .= "</tr>";
        $htm .= ' </table></center>';
        

include("./MPDF/mpdf.php");
$mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
?>
