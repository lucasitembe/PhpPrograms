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
                <td style="width:14%;text-align:right"><b>Insurance Details:</b></td><td colspan="2" style="width:100%;text-align:left"> '. $Guarantor_Name.'</td>
            </tr>
        </table>
    </div><br><br>
    <table class="table">
        <thead>
            <tr>
               <th>PAEDIATRIC ECHOCARDIOGRAPHY REPORT</th>
            </thead>   
            <tbody>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">SPO2</label></th><td>
                <input class="form_group" name="SPO2" value="'.$SPO2.'" placeholder="SPO2" type="text" class="inp"></td>
                <th><label for="">PR</label></th><td><input class="form_group" name="PPR" value="'.$PPR.'" placeholder="PR" type="text" class="inp"></td>
                <th><label for="">WT</label></th><td> <span ><input class="form_group" placeholder="WT" value="'.$WT.'" name="WT" type="text" class="inp"></span></td>
                </tr>
                <tr>
                <th><label for="">WARD</label></th>
                <td> 
                    <span >
                        <input class="form_group" name="pulm_valve" placeholder="Not Admitted" type="text">
                    </span>
                </td> 
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">PROFILE</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">Abdominal Situs:</label></th>
                <td> <span >
                <input class="form_group" name="Abdominal_situs" value="'.$Abdominal_situs.'" type="text"></td>
                <th><label for="">Cardiac Position:</label></th>
                <td> <span ><input class="form_group" name="Cardiac_position" value="'.$Cardiac_position.'" type="type"></td>
                </tr>
                <tr>
                <th colspan="2"><label for="">SYSTEMIC VENOUS DRAINAGE</label></th>
                <th><label for="">SVC TO RA:</label></th>
                <td><span ><input class="form_group" name="SVC_RA" value="'.$SVC_RA.'" type="text"></span></td>
                <th><label for="">IVC TO RA:</label></th>
                <td><span ><input  type="text" class="form_group" name="IVC_RA" value="'.$IVC_RA.'"></span></td>
                </tr>
                <tr>
                <th colspan="2"><label for="">PULMONARY VENOUS DRAINAGE</label></th>
                <th><label for="">ALL 4 PVs TO LA:</label></th>
                <td colspan="3"><span ><input type="text" class="inp" class="form_group" name="pulmonary_drainage" value="'.$pulmonary_drainage.'">
                </span></td>
                </tr>
                <tr>
                <th><label for="">Atrio Ventricular Connection</label></th>
                <td> <span ><input class="form_group" name="Atrio_ventricular" type="text" value="'.$Atrio_ventricular.'"></span></td>
               <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input type="text" class="inp" class="form_group" name="avc_Remarks" value="'.$avc_Remarks.'">
                </span></td>
                </tr>
                <tr>
                <th><label for="">Ventricular Artetial Connection</label></th>
                <td> <span ><input class="form_group" name="ventricular_arterial" type="text" value="'.$ventricular_arterial.'"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input type="text" class="inp" class="form_group" name="vac_Remarks" value="'.$vac_Remarks.'">
                </span></td>
                </tr>

                <!-- END OF PROFILE -->
                <tr><td colspan="6"><hr></td>
                </tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">ATRIA</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">Left Atrium:</label></th>
                <td> <span ><input class="form_group" value="'.$Left_attrium.'" name="Left_attrium" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" name="la_Remarks" value="'.$la_Remarks.'" placeholder="Left Atrium" type="text">
                </span></td>
                </tr>
                <th><label for="">Right Atrium:</label></th>
                <td> <span ><input class="form_group" name="Right_attrium" value="'.$Right_attrium.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" name="ra_Remarks" value="'.$ra_Remarks.'" placeholder="Right Atrium" type="text">
                </span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>


                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">ATRIOVENTRICULAR VALVES</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">Mitral Valve:</label></th>
                <td> <span ><input class="form_group" name="Mitral_valve" value="'.$Mitral_valve.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" type="text" class="inp" name="mv_Remarks" value="'.$mv_Remarks.'">
                </span></td>
                </tr>
                <th><label for="">Tricuspid Valve:</label></th>
                <td> <span ><input class="form_group" name="tricuspid_valves" value="'.$tricuspid_valves.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" type="text" class="inp" name="tv_Remarks" value="'.$tv_Remarks.'">
                </span></td>
                </tr>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>


                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">SEMILUNAR VALVES</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">Aortic Valve:</label></th>
                <td> <span ><input class="form_group" name="Aortic_valve" value="'.$Aortic_valve.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" type="text" class="inp" name="aortic_Remarks" value="'.$aortic_Remarks.'">
                </span></td>
                </tr>
                <th><label for="">Pulmonary Valve:</label></th>
                <td> <span ><input class="form_group" name="pulmonary_valves" type="text" value="'.$pulmonary_valves.'"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" type="text" class="inp" name="pulmonary_Remarks" value="'.$pulmonary_Remarks.'">
                </span></td>
                </tr>

                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>

                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">VENTRICLES</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">Left Ventricle:</label></th>
                <td> <span ><input class="form_group" name="lv_ventricles" type="text" value="'.$lv_ventricles.'"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" name="lv_Remarks" type="text" class="inp" value="'.$lv_Remarks.'">
                </span></td>
                </tr>
                <th><label for="">Right Ventricles:</label></th>
                <td> <span ><input class="form_group" name="rv_ventricles" type="text" value="'.$rv_ventricles.'"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group"  type="text" class="inp" name="rv_Remarks" value="'.$rv_Remarks.'">
                </span></td>
                </tr>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>



                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">SEPTAE</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <th><label for="">IAS:</label></th>
                <td> <span ><input class="form_group" name="IAS" value="'.$IAS.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input class="form_group" type="text" class="inp" name="IAS_Remarks" value="'.$IAS_Remarks.'">
                </span></td>
                </tr>
                <th><label for="">IVS:</label></th>
                <td> <span ><input class="form_group" name="IVS" value="'.$IVS.'" type="text"></span></td>
                <th><label for="">*** Remarks:</label></th>
                <td colspan="3"><span ><input type="text" class="inp" class="form_group" name="IVS_Remarks" value="'.$IVS_Remarks.'">
                </span></td>
                </tr>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <span >
                <th><label for="">PDA:</label></th>
                <td colspan="2"><span ><input type="text" class="inp" class="form_group" name="PDA" value="'.$PDA.'">
                </span></td>
                <th><label for="">AORTIC ARCH:</label></th>
                <td colspan="2"><span ><input  type="text" class="inp" class="form_group" name="Aortic_Arch" value="'.$Aortic_Arch.'">
                </span></td>
                </tr>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;"> 2D/M-MODE MEASUREMENTS</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr style="border: 2px solid #fff; font-size: 15px; text-align: center;">
                    <th>PARAMETER</th>
                    <th colspan="2">RESULTS</th>
                    <th>PARAMETER</th>
                    <th colspan="2">RESULTS</th>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr style="border: 2px solid #fff;">
                    <th>LA: AO</th>
                    <th  colspan="2"><input name="LA_AO" class="form_group" type="text" value="'.$LA_AO.'" class="inp" ></th>
                    <th>LVPWD/S</th>
                    <th colspan="2"><input name="LVPWD" class="form_group" type="text" value="'.$LVPWD.'" class="inp" ></th>
                </tr>
                <tr style="border: 2px solid #fff;">
                    <th>LVIDD/S (mm)</th>
                    <th colspan="2"><input name="LVIDD" class="form_group" type="text" value="'.$LVIDD.'"></th>
                    <th>LVFS (%)</th>
                    <th  colspan="2"><input name="LVFS" class="form_group" type="text" class="inp" value="'.$LVFS.'"></th>
                </tr>
                <tr style="border: 2px solid #fff;">
                    <th>IVSD/S (mm)</th>
                    <th  colspan="2"><input name="IVSD_S" type="text" class="inp" class="form_group" value="'.$IVSD_S.'"></th>
                    <th>LVEF (%)</th>
                    <th colspan="2"><input name="LVEF" type="text" class="inp" class="form_group" value="'.$LVEF.'"></th>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">EFFUSION</span></td>
                </tr>
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr style="border: 2px solid #fff;">
                    <th>PLEURAL</th>
                    <th  colspan="2"><input name="pleural" type="text" class="inp" class="form_group" value="'.$pleural.'"></th>
                    <th>PERICARDIAL</th>
                    <th colspan="2"><input name="pericardial"  class="form_group" type="text" class="inp" value="'.$pericardial.'"></th>
                </tr>                  
                <tr><td colspan="6"><hr></td>
                </tr>
                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">OTHER DETAILS:</span></td>
                </tr>
                <tr><td colspan="6"><input type="text" name="others1" class="inp" value="'.$others.'"></td>
                </tr>
                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">FINAL IMPRESSION:</span></td>
                </tr>
                <tr><td colspan="6"><input type="text" name="p_final_impression" class="inp" value="'.$p_final_impression.'"></td>
                </tr>
                <tr>
                <td colspan="6"><span style="font-weight: bold; font-size: 16px;">RECOMMENDATION:</span></td>
                </tr>
                <tr><td colspan="6"><input type="text" name="p_recommendation" class="inp" value="'.$p_recommendation.'"></td>
                </tr>
            </tbody>

    </table>
    </fieldset>"';
?>
<br>
<a href="echocardiogram_Paediatric_file.php?Payment_Item_Cache_List_ID=<?php echo $Payment_Item_Cache_List_ID ?>&patient_id=<?php echo $patient_id ?>" target="_blank" class="art-button-green">PRINT PATIENT FILE</a>
<br><br>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<?php
include("./includes/footer.php");
?>