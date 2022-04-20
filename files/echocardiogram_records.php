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

$Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];

//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
//    select patient information
if (isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
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
} 
//clinical informations records
$clinical_info = mysqli_query($conn,"SELECT ABP,APR,aortic,aortic1,L_atrium,L_atrium1,R_atrium,R_atrium1,rvid,patient_id,payment_item_cache_list_id,rvid1,Tapse,Tapse1,mv_area, mv_area1,lv_systolic,lvef,chamber,regional,MV_AV,Thrombus,Vegetation,Effusion,IVC,E_A,AV_Vmax,MR,AR,MS,MS_Remarks,MR_Remarks1,TR,Pulmonary_valve, tr_Vmax, tr_Vmax_Remarks, RVSP, lvtdi_septal, lvtdi_leptal, rvtdi_sa, es_ratio, a_final_impression, a_recommendation, lv_diastolic, TV, TV_structure, PV, PV_structure, IAS, MV, IVC, AV, MV_structure, AV_structure, others FROM tbl_clinical_information WHERE patient_id = '$patient_id' and Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
while($row = mysqli_fetch_assoc($clinical_info)){
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
    $PV = $row['PV'];
    $IAS = $row['IAS'];
    $MV = $row['MV'];
    $AV = $row['AV'];
    $AV_structure = $row['AV_structure'];
    $MV_structure = $row['MV_structure'];
    $others = $row['others'];

}


if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
$sponsoDetails = '';
if (strtolower($Guarantor_Name) != 'cash') {
    $sponsoDetails = ',&nbsp;&nbsp;<b>Address:</b>  ' . $Sponsor_Postal_Address . ' ,&nbsp;&nbsp;<b>Benefit Limit:</b>' . $Benefit_Limit . '';
}

if ($clinical_info > 0){
echo '<fieldset style="width:99%; ;padding:5px;background-color:white;margin-top:20px;overflow-x:hidden;overflow-y:scroll">
    <div style="padding:5px; width:99%;font-size:larger;border:1px solid #000;  background:#ccc;text-align:center  ">
        <b align="center">ECHOCARDIOGRAM RECORDS FOR '.$Patient_Name.'</b>
    </div>
    <div style="margin:2px;border:1px solid #000">
        <table class="userinfo" border="0" style="border:none !important" width="100%" style="margin-left:2px">
            <tr>
                <td style="width:10%;text-align:right "><b>Patient Name:</b></td><td colspan="" style="width:30%;text-align:left ">'. $Patient_Name.'</td>
                <td style="width:10%;text-align:right "><b>Country:</b></td><td colspan="">'. $Country.'</td>
                <td style="width:10%;text-align:right "><b>Region:</b></td><td colspan="">'.$Region.'</td>
                <td rowspan="4" width="100">
                    <img width="120" height="90" name="Patient_Picture" id="Patient_Pictured" src="./patientImages/'. $Patient_Picture.'">
                </td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Registration #:</b></td><td>'. $Registration_ID.'</td><td style="text-align:right"><b>Phone #:</b></td><td style="">'. $Phone_Number .'</td><td style="text-align:right"><b>District:</b></td><td style="">'. $District.'</td>
            </tr>
            <tr>
                <td style="width:10%;text-align:right"><b>Date of Birth:</b></td><td style="">'.date("j F, Y", strtotime($Date_Of_Birth)).' </td><td style="text-align:right"><b>Gender:</b></td><td style="">'. $Gender .'</td><td style="text-align:right"><b>Diseased:</b></td><td style="">'. $Deseased .'</td>
            </tr>
            <tr>
                <td style="width:14%;text-align:right"><b>Insurance Details:</b></td><td colspan="2" style="width:100%;text-align:left"> '. $Guarantor_Name .$sponsoDetails.'</td>
            </tr>
        </table>
    </div><br><br>
    <table class="table">
        <thead>
            <tr>
               <th>ADULT ECHOCARDIOGRAPHY RECORDS</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <th>BP:</th>
            <th colspan="2">'.$ABP.'</th>
            <th>PR:</th>
            <th colspan="2">'.$APR.'</th>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">A. 2D/M-MODE PARAMETERS</span></td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
            <th>PARAMETER</th>
            <th>NORMAL</th>
            <th>RESULTS</th>
            <th>PARAMETER</th>
            <th>NORMAL RANGE</th>
            <th>RESULTS</th>
        </tr>
        <tr><td colspan="6"><hr></td>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>Aortic Root</th>
            <th>20 - 37 mm</th>
            <th>'.$aortic.'</th>
            <th>IVS (d)</th>
            <th>6 - 11 mm</th>
            <th>'.$aortic1.'</th>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>Left Atrium</th>
            <th>19 - 40mm</th>
            <th>'.$L_atrium.'</th>
            <th>LVPW (d)</th>
            <th>6 - 11 mm</th>
            <th>'.$L_atrium1.'</th>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>Right Atrium</th>
            <th>14 - 26 mm</th>
            <th>'.$R_atrium.'</th>
            <th>LVID (d)</th>
            <th>33 - 55 mm</th>
            <th>'.$R_atrium1.'</th>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>RVID (d)</th>
            <th>19 - 38 mm</th>
            <th>'.$rvid.'></th>
            <th>LVID (s)</th>
            <th>6 - 11 mm</th>
            <th>'.$rvid1.'</th>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>Tapse</th>
            <th>15 - 20 mm</th>
            <th>'.$Tapse.'</th>
            <th>Fractional Shortening</th>
            <th>29 - 37%</th>
            <th>'.$Tapse1.'</th>
        </tr>
        <tr style="border: 2px solid #fff;">
            <th>MV Area Planimetry</th>
            <th>4 - 6sqr cm</th>
            <th>'.$mv_area.'</th>
            <th>LV Ejection Fraction</th>
            <th>55 - 70%</th>
            <th>'.$mv_area1.'</th>
            </tr>
            <tr>
            <tr><td colspan="6"><hr></td>
        </tr>

            <tbody >
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">B. SIMPSONS BIPLANE METHOD</span></td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
                <th colspan="2">PARAMETER</th>
                <th colspan="2">NORMAL RANGE</th>
                <th colspan="2">RESULTS</th>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">LV end diastolic volume (ml)</th>
                <th colspan="2">83 - 107</th>
                <th colspan="2">'.$lv_diastolic.'</th>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">LV end systolic volume (ml)</th>
                <th colspan="2">20 - 37mm</th>
                <th colspan="2">'.$lv_systolic.'</th>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">LVEF</th>
                <th colspan="2">>55%</th>
                <th colspan="2">'.$lvef.'</th>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>

            <tr>
            <th><label for="">Chamber Sizes: </label></th><td> 
            <span>'.$chamber.'</span></td>
            <th><label for="">Regional Wall Motion Abnormality: </label></th><td colspan="3"> 
            <span>'.$regional.'</span></td>
            </tr>
            <tr>
            <th><label for="">MV Structure:</label></th><td> 
            <span>'.$MV_structure.'</span></td> 
            <th><label for="" style="color: red;">*** If Abnormal:</label></th><td colspan="3">'.$MV.'</td> 
            </tr>
            <tr>
            <th><label for="">AV Structure:</label></th><td> 
            <span>'.$AV_structure.'</span></td> 
            <th><label for="" style="color: red;">*** If Abnormal:</label></th><td colspan="3">'.$AV.'</td> 
            </tr>
            <tr>
            <th><label for="">PV Structure:</label></th><td> 
            <span>'.$PV_structure.'</span></td> 
            <th><label for="" style="color: red;">*** If Abnormal:</label></th><td colspan="3">'.$PV.'</td> 
            </tr>
            <tr>
            <th><label for="">TV Structure:</label></th><td> 
            <span>'.$TV_structure.'</span></td> 
            <th><label for="" style="color: red;">*** If Abnormal:</label></th><td colspan="3">'.$TV.'</td> 
            </tr>
            <tr>
            <th><label for="">IAS: </label></th><td> 
            <span>'.$IAS.'</span></td>
            <th><label for="">IVS: </label></th><td> 
            <span>'.$IVS.'</span></td>
            <th><label for="">AV/VA: </label></th><td> 
            <span>'.$AV_VA.'</span></td>
            </tr><tr>
            <th><label for="">Thrombus: </label></th><td> 
            <span>'.$Thrombus.'</span></td>
            <th><label for="">Vegetation: </label></th><th> 
            <span>'.$Vegetation.'</span></th>
            <th><label for="">Effusion: </label></th><td> 
            <span>'.$Effusion.'</span></td>
            </tr>
            <tr>
            <th><label for="">IVC: </label></th><td> 
            <span>'.$IVC.'</span></td>
            </tr>
            <tr><th colspan="6"><hr></th>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">C. DOPPLER PARAMETERS</span></td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
                <th colspan="3">MITRAL VALVE</th>
                <th colspan="3">AORTIC VALVE</th>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="1">E/A:</th>
                <td colspan="2">'.$E_A.'></td>
                <th colspan="1">AV Vmax:</th>
                <td colspan="2">'.$AV_Vmax.'</td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="1">MR:</th>
                <td colspan="2">'.$MR.'</td> 
                <th colspan="1">AR:</th>
                <td colspan="2">'.$AR.'</td> 
            </tr>
            <tr style="border: 2px solid #fff;">
                <th>MS:</th>
                <td>'.$MS.'</td>
                <td>'.$MS_remarks.'</td>
                <td colspan="3">'.$MS_remarks1.'</td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
                <th colspan="3">TRICUSPID VALVE</th>
                <th colspan="3">PULMONARY VALVE</th>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="1">TR:</th>
                <td colspan="2">'.$TR.'</td> 
                <th colspan="1">Pulmonary Valve:</th>
                <td colspan="2">'.$Pulmonary_Valve.'</td>
            </tr>
                <tr style="border: 2px solid #fff;">
                <th colspan="1">TR Vmax/PG (mmHG):</th>
                <td colspan="2">'.$tr_vmax.'</td>
                <td colspan="1">Remarks/Value:</td>
                <td colspan="2">'.$tr_vmax_Remarks.'</td></tr>
                <tr style="border: 2px solid #fff;">
                <th colspan="1">RVSP:</th>
                <th colspan="2">'.$RVSP.'</th>
                
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">D. TISSUE DOPPLER IMAGING</span></td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
                <th colspan="2">PARAMETER</th>
                <th colspan="2">NORMAL RANGE</th>
                <th colspan="2">RESULTS</th>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">LVTDI Sa (Septal)</th>
                <th colspan="2">> 8 cm/s</th>
                <td colspan="2">'.$lvtdi_septal.'</td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">LVTDI Sa (Leptal)</th>
                <th colspan="2">> 10 cm/s</th>
                <td colspan="2">'.$lvtdi_leptal.'</td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">RVTDI Sa (Septal)</th>
                <th colspan="2">> 12 cm/s</th>
                <td colspan="2">'.$rvtdi_sa.'</td>
            </tr>
            <tr style="border: 2px solid #fff;">
                <th colspan="2">E/s ratio</th>
                <th colspan="2">< 14</th>
                <td colspan="2">'.$es_ratio.'</td>
            </tr>
            <tr><td colspan="6"><hr></td>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">OTHER DETAILS:</span></td>
            </tr>
            <tr><td colspan="6"><textarea id="adult_inputs" class="form_group"  placeholder="Final Impression" name="a_final_impression" class="inp">'.$others.'</textarea></td>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">FINAL IMPRESSION:</span></td>
            </tr>
            <tr><td colspan="6"><textarea id="adult_inputs" class="form_group"  placeholder="Final Impression" name="a_final_impression" class="inp">'.$a_final_impression.'</textarea></td>
            </tr>
            <tr>
            <td colspan="6"><span style="font-weight: bold; font-size: 16px;">RECOMMENDATION:</span></td>
            </tr>
            <tr><td colspan="6"><textarea id="adult_inputs">'.$a_recommendation.'</textarea></td>
            </tr>

                
        </tbody>
    </table>
    </fieldset>';
}
?>
<br>
<a href="echocardiogram_file.php?Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID ?>&patient_id=<?php echo $patient_id ?>" target="_blank" class="art-button-green">PRINT PATIENT FILE</a>
<br><br>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
