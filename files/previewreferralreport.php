<?php

@session_start();
include("includes/connection.php");
include("includes/cleaninput.php");

$employee_id = $_SESSION['userinfo']['Employee_ID'];

    $_GET = sanitize_input($_GET);

    $referr_id = $_GET['referr_id'];

    $patients_tobeadmitted = "
		SELECT rp.status,referr_id, rp.date_processed, Patient_Name,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,pr.District,pr.Region,Date_Of_Birth,rp.Registration_ID,rp.Patient_Payment_Item_List_ID,Patient_Name,Guarantor_Name,Gender,pr.Phone_Number,Employee_Name,ref_hosp_name,transfer_date, diagnosis, temp, heatrate, resprate, bloodpressure, mental_status, alert, patienthist, chrnicmed, medalergy, pertnetfindings, labresult, radresult, treatmentrendered, reasonfortransfer, doct_phone_number, call_phone_number FROM 
			tbl_referral_patient rp,
                        tbl_referral_hosp rh,
			tbl_patient_registration pr,
			tbl_employee em,
			tbl_sponsor sp
			WHERE 
				pr.Registration_ID = rp.Registration_ID AND
				rp.employee_id = em.Employee_ID AND
				pr.Sponsor_ID = sp.Sponsor_ID AND
                                rp.referral_to = rh.hosp_ID AND
                                referr_id='$referr_id'
	";
	
	$pat_qry = mysqli_query($conn,$patients_tobeadmitted) or die(mysqli_error($conn));
        $rowd=  mysqli_fetch_array($pat_qry);
        
    $html = '<table width ="100%" border="0" style="background-color:white;" class="nobordertable">
          <tr>
             <td style="text-align:center"><img src="branchBanner/branchBanner.png" width="100%" /></td>
          </tr>
          <tr>
             <td  style="text-align:center"><b>PATIENT REFERRAL FORM TO '.$rowd['ref_hosp_name'].'</b><br/> </td>
          </tr>';

    $html .= '<tr>
             <td  style="text-align:center;font-weight:bold">Transfer Date :&nbsp;&nbsp;&nbsp;' . $rowd['transfer_date'] . '<br/><br/></td>
          </tr>';

$html .='</table><br/>';   

$html.= '<table width="100%"  border="0"   class="nobordertable">
                <tr>
                    <td style="text-align: right;"><b>Name:</b></td>
                    <td width="30%">' . $rowd['Patient_Name'] . '</td>
                     
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $rowd['Registration_ID'] . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $rowd['Gender'] . '</td>
                </tr>
               
                <tr>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $rowd['age'] . ' years</td>
                    <td style="text-align: right;" ><b>Region:</b></td>
                    <td>' . $rowd['Region'] . '</td>
                    <td style="text-align: right;"><b>District:</b></b></td>
                    <td>' . $rowd['District'] . '</td>
                </tr>
                <tr>
                    <td style="text-align: right;"><b>Time:</b></td>
                    <td>' . date('H:i:s',strtotime($rowd['date_processed'])) . '</td>
                </tr>
               

            </table><br/>';
        
    $html .= '<table width="100%" >';
   
    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>Diagnosis:</strong>'
            . '</td>'
            . '<td colspan="4">'
            . '' . $rowd['diagnosis'] . '</textarea>'
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td colspan="5">'
            . '<strong>Temperature:</strong>&nbsp;&nbsp;'.$rowd['temp'].'&nbsp;&nbsp;&nbsp;'
            . '<strong>Heart Rate:</strong>&nbsp;&nbsp;'.$rowd['heatrate'].'&nbsp;&nbsp;&nbsp;'
            . '<strong>Respiratory rate:</strong>&nbsp;&nbsp;'.$rowd['resprate'].'&nbsp;&nbsp;&nbsp;'
            . '<strong>Blood Pressure:</strong>&nbsp;&nbsp;'.$rowd['bloodpressure']
            . '</td>'
            . '</tr>';
 
    $html .= '<tr>'
            . '<td style="width:40%" colspan="3">'
            . '<strong>Mental status (Circle):</strong>&nbsp;&nbsp;'
            .$rowd['mental_status']
            . '</td>'
            . '<td colspan="2">'
            . '<strong>Alert:</strong>&nbsp;&nbsp; '.$rowd['alert']
            . '</td>'
            . '</tr>';

  
    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>Patient History:</strong>'
            . '</td>'
             . '<td colspan="4">'
               .$rowd['patienthist']
            . '</td>'
            . '</tr>';


    $html .= '<tr>'
            . '<td>'
            . '<strong>Chronic medications:</strong>'
            . '</td>'
            . '<td colspan="4">'
            .$rowd['chrnicmed']
            . '</td>'
            . '</tr>';


    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>Medication allergies:</strong>'
            . '</td>'
            . '<td colspan="4">'
            .$rowd['medalergy']
            . '</td>'
            . '</tr>';

    $html .= '<tr>'
            . '<td>'
            . '<strong>Pertinent exam findings:</strong> '
            . '</td>'
            . '<td colspan="4">'
            .$rowd['pertnetfindings']
            . '</td>'
            . '</tr>';


    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>Lab results:</strong>'
            . '</td>'
            . '<td colspan="2">'
            .$rowd['labresult']
            . '</td>'
            . '<td>'
            . '<strong>Rad results:</strong>'
            . '</td>'
             . '<td >'
            .$rowd['radresult']
            . '</td>'
            . '</tr>';

    
     $html .= '<tr>'
            . '<td>'
            . '<strong>Treatment rendered prior to transfer:</strong> '
            . '</td>'
            . '<td colspan="4">'
            .$rowd['treatmentrendered']
            . '</td>'
            . '</tr>';
     
      $html .= '<tr>'
            . '<td>'
            . '<strong>Medical reason for transfer:</strong> '
            . '</td>'
            . '<td colspan="4">'
            .$rowd['reasonfortransfer']
            . '</td>'
            . '</tr>';


    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>Doctor Name:</strong>'
            . '</td><td colspan="2">'
            .$rowd['Employee_Name']
            . '</td>'
            . '<td>'
            . '<strong>Phone number</strong>'
            . '</td><td colspan="">'
            .$rowd['doct_phone_number']
            . '</td>'
            . '</td>'
            . '</tr>';
    
    $html .= '<tr>'
            . '<td colspan="">'
            . '<strong>To faciltate transfer of care, call :</strong>'
            . '</td><td colspan="4">'
            .$rowd['call_phone_number']
            . '</td></tr>';

   

    $html .= '</table>';
    
     $html .= '<hr style="width:100%"/><p align="right">Printed on '.date('Y-m-d').'</p>';
    
include("MPDF/mpdf.php");

$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html, 2);

$mpdf->Output();
exit;











