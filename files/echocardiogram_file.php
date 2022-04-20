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
    $select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID,sp.Postal_Address,sp.Benefit_Limit
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$patient_id'") or die(mysqli_error($conn));
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
$clinical_info = mysqli_query($conn,"SELECT ABP,APR,aortic,aortic1,L_atrium,L_atrium1,R_atrium,R_atrium1,rvid,patient_id,payment_item_cache_list_id,rvid1,Tapse,Tapse1,mv_area1,lv_systolic,lvef,chamber,regional,MV_AV,Thrombus,Vegetation,Effusion,IVC,E_A,AV_Vmax,MR,AR,MS,MS_Remarks,MR_Remarks1,TR,Pulmonary_valve, tr_Vmax, tr_Vmax_Remarks, RVSP, lvtdi_septal, lvtdi_leptal, rvtdi_sa, es_ratio, a_final_impression, a_recommendation, lv_diastolic, TV, TV_structure, PV, PV_structure, IAS, MV, AV, MV_structure, AV_structure, Created_at, others FROM tbl_clinical_information WHERE patient_id = '$patient_id' and payment_item_cache_list_id = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
while($row = mysqli_fetch_array($clinical_info)){
    $ABP = $row['ABP'];
    $APR = $row['APR'];
    $aortic = $row['aortic'];
    $aortic1 = $row['aortic1'];
    $L_atrium = $row['L_atrium'];
    $L_atrium1 = $row['L_atrium1'];
    $R_atrium = $row['R_atrium'];
    $R_atrium1 = $row['R_atrium1'];
    $rvid = $row['rvid'];
    $rvid1 = $row['rvid1'];
    $Tapse = $row['Tapse'];
    $Tapse1 = $row['Tapse1'];
    $mv_area = $row['mv_area'];
    $mv_area1 = $row['mv_area1'];
    $lv_systolic = $row['lv_systolic'];
    $lvef = $row['lvef'];
    $chamber = $row['chamber'];
    $regional = $row['regional'];
    $MV_AV = $row['MV_AV'];
    $Thrombus = $row['Thrombus'];
    $Vegetation = $row['Vegetation'];
    $Effusion = $row['Effusion'];
    $IVC = $row['IVC'];
    $E_A = $row['E_A'];
    $AV_Vmax = $row['AV_Vmax'];
    $MR = $row['MR'];
    $AR = $row['AR'];
    $MS = $row['MS'];
    $MS_Remarks = $row['MS_Remarks'];
    $MR_Remarks1 = $row['MR_Remarks1'];
    $TR = $row['TR'];
    $Pulmonary_valve = $row['Pulmonary_valve'];
    $tr_Vmax = $row['tr_Vmax'];
    $tr_Vmax_Remarks = $row['tr_Vmax_Remarks'];
    $RVSP = $row['RVSP'];
    $lvtdi_septal = $row['lvtdi_septal'];
    $lvtdi_leptal = $row['lvtdi_leptal'];
    $rvtdi_sa = $row['rvtdi_sa'];
    $es_ratio = $row['es_ratio'];
    $a_final_impression = $row['a_final_impression'];
    $a_recommendation = $row['a_recommendation'];
    $lv_diastolic = $row['lv_diastolic'];
    $TV = $row['TV'];
    $TV_structure = $row['TV_structure'];
    $PV_structure = $row['PV_structure'];
    $MV_structure = $row['MV_structure'];
    $AV_structure = $row['AV_structure'];
    $PV = $row['PV'];
    $IAS = $row['IAS'];
    $MV = $row['MV'];
    $AV = $row['AV'];
    $Created_at = $row['Created_at'];
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
    <b align="center">ADULT ECHOCARDIOGRAPHY RECORDS</b>
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
        <td>BP</td><th colspan="2">'.$ABP.'mmHG</td>
        <td>PR</td><th colspan="2">'.$APR.'</th>
    </tr>
    <tr>
        <td >Perfomed at</td><th colspan="5">'.$Created_at.'</th>
    </tr>
</tbody>
</table>

<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6">A. 2D/M-MODE PARAMETERS</th>
    </tr>
</thead>
<tbody>
    <tr style="background:#ccc">
        <th>PARAMETER</th>
        <th>NORMAL RANGE</th>
        <th>RESULTS</th>
        <th>PARAMETER</th>
        <th>NORMAL RANGE</th>
        <th>RESULTS</th>
    </tr>
    <tr>
        <td>Aortic Root</td><td>20 - 37mm</td><th>'.$aortic.'mm</th>
        <td>IVS</td><td>6 - 11mm</td><th>'.$aortic1.'mm</th>
    </tr>
    <tr>
        <td>Left Atrium</td><td>14 - 26mm</td><th>'.$L_atrium.'mm</th>
        <td>LVPW (d)</td><td>6 - 11mm</td><th>'.$L_atrium1.'mm</th>
    </tr>
    <tr>
        <td>Right Atrium</td><td>19 - 40mm</td><th>'.$R_atrium.'mm</th>
        <td>LVID (s)</td><td>6 - 11mm</td><th>'.$R_atrium1.'mm</th>
    </tr>
    <tr>
        <td>RVID (d)</td><td>19 - 38mm</td><th>'.$rvid.'mm</th>
        <td>LVID (d)</td><td>33 - 55mm</td><th>'.$rvid1.'mm</th>
    </tr>
    <tr>
        <td>Tapse</td><td>15 - 20mm</td><th>'.$Tapse.'m</th>
        <td>Fractional Shortening</td><td>29 - 37%</td><th>'.$Tapse1.'%</th>
    </tr>
    <tr>
        <td>MV Area Planimentry</td><td>4 - 6sqr cm</td><th>'.$mv_area.'sqr cm</th>
        <td>LV Ejection Fraction</td><td>50 - 70%</td><th>'.$mv_area1.'%</th>
    </tr>
    
</tbody>
</table>

<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6">B. SIMPSONS BIPLANE</th>
    </tr>
</thead>
<tbody>
    <tr style="background:#ccc;">
        <th colspan="2">PARAMETER</th>
        <th colspan="2">NORMAL RANGE</th>
        <th colspan="2">RESULTS</th>
    </tr>
    <tr>
        <td colspan="2">LV and diastolic Volume</td><td colspan="2">83 - 107</td><th colspan="2">'.$lv_diastolic.'</th>
    </tr>
    <tr>
        <td colspan="2">LV and systolic Volume</td><td colspan="2">20 - 37mm</td><th colspan="2">'.$lv_systolic.'mm</th>
    </tr>
    <tr>
        <td colspan="2">LVEF</b></td><td colspan="2">> 55%</td><th colspan="2">'.$lvef.'%</th>
    </tr>
    <tr>
        <td colspan="3">Chamber Sizes: &nbsp;<b>'.$chamber.'</b></td><td colspan="3">*** If Abnormal: &nbsp;<b>'.$MV.'</b></td>
    </tr>
    <tr>
        <td colspan="3">MV Structure: &nbsp;<b>'.$MV_structure.'</b></td><td colspan="3">*** If Abnormal: &nbsp;<b>'.$MV.'</b></td>
    </tr>
    <tr>
        <td colspan="3">AV Structure: &nbsp;<b>'.$AV_structure.'</b></td><td colspan="3">*** If Abnormal: &nbsp;<b>'.$AV.'</b></td>
    </tr>
    <tr>
        <td colspan="3">PV Structure: &nbsp;<b>'.$PV_structure.'</b></td><td colspan="3">*** If Abnormal: &nbsp;<b>'.$PV.'</b></td>
    </tr>
    <tr>
        <td colspan="3">TV Structure: &nbsp;<b>'.$TV_structure.'</b></td><td colspan="3">*** If Abnormal: &nbsp;<b>'.$TV.'</b></td>
    </tr>
    <tr>
        <td colspan="2">IAS: <b>'.$IAS.'</b></b></td><td colspan="2">IVS: <b>'.$IAS.'</b></td><td colspan="2">AV/VA: <b>'.$MV_AV.'</b></td>
    </tr>
    <tr>
        <td colspan="2">Vegetation: <b>'.$Vegetation.'</b></b></td><td colspan="2">Thrombus: <b>'.$Thrombus.'</b></td><td colspan="2">AV/Effusion: <b>'.$Effusion.'</b></td>
    </tr>
    <tr>
        <td colspan="2">IVC: <b>'.$IVC.'</b></b></td>
    </tr>
        
</tbody>

</table>
<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6">C: DOPPLER PARAMETERS </th>
    </tr>
</thead>
<tbody>
    <tr style="background:#ccc;">
        <th colspan="3">MITRAL VALVE</th>
        <th colspan="3">AORTIC VALVE</th>
    </tr>
    <tr>
        <td colspan="3">E/A: &nbsp;<b>'.$E_A.'</b></td><td colspan="3">AV Vmax: &nbsp;<b>'.$AV_Vmax.'</b></td>
    </tr>
    <tr>
        <td colspan="3">MR: &nbsp;<b>'.$MR.'</b></td><td colspan="3">AR: &nbsp;<b>'.$AR.'</b></td>
    </tr>
    <tr>
        <td>MS: &nbsp;<b>'.$MS.'</b></td><td colspan="2">Remarks: &nbsp;<b>'.$MS_Remarks.'</b></td><td colspan="3">Remarks &nbsp;<b>'.$MR_Remarks1.'</b></td>
    </tr>
    <tr style="background:#ccc;">
        <th colspan="3">TRICUPSID VALVE</th>
        <th colspan="3">PULMONARY VALVE</th>
    </tr>
    <tr>
        <td colspan="3">TR: &nbsp;<b>'.$TR.'</b></td><td colspan="3">Pulmonary Valve: &nbsp;<b>'.$Pulmonary_valve.'</b></td>
    </tr>
    <tr>
        <td colspan="3">TR Vmax/PG: &nbsp;<b>'.$tr_Vmax.'mmHG</b></td><td colspan="3">Remarks: &nbsp;<b>'.$tr_Vmax_Remarks.'</b></td>
    </tr>
    <tr>
        <td>RVSP: &nbsp;<b>'.$RVSP.'</b></td>
    </tr>
</tbody>
</table>


</table>
<table class="table" width="100%">
<thead>
    <tr>
       <th colspan="6">D: TISSUE DOPPLER IMAGING </th>
    </tr>
</thead>
    <tr style="background:#ccc;">
        <th colspan="2">PARAMETER</th>
        <th colspan="2">NORMAL RANGE</th>
        <th colspan="2">RESULTS</th>
    </tr>
    <tr>
        <td colspan="2">LVTDI Sa (Septal)</td><td colspan="2">> 8 cm/s</td><th colspan="2">'.$lvtdi_septal.'</th>
    </tr>
    <tr>
        <td colspan="2">LVTDI Sa (Leptal)</td><td colspan="2">> cm/s</td><th colspan="2">'.$lvtdi_leptal.'</th>
    </tr>
    <tr>
        <td colspan="2">RVTDI Sa (Septal)</b></td><td colspan="2">> 12cm/s</td><th colspan="2">'.$rvtdi_sa.'</th>
    </tr>
    <tr>
        <td colspan="2">E/s ratio</b></td><td colspan="2">< 14</td><th colspan="2">'.$es_ratio.'</th>
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
        <td colspan="6">'.$a_final_impression.'</td>
    </tr>
</table>
<table class="table" width="100%">
      <tr style="background:#ccc;">
        <th colspan="6">RECOMMENDATION</th>
    </tr>
    <tr>
        <td colspan="6">'.$a_recommendation.'</td>
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


