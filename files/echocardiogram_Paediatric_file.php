<style>
    
    .userinfo td,tr{
        height:20px ;
        border:none !important; 
    }
    .userinfo tr{
        border:none !important;
    }
    .headerTitle{
        background:#ccc;padding:5px;font-size: x-large;font-weight:bold;text-align:left;  
        width:100%;    
    }
    .modificationStats:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0);
    }

    .prevHistory:hover{
        text-decoration: underline;
        cursor:pointer;
        color: rgb(145,0,0); 
    }
    .no_color{
        color:inherit;
        text-decoration:none;  
    }
    .headerPage{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style>
<?php
include("./includes/connection.php");
include 'Patient_Record_Review_out_frame_print.php';
include 'Patient_Record_Review_in_frame_print.php';
@session_start();


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$data .= "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
include("./includes/connection.php");

$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
$patient_id = $_GET['patient_id'];

//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $select_Patient = mysqli_query($conn, "SELECT Old_Registration_Number, Title,Patient_Name, pr.Sponsor_ID, Date_Of_Birth, Gender, pr.Region, pr.Country, pr.Diseased, pr.District, pr.Ward,pr.Patient_Picture, Member_Number, Member_Card_Expire_Date, pr.Phone_Number, Email_Address,Occupation, Employee_Vote_Number, Emergence_Contact_Name, Emergence_Contact_Number, Company, Registration_ID, Employee_ID, Registration_Date_And_Time, Guarantor_Name, Claim_Number_Status, Registration_ID, sp.Postal_Address, sp.Benefit_Limit FROM tbl_patient_registration pr, tbl_sponsor sp WHERE pr.Sponsor_ID = sp.Sponsor_ID and Registration_ID = '$patient_id'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Title = $row['Title'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Country = $row['Country'];
            $Patient_Picture = $row['Patient_Picture'];
            $Deseased = ucfirst(strtolower($row['Diseased']));
            $Sponsor_Postal_Address = $row['Postal_Address'];
            $Benefit_Limit = $row['Benefit_Limit'];
            $Region = $row['Region'];
            $District = $row['District'];
            $Ward = $row['Ward'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Claim_Number_Status = $row['Claim_Number_Status'];
            $Member_Number = $row['Member_Number'];
            $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
            $Phone_Number = $row['Phone_Number'];
            $Email_Address = $row['Email_Address'];
            $Occupation = $row['Occupation'];
            $Employee_Vote_Number = $row['Employee_Vote_Number'];
            $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
            $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
            $Company = $row['Company'];
            $Employee_ID = $row['Employee_ID'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            // echo $Ward."  ".$District."  ".$Ward; exit;
        }

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
    } else {
        $Registration_ID = '';
        $Old_Registration_Number = '';
        $Title = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Country = '';
        $Deseased = '';
        $Sponsor_Postal_Address = '';
        $Benefit_Limit = '';
        $Patient_Picture = '';
        $Region = '';
        $District = '';
        $Ward = '';
        $Guarantor_Name = '';
        $Claim_Number_Status = '';
        $Member_Number = '';
        $Member_Card_Expire_Date = '';
        $Phone_Number = '';
        $Email_Address = '';
        $Occupation = '';
        $Employee_Vote_Number = '';
        $Emergence_Contact_Name = '';
        $Emergence_Contact_Number = '';
        $Company = '';
        $Employee_ID = '';
        $Registration_Date_And_Time = '';
        $age = 0;
    }
} else {
    $Registration_ID = '';
    $Old_Registration_Number = '';
    $Title = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Country = '';
    $Deseased = '';
    $Sponsor_Postal_Address = '';
    $Benefit_Limit = '';
    $Patient_Picture = '';
    $Region = '';
    $District = '';
    $Ward = '';
    $Guarantor_Name = '';
    $Claim_Number_Status = '';
    $Member_Number = '';
    $Member_Card_Expire_Date = '';
    $Phone_Number = '';
    $Email_Address = '';
    $Occupation = '';
    $Employee_Vote_Number = '';
    $Emergence_Contact_Name = '';
    $Emergence_Contact_Number = '';
    $Company = '';
    $Employee_ID = '';
    $Registration_Date_And_Time = '';
    $age = 0;
}

//clinical informations records
$clinical_info = mysqli_query($conn,"SELECT patient_item_cache_list_id, patient_id, SPO2, PPR, WT, Abdominal_situs, Cardiac_position, SVC_RA, IVC_RA, pulmonary_drainage, Atrio_ventricular, avc_Remarks, Left_attrium, la_Remarks, Right_attrium, ra_Remarks, Mitral_valve, mv_Remarks, tricuspid_valves, tv_Remarks, Aortic_valve, aortic_Remarks, pulmonary_Remarks, lv_ventricles, lv_Remarks, rv_ventricles, rv_Remarks, IAS, IVS, IVS_Remarks, PDA, Aortic_Arch, LA_AO, LVPWD, p_final_impression, p_recommendation, others FROM tbl_paediatric_information WHERE patient_id = '$patient_id' and patient_item_cache_list_id = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
while($row = mysqli_fetch_array($clinical_info)){
    $short_patient_id = $row['short_patient_id'];
    $short_item_cache_list_id = $row['short_item_cache_list_id'];
    $SPO2 = $row['SPO2'];
    $PPR = $row['PPR'];
    $WT = $row['WT'];
    $Abdominal_situs = $row['Abdominal_situs'];
    $SVC_RA = $row['SVC_RA'];
    $IVC_RA = $row['IVC_RA'];
    $pulmonary_drainage = $row['pulmonary_drainage'];
    $Atrio_ventricular = $row['Atrio_ventricular'];
    $avc_Remarks = $row['avc_Remarks'];
    $Ventricular_arterial = $row['Ventricular_arterial'];
    $Mitral_valve = $row['Mitral_valve'];
    $vac_Remarks = $row['vac_Remarks'];
    $Left_attrium = $row['Left_attrium'];
    $la_Remarks = $row['la_Remarks'];
    $tricuspid_valves = $row['tricuspid_valves'];
    $Right_attrium = $row['Right_attrium'];
    $ra_Remarks = $row['ra_Remarks'];
    $mv_Remarks = $row['mv_Remarks'];
    $tv_Remarks = $row['tv_Remarks'];
    $Aortic_valve = $row['Aortic_valve'];
    $aortic_Remarks = $row['aortic_Remarks'];
    $pulmonary_valves = $row['pulmonary_valves'];
    $pulmonary_Remarks = $row['pulmonary_Remarks'];
    $lv_ventricles = $row['lv_ventricles'];
    $lv_Remarks = $row['lv_Remarks'];
    $rv_ventricles = $row['rv_ventricles'];
    $rv_Remarks = $row['rv_Remarks'];
    $IAS = $row['IAS'];
    $IVS = $row['IVS'];
    $IVS_Remarks = $row['IVS_Remarks'];
    $PDA = $row['PDA'];
    $Aortic_Arch = $row['Aortic_Arch'];
    $LA_AO = $row['LA_AO'];
    $LVPWD = $row['LVPWD'];
    $Cardiac_position = $row['Cardiac_position'];
    $MV = $row['MV'];
    $p_final_impression = $row['p_final_impression'];
    $p_recommendation = $row['p_recommendation'];
    $others = $row['others'];
}

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}

$data .= '<fieldset style="width:99%; ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
<div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  ">
    <b align="center"> PAEDIATRIC ECHOCARDIOGRAPHY RECORDS</b>
</div>
<div style="margin:2px;border:1px solid #000">
    <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
        <tr>
            <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="" style="width:30%;text-align:left ">'. $Patient_Name.'</td>
            <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">'. $Country.'</td>
            <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">'.$Region.'</td>
        </tr>
        <tr>
            <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>'. $Registration_ID.'</td><td style="text-align:right"><b>Phone #:</b></td><td style="">'. $Phone_Number .'</td><td style="text-align:right"><b>District:</b></td><td style="">'. $District.'</td>
        </tr>
        <tr>
            <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">'.date("j F, Y", strtotime($Date_Of_Birth)).' </td><td style="text-align:right"><b>Gender:</b></td><td style="">'. $Gender .'</td><td style="text-align:right"><b>Diseased:</b></td><td style="">'. $Deseased .'</td>
        </tr>
        <tr>
            <td style="width:14%;text-align:right"><b>Insurance Details:</b></td><td colspan="2" style="width:100%;text-align:left"> '. $Guarantor_Name .$sponsoDetails.'</td>
            <td style="width:14%;text-align:right"><b>Perfromed By:</b></td><td colspan="2" style="width:100%;text-align:left"> '.$Employee_Name.'</td>
        </tr>
    </table>
</div><br>
<table class="table" width="100%">
<tbody>
    <tr>
        <td>SPO2</td><th>'.$SPO2.'</th>
        <td>PR</td><th>'.$PPR.'</th>
        <td>WT</td><th>'.$WT.'</th>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6" style="background:#ccc;">PROFILE</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>Abdominal Situs</td><td colspan="2"><b>'.$Abdominal_situs.'</b></td>
        <td>Cardiac Position</td><td colspan="2"><b>'.$Cardiac_position.'</b></td>
    </tr>
    <tr>
        <td>System Venous Drainage</td><td colspan="2">SVC AND IVC TO RA: <b>'.$SVC_RA.'</b></td>
        <td colspan="3"><b>'.$IVC_RA.'</b></td>
    </tr>
    <tr>
        <td>Pulmonary Venous Drainage</td><td colspan="2">ALL 4 PV(s) TO LA: <b>'.$pulmonary_drainage.'</b></td>
        <td colspan="3"><b>'.$pulmonary_Remarks.'</b></td>
    </tr>
    <tr>
        <td>Atrio Ventricular Connection</td><td colspan="2"><b>'.$Atrio_ventricular.'</b></td>
        <td colspan="3"><b>'.$avc_Remarks.'</b></td>
    </tr>
    <tr>
        <td>Ventricular Arterial Connection</td><td colspan="2"><b>'.$Ventricular_arterial.'</b></td>
        <td colspan="3"><b>'.$vac_Remarks.'</b></td>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">ATRIA</th>
    </tr>
    <tr>
        <td colspan="2">Left Atrium</td><td colspan="2"><b>'.$Left_attrium.'</b></td><th colspan="2"><b>'.$la_Remarks.'</b></th>
    </tr>
    <tr>
        <td colspan="2">Right Atrium</td><td colspan="2"><b>'.$Right_attrium.'</b></td><th colspan="2"><b>'.$ra_Remarks.'</b></th>
    </tr>
        
</tbody>

</table>

<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">ATRIOVENTRICULAR VALVEs</th>
    </tr>
    <tr>
        <td colspan="2">Mitral Valve</td><td colspan="2"><b>'.$Mitral_valve.'</b></td><th colspan="2"><b>'.$mv_Remarks.'</b></th>
    </tr>
    <tr>
        <td colspan="2">Tricuspid Valve</td><td colspan="2"><b>'.$tricuspid_valves.'</b></td><th colspan="2"><b>'.$tv_Remarks.'</b></th>
    </tr>
</tbody>
</table>


<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">SEMILUNAR VALVES</th>
    </tr>
    <tr>
        <td colspan="2">Aortic Valve</td><td colspan="2"><b>'.$Aortic_valve.'</b></td><th colspan="2"><b>'.$aortic_Remarks.'</b></th>
    </tr>
    <tr>
        <td colspan="2">Pulmonary Valve</td><td colspan="2"><b>'.$pulmonary_valves.'</b></td><th colspan="2"><b>'.$pulmonary_Remarks.'</b></th>
    </tr>
</tbody>
</table>


<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">VENTRICLES</th>
    </tr>
    <tr>
        <td colspan="2">Left Ventricle</td><td colspan="2"><b>'.$lv_ventricles.'</b></td><th colspan="2"><b>'.$lv_Remarks.'</b></th>
    </tr>
    <tr>
        <td colspan="2">Right Ventricle</td><td colspan="2"><b>'.$rv_ventricles.'</b></td><th colspan="2"><b>'.$rv_Remarks.'</b></th>
    </tr>
</tbody>
</table>


<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">SEPTAE</th>
    </tr>
    <tr>
        <td colspan="2">IAS</td><td colspan="4"><b>'.$IAS.'</b></td>
    </tr>
    <tr>
        <td colspan="2">IVS</td><td colspan="4"><b>'.$IVS.'</b></td>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<tbody>
    <tr>
        <td colspan="2">PDA</td><td colspan="4"><b>'.$PDA.'</b></td>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">ARCH</th>
    </tr>
    <tr>
        <td colspan="2">AORTIC ARCH</td><td colspan="4"><b>'.$Aortic_Arch.'</b></td>
    </tr>
</tbody>
</table>

<br/><br/>
<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6">2D/ M-MODE MEASUREMENTS</th>
    </tr>
</thead>
<tbody>
    <tr style="background:#ccc;">
        <th>PARAMETER</th>
        <th colspan="2">RESULTS</th>
        <th>PARAMETER</th>
        <th colspan="2">RESULTS</th>
    </tr>
    <tr>
        <td >LA:AO</td><td colspan="2"><b>'.$LA_AO.'</b></td>
        <td >LVPWD/S</td><td colspan="2"><b>'.$LVPWD.'</b></td>
    </tr>
    <tr>
        <td >LVIDD/S (mm)</td><td colspan="2"><b>'.$lvidd.'</b></td>
        <td >LVFS (%)</td><td colspan="2"><b>'.$LVFS.'%</b></td>
    </tr>
    <tr>
        <td >IVSD/S (mm)</td><td colspan="2"><b>'.$IVSD.'</b></td>
        <td >LVEF (%)</td><td colspan="2"><b>'.$LVEF.'%</b></td>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<tbody>
    <tr style="background:#ccc;">
        <th colspan="6">EFFUSION</th>
    </tr>
    <tr>
        <td colspan="2">PLEURAL</td><td colspan="4"><b>'.$preural.'</b></td>
    </tr>
    <tr>
        <td colspan="2">PERICARDIAL</td><td colspan="4"><b>'.$pericardial.'</b></td>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
      <tr style="background:#ccc;">
        <th colspan="6">OTHER DETAILS</th>
    </tr>
    <tr>
        <td colspan="6">'.$others.'</td>
    </tr>
</table>

<table class="table" width="100%">
      <tr style="background:#ccc;">
        <th colspan="6">FINAL IMPRESSION</th>
    </tr>
    <tr>
        <td colspan="6">'.$p_final_impression.'</td>
    </tr>
</table>
</table>
<table class="table" width="100%">
      <tr style="background:#ccc;">
        <th colspan="6">RECOMMENDATION</th>
    </tr>
    <tr>
        <td colspan="6">'.$p_recommendation.'</td>
    </tr>
</table>
</fieldset>';
$Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID'"))['Employee_Name'] or die(mysqli_error($conn));
//echo $data;

include("MPDF/mpdf.php");
$mpdf = new mPDF('', 'A4');
$mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->Output('echocardiogram Report.pdf','I');


?>


