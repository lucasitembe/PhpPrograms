<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    th{
        text-align:right;
    }
   
</style>
<?php
   session_start();
   include("./includes/connection.php");
   

    if (isset($_GET['Date_From'])) {
        $Date_From = $_GET['Date_From'];
    } else {
        $Date_From = '';
    }
    
    
    if (isset($_GET['Date_To'])) {
        $Date_To = $_GET['Date_To'];
    } else {
        $Date_To = '';
    }
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];


    $Registration_ID = $_POST['Registration_ID'];
    $Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
    // $Employee_ID = $_POST['Employee_ID'];
    
?>

<script>
$(document).ready(function() {
    $('#short').hide();
    $('#long').hide();

	$('#clinical_button').on('click', function() {
    alert("FOR ADULT ECHOCARDIOGRAPHY PATIENT");
        $('#short').hide();
        $('#long').hide();
        $('#clinical').show();
	});
    $('#short_view_button').on('click', function() {
    alert("FOR PAEDIATRIC ECHOCARDIOGRAPHY PATIENT");
        $('#long').hide();
        $('#clinical').hide();
        $('#short').show();
	});
});
</script>
<br><br>
<fieldset>
    <table class="table table-bordered" style="background: #FFFFFF">
        <caption><b>PATIENT DETAILS</b></caption>
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td style='text-align:right'><b>REGISTRATION No.</b></td>
            <td><b>WARD</b></td>
            <td><b>DOCTOR </b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            
        </tr>
        <?php 
            $Patient_Name ="";
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region ="";
            $District ="";
            $Ward ="";
            $village ="";
            $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name,Date_Of_Birth,Region,District,Ward,village,Gender FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_patient_information_result)>0){
                while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
                    $Patient_Name =$pat_details_rows['Patient_Name'];
                    $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
                    $Region =$pat_details_rows['Region'];
                    $District =$pat_details_rows['District'];
                    $Ward =$pat_details_rows['Ward'];
                    $village =$pat_details_rows['village'];
                    $Gender =$pat_details_rows['Gender'];
                }
            }
             //today function
            $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                //select doctor name
                $doctor_id=mysqli_query($conn,"SELECT consultant_ID, Payment_Date_And_Time FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID= '$Payment_Item_Cache_List_ID '") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_id)){
                    $doctor_id2 = $row['consultant_ID'];
                }

                $doctor_name1 = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID= '$doctor_id2'") or die(mysqli_error($conn));
                while($row = mysqli_fetch_array($doctor_name1)){
                    $doctor_name = $row['Employee_Name'];
                }
                
                //select admission ward 
                $Hospital_Ward_Name="";
                $sql_select_admission_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID=(SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status<>'Discharged')") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_admission_ward_result)>0){
                    $Hospital_Ward_Name=mysqli_fetch_assoc($sql_select_admission_ward_result)['Hospital_Ward_Name'];
                }
                echo "<tr>
                    <td>$Patient_Name</td>
                    <td style='text-align:right'>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>$doctor_name</td>
                    <td>$age</td>
                    <td>$Gender </td>
                  </tr>";
        ?>
    </table>
  
    <table class="table"id="top-table">
                <tbody>
                    <?php if($age <= 12 ){ ?>
                        <tr >
                            <th><input type="button" class="form-control " style="font-weight: bold;" id="short_view_button" value="PAEDIATRIC ECHOCARDIOGRAPHY REPORT"></th>
                        </tr>
                    <?php }else if(($age <= 18)  && ($age >= 12)){ ?>
                        <tr >
                            <th><input type="button" class="form-control " style="font-weight: bold;" id="clinical_button" value="ADULT ECHOCARDIOGRAPHY REPORT"></th>
                            <th><input type="button" class="form-control " style="font-weight: bold;" id="short_view_button" value="PAEDIATRIC ECHOCARDIOGRAPHY REPORT"></th>
                        </tr>
                    <?php } else if($age > 18 ){ ?>
                        <tr >
                            <th><input type="button" class="form-control " style="font-weight: bold;" id="clinical_button" value="ADULT ECHOCARDIOGRAPHY REPORT"></th>
                        </tr>
                    <?php } ?>
                   
                </tbody>
        </table>
    <!--div for adding clinical information -->
    <form action="" id="clinical"><button type="button" style="margin-top:-5%;" class="art-button-green pull-center" onclick="open_echorcadiorgram_records_adults(<?=$Registration_ID?> ,<?=$Payment_Item_Cache_List_ID?>)">PREVIEW</button>
        <table class="table table-bordered" >
        <script>
    alert("FOR ADULT ECHOCARDIOGRAPHY PATIENT");
        </script>
        <caption style="font-size: 16px;"><b>ADULT ECHOCARDIOGRAPHY REPORT</b></caption>
        
                <tbody >
                    <tr>
                        <th>BP:</th>
                        <th colspan='2'><input name="ABP" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>PR:</th>
                        <th colspan='2'><input name="APR" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>A. 2D/M-MODE PARAMETERS</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th>PARAMETER</th>
                        <th>NORMAL</th>
                        <th>RESULTS</th>
                        <th>PARAMETER</th>
                        <th>NORMAL RANGE</th>
                        <th>RESULTS</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Aortic Root</th>
                        <th>20 - 37mm</th>
                        <th><input name="aortic" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>IVS (d)</th>
                        <th>6 - 11mm</th>
                        <th><input name="aortic1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Left Atrium</th>
                        <th>19 - 40mm</th>
                        <th><input name="L_atrium" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>LVPW (d)</th>
                        <th>6 - 11mm</th>
                        <th><input name="L_atrium1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Right Atrium</th>
                        <th>14 - 26mm</th>
                        <th><input name="R_atrium" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>LVID (d)</th>
                        <th>33 - 55mm</th>
                        <th><input name="R_atrium1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>RVID (d)</th>
                        <th>19 - 38mm</th>
                        <th><input name="rvid" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>LVID (s)</th>
                        <th>24 - 42 mm</th>
                        <th><input name="rvid1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>Tapse</th>
                        <th>15 - 20mm</th>
                        <th><input name="Tapse" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>Fractional Shortening</th>
                        <th>29 - 37%</th>
                        <th><input name="Tapse1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>MV Area Planimetry</th>
                        <th>4 - 6sqr cm</th>
                        <th><input name="mv_area" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th>LV Ejection Fraction</th>
                        <th>55 - 70%</th>
                        <th><input name="mv_area1" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>

                    <tbody >
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>B. SIMPSON'S BIPLANE METHOD</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th colspan='2'>PARAMETER</th>
                        <th colspan='2'>NORMAL RANGE</th>
                        <th colspan='2'>RESULTS</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>LV end diastolic volume (ml)</th>
                        <th colspan='2'>83 - 107</th>
                        <th colspan='2'><input name="lv_diastolic" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>LV end systolic volume (ml)</th>
                        <th colspan='2'>20 - 37mm</th>
                        <th colspan='2'><input name="lv_systolic" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>LVEF</th>
                        <th colspan='2'>>55%</th>
                        <th colspan='2'><input name="lvef" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>

                    <tr>
                    <th><label for="">Chamber Sizes: </label></th><td> 
                    <span>
                        <input class="form_group" value="Normal" name="chamber" type="radio" id="adult_radio"> Normal 
                        <input value="Dilated" class="form_group" name="chamber" type="radio" id="adult_radio"> Dilated
                    </span></td>
                    <th><label for="">Regional Wall Motion Abnormality: </label></th><td colspan='3'> 
                    <span>
                        <input class="form_group" value="Normal" name="regional" type="radio" id="adult_radio"> Yes 
                        <input value="Dilated" class="form_group" name="regional" type="radio" id="adult_radio"> No
                    </span></td>
                    </tr>
                    <tr>
                    <th><label for="">MV Structure:</label></th><td> 
                    <span>
                        <input class="form_group" name="MV_structure" value="Normal" type="radio" id="adult_radio"> Normal <input class="form_group" name="MV_structure" value="Abnormal" type="radio" id="adult_radio">Abnormals</span></td> 
                    <th><label for="" style='color: red;'>*** If Abnormal:</label></th><td colspan='3'>
                        <input class="form_group" name="MV" value="Prolapsed" type="radio" id="adult_radio"> Prolapsed 
                        <input class="form_group" name="MV" value="Thickened"  type="radio" id="adult_radio"> Thickened
                        <input class="form_group" name="MV" value="Calcified" type="radio" id="adult_radio"> Calcified
                        <input class="form_group" name="MV" value="Domed" type="radio" id="adult_radio"> Domed
                        <input class="form_group" name="MV" value="Bicuspid" type="radio" id="adult_radio"> Bicuspid</span></td> 
                    </tr>
                    <tr>
                    <th><label for="">AV Structure:</label></th><td> 
                    <span>
                        <input class="form_group" name="AV_structure" value="Normal" type="radio" id="adult_radio"> Normal <input class="form_group" name="AV_structure" value="Abnormal" type="radio" id="adult_radio">Abnormals</span></td> 
                    <th><label for="" style='color: red;'>*** If Abnormal:</label></th><td colspan='3'>
                        <input class="form_group" name="AV" value="Prolapsed" type="radio" id="adult_radio"> Prolapsed 
                        <input class="form_group" name="AV" value="Thickened"  type="radio" id="adult_radio"> Thickened
                        <input class="form_group" name="AV" value="Calcified" type="radio" id="adult_radio"> Calcified
                        <input class="form_group" name="AV" value="Domed" type="radio" id="adult_radio"> Domed
                        <input class="form_group" name="AV" value="Bicuspid" type="radio" id="adult_radio"> Bicuspid</span></td> 
                    </tr>
                    <tr>
                    <th><label for="">PV Structure:</label></th><td> 
                    <span>
                        <input class="form_group" name="PV_structure" value="Normal" type="radio" id="adult_radio"> Normal 
                        <input class="form_group" name="PV_structure" value="Abnormal" type="radio" id="adult_radio">Abnormals</span></td> 
                    <th><label for="" style='color: red;'>*** If Abnormal:</label></th><td colspan='3'>
                        <input class="form_group" name="PV" value="Prolapsed" type="radio" id="adult_radio"> Prolapsed 
                        <input class="form_group" name="PV" value="Thickened"  type="radio" id="adult_radio"> Thickened
                        <input class="form_group" name="PV" value="Calcified" type="radio" id="adult_radio"> Calcified
                        <input class="form_group" name="PV" value="Domed" type="radio" id="adult_radio"> Domed
                        <input class="form_group" name="PV" value="Bicuspid" type="radio" id="adult_radio"> Bicuspid</span></td> 
                    </tr>
                    <tr>
                    <th><label for="">TV Structure:</label></th><td> 
                    <span>
                        <input class="form_group" name="TV_structure" value="Normal" type="radio" id="adult_radio"> Normal 
                        <input class="form_group" name="TV_structure" value="Abnormal" type="radio" id="adult_radio">Abnormals</span></td> 
                    <th><label for="" style='color: red;'>*** If Abnormal:</label></th><td colspan='3'>
                        <input class="form_group" name="TV" value="Prolapsed" type="radio" id="adult_radio"> Prolapsed 
                        <input class="form_group" name="TV" value="Thickened"  type="radio" id="adult_radio"> Thickened
                        <input class="form_group" name="TV" value="Calcified" type="radio" id="adult_radio"> Calcified
                        <input class="form_group" name="TV" value="Domed" type="radio" id="adult_radio"> Domed
                        <input class="form_group" name="TV" value="Bicuspid" type="radio" id="adult_radio"> Bicuspid</span></td> 
                    </tr>
                    <tr>
                    <th><label for="">IAS: </label></th><td> 
                    <span>
                        <input class="form_group" value="Intact" name="IAS" type="radio" id="adult_radio"> Intact 
                        <input value="ASD" class="form_group" name="IAS" type="radio" id="adult_radio"> ASD
                    </span></td>
                    <th><label for="">IVS: </label></th><th> 
                    <span>
                        <input class="form_group" value="Intact" name="IVS" type="radio" id="adult_radio"> Intact 
                        <input value="VSD" class="form_group" name="IVS" type="radio" id="adult_radio"> VSD
                    </span></th>
                    <th><label for="">AV/VA: </label></th><th> 
                    <span>
                        <input class="form_group" value="Concordant" name="MV_AV" type="radio" id="adult_radio"> Concordant 
                        <input value="Discordant" class="form_group" name="MV_AV" type="radio" id="adult_radio"> Discordant
                    </span></th>
                    </tr><tr>
                    <th><label for="">Thrombus: </label></th><th> 
                    <span>
                        <input class="form_group" value="Yes" name="Thrombus" type="radio" id="adult_radio"> Yes 
                        <input value="No" class="form_group" name="Thrombus" type="radio" id="adult_radio"> No
                    </span></th>
                    <th><label for="">Vegetation: </label></th><th> 
                    <span>
                        <input class="form_group" value="Yes" name="Vegetation" type="radio" id="adult_radio"> Yes 
                        <input value="No" class="form_group" name="Vegetation" type="radio" id="adult_radio"> No
                    </span></th>
                    <th><label for="">Effusion: </label></th><th> 
                    <span>
                        <input class="form_group" value="Yes" name="Effusion" type="radio" id="adult_radio"> Yes 
                        <input value="No" class="form_group" name="Effusion" type="radio" id="adult_radio"> No
                    </span></th>
                    </tr>
                    <tr>
                    <th><label for="">IVC: </label></th><th> 
                    <span>
                        <input class="form_group" value="Normal" name="IVC" type="radio" id="adult_radio"> Normal 
                        <input value="Abnormal" class="form_group" name="IVC" type="radio" id="adult_radio"> Abnormal
                    </span></th>
                    </tr>
                    <tr><th colspan='6'><hr></th>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>C. DOPPLER PARAMETERS</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th colspan='3'>MITRAL VALVE</th>
                        <th colspan='3'>AORTIC VALVE</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='1'>E/A:</th>
                        <th colspan='2'><input name="E_A" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                        <th colspan='1'>AV Vmax:</th>
                        <th colspan='2'><input name="AV_Vmax" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='1'>MR:</th>
                        <td colspan='2'>
                            <input class="form_group" name="MR" value="None" type="radio" id="adult_radio"> None 
                            <input class="form_group" name="MR" value="Trivial"  type="radio" id="adult_radio"> Trivial
                            <input class="form_group" name="MR" value="Mild" type="radio" id="adult_radio"> Mild
                            <input class="form_group" name="MR" value="Moderate" type="radio" id="adult_radio"> Moderate
                            <input class="form_group" name="MR" value="Severe" type="radio" id="adult_radio"> Severe</span>
                        </td> 
                        <th colspan='1'>AR:</th>
                        <td colspan='2'>
                            <input class="form_group" name="AR" value="None" type="radio" id="adult_radio"> None 
                            <input class="form_group" name="AR" value="Trivial"  type="radio" id="adult_radio"> Trivial
                            <input class="form_group" name="AR" value="Mild" type="radio" id="adult_radio"> Mild
                            <input class="form_group" name="AR" value="Moderate" type="radio" id="adult_radio"> Moderate
                            <input class="form_group" name="AR" value="Severe" type="radio" id="adult_radio"> Severe</span>
                        </td> 
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>MS:</th>
                        <td>
                            <input class="form_group" name="MS" value="Yes" type="radio" id="adult_radio"> Yes 
                            <input class="form_group" name="MS" value="No"  type="radio" id="adult_radio"> No
                        </td>
                        <td><input name="MS_Remarks" class="form_group" id="adult_inputs" type="text" class="inp" placeholder="Remarks"></td>
                        <th colspan="3"><input name="MR_Remarks1" class="form_group" id="adult_inputs" type="text" class="inp" placeholder="Remarks"></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th colspan='3'>TRICUSPID VALVE</th>
                        <th colspan='3'>PULMONARY VALVE</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                    <th colspan='1'>TR:</th>
                    <td colspan='2'>
                        <input class="form_group" name="TR" value="None" type="radio" id="adult_radio"> None 
                        <input class="form_group" name="TR" value="Trivial"  type="radio" id="adult_radio"> Trivial
                        <input class="form_group" name="TR" value="Mild" type="radio" id="adult_radio"> Mild
                        <input class="form_group" name="TR" value="Moderate" type="radio" id="adult_radio"> Moderate
                        <input class="form_group" name="TR" value="Severe" type="radio" id="adult_radio"> Severe</span>
                    </td> 
                    <th colspan='1'>Pulmonary Valve:</th>
                    <td colspan='2'>
                        <input class="form_group" name="Pulmonary_Valve" value="Normal" type="radio" id="adult_radio"> Normal 
                        <input class="form_group" name="Pulmonary_Valve" value="Abnormal"  type="radio" id="adult_radio"> Abnormal
                    </td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                    <th colspan='1'>TR Vmax/PG (mmHG):</th>
                    <th colspan='2'><input name="tr_Vmax" class="form_group" id="adult_inputs" type="text" class="inp" placeholder="TR Vmax/PG (mmHG)"></th>
                    <th colspan='1'>Remarks/Value:</th>
                    <th colspan='2'>
                        <input name="tr_Vmax_Remarks" class="form_group" id="adult_inputs" type="text" class="inp" placeholder="Remarks/Value"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                    <th colspan='1'>RVSP:</th>
                    <th colspan='2'><input name="RVSP" class="form_group" id="adult_inputs" type="text" class="inp" placeholder="RVSP"></th>
                        
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>D. TISSUE DOPPLER IMAGING</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th colspan='2'>PARAMETER</th>
                        <th colspan='2'>NORMAL RANGE</th>
                        <th colspan='2'>RESULTS</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>LVTDI Sa (Septal)</th>
                        <th colspan='2'>> 8 cm/s</th>
                        <th colspan='2'><input name="lvtdi_septal" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>LVTDI Sa (Leptal)</th>
                        <th colspan='2'>> 10 cm/s</th>
                        <th colspan='2'><input name="lvtdi_leptal" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>RVTDI Sa (Septal)</th>
                        <th colspan='2'>> 12 cm/s</th>
                        <th colspan='2'><input name="rvtdi_sa" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th colspan='2'>E/s ratio</th>
                        <th colspan='2'>< 14</th>
                        <th colspan='2'><input name="es_ratio" class="form_group" id="adult_inputs" type="text" class="inp" ></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>OTHERS DETAILS:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type='text' id="adult_inputs" class="form_group"  placeholder="Other Details" name="others" class="inp"></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>FINAL IMPRESSION:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type='text' id="adult_inputs" class="form_group"  placeholder="Final Impression" name="a_final_impression" class="inp"></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>RECOMMENDATION:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type='text' id="adult_inputs" class="form_group"  placeholder="Recommendations" name="a_recommendation" class="inp"></td>
                    </tr>

                    
                </tbody>
        </table>
        
        <input type="text" name="clinical_patient_id" hidden="hidden" value="<?php echo $Registration_ID;?>" >
        <input type="text" name="clinical_item_cache_list_id" hidden="hidden" value="<?php echo $Payment_Item_Cache_List_ID ?>" >
        <input type="button" id="clinical_btn" style="border-radius:0px" value="SAVE DATA" class="btn art-button pull-right">
    </form>


    <div class="table-responsive" style = "overflow-x: hidden;" id="short" style="text-align:right !important">
    <form action=""><button type="button" style="margin-top:-5%;" class="art-button-green pull-center" onclick="open_echorcadiorgram_records_paediatric(<?=$Registration_ID?>,<?=$Payment_Item_Cache_List_ID?>)">PREVIEW</button>
        <table class="table table-bordered" id="table">
        <caption style="font-size: 16px;"><b>PAEDIATRIC ECHOCARDIOGRAPHY REPORT</b></caption>
                <tbody>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">SPO2</label></th><td><input class="form_group" name="SPO2" placeholder="SPO2" type="text" class="inp"></td>
                    <th><label for="">PR</label></th><td><input class="form_group" name="PPR" placeholder="PR" type="text" class="inp"></td>
                    <th><label for="">WT</label></th><td> <span ><input class="form_group" placeholder="WT" name="WT" type="text" class="inp"></span></td>
                    </tr>
                    <tr>
                    <th><label for="">WARD</label></th>
                    <td> 
                        <span >
                            <input class="form_group" name="pulm_valve" placeholder="Not Admitted" type="text">
                        </span>
                    </td> 
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>PROFILE</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">Abdominal Situs:</label></th>
                    <td> <span ><input class="form_group" name="Abdominal_situs" value="Solitus" type="radio"> Solitus 
                    <input class="form_group" value="Ambiguous" name="Abdominal_situs" type="radio"> Ambiguous</span></td>
                    <th><label for="">Cardiac Position:</label></th>
                    <td> <span ><input class="form_group" name="Cardiac_position" value="Levocardia" type="radio"> Levocardia 
                    <input class="form_group" value="Mesocardia" name="Cardiac_position" type="radio"> Mesocardia
                    <input class="form_group" value="Dextrocardia" name="Cardiac_position" type="radio"> Dextrocardia</span></td>
                    </tr>
                    <tr>
                    <th colspan="2"><label for="">SYSTEMIC VENOUS DRAINAGE</label></th>
                    <th><label for="">SVC TO RA:</label></th>
                    <td><span ><input class="form_group" name="SVC_RA" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="SVC_RA" type="radio"> Abnormal
                    </span></td>
                    <th><label for="">IVC TO RA:</label></th>
                    <td><span ><input class="form_group" name="IVC_RA" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="IVC_RA" type="radio"> Abnormal
                    </span></td>
                    </tr>
                    <tr>
                    <th colspan="2"><label for="">PULMONARY VENOUS DRAINAGE</label></th>
                    <th><label for="">ALL 4 PV's TO LA:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="pulmonary_drainage" placeholder="PULMONARY VENOUS DRAINAGE - ALL 4 PV's TO LA " type="text" class="inp">
                    </span></td>
                    </tr>
                    <tr>
                    <th><label for="">Atrio Ventricular Connection</label></th>
                    <td> <span ><input class="form_group" value="Concordant" name="Atrio_ventricular" type="radio"> Concordant 
                    <input class="form_group" value="Discordant" name="Atrio_ventricular" type="radio"> Discordant</span></td>
                   <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="avc_Remarks" placeholder="Atrio Ventricular Connection" type="text" class="inp">
                    </span></td>
                    </tr>
                    <tr>
                    <th><label for="">Ventricular Artetial Connection</label></th>
                    <td> <span ><input class="form_group" value="Concordant" name="ventricular_arterial" type="radio"> Concordant 
                    <input class="form_group" value="Discordant" name="ventricular_arterial" type="radio"> Discordant</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="vac_Remarks" placeholder="Ventricular Artetial Connection" type="text" class="inp">
                    </span></td>
                    </tr>

                    <!-- END OF PROFILE -->
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>ATRIA</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">Left Atrium:</label></th>
                    <td> <span ><input class="form_group" name="Left_attrium" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Dilated" name="Left_attrium" type="radio"> Dilated</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="la_Remarks" placeholder="Left Atrium" type="text">
                    </span></td>
                    </tr>
                    <th><label for="">Right Atrium:</label></th>
                    <td> <span ><input class="form_group" name="Right_attrium" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Dilated" name="Right_attrium" type="radio"> Dilated</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="ra_Remarks" placeholder="Right Atrium" type="text">
                    </span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>


                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>ATRIOVENTRICULAR VALVES</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">Mitral Valve:</label></th>
                    <td> <span ><input class="form_group" name="Mitral_valve" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Dilated" name="Mitral_valve" type="radio"> Dilated</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="mv_Remarks" placeholder="Mitral Valve" type="text" class="inp">
                    </span></td>
                    </tr>
                    <th><label for="">Tricuspid Valve:</label></th>
                    <td> <span ><input class="form_group" name="tricuspid_valves" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Dilated" name="tricuspid_valves" type="radio"> Dilated</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="tv_Remarks" placeholder="Tricuspid Valve" type="text" class="inp">
                    </span></td>
                    </tr>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>


                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>SEMILUNAR VALVES</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">Aortic Valve:</label></th>
                    <td> <span ><input class="form_group" name="Aortic_valve" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="Aortic_valve" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="aortic_Remarks" placeholder="Aortic Valve" type="text" class="inp">
                    </span></td>
                    </tr>
                    <th><label for="">Pulmonary Valve:</label></th>
                    <td> <span ><input class="form_group" name="pulmonary_valves" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="pulmonary_valves" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="pulmonary_Remarks" placeholder="Pulmonary Valve" type="text" class="inp">
                    </span></td>
                    </tr>

                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>

                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>VENTRICLES</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">Left Ventricle:</label></th>
                    <td> <span ><input class="form_group" name="lv_ventricles" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="lv_ventricles" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="lv_Remarks" placeholder="Left Ventricle" type="text" class="inp">
                    </span></td>
                    </tr>
                    <th><label for="">Right Ventricles:</label></th>
                    <td> <span ><input class="form_group" name="rv_ventricles" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="rv_ventricles" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="rv_Remarks" placeholder="Right Ventricles" type="text" class="inp">
                    </span></td>
                    </tr>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>



                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>SEPTAE</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <th><label for="">IAS:</label></th>
                    <td> <span ><input class="form_group" name="IAS" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="IAS" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="IAS_Remarks" placeholder="IAS" type="text" class="inp">
                    </span></td>
                    </tr>
                    <th><label for="">IVS:</label></th>
                    <td> <span ><input class="form_group" name="IVS" value="Normal" type="radio"> Normal 
                    <input class="form_group" value="Abnormal" name="IVS" type="radio"> Abnormal</span></td>
                    <th><label for="">*** Remarks:</label></th>
                    <td colspan="3"><span ><input class="form_group" name="IVS_Remarks" placeholder="IVS" type="text" class="inp">
                    </span></td>
                    </tr>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <span >
                    <th><label for="">PDA:</label></th>
                    <td colspan="2"><span ><input class="form_group" name="PDA" placeholder="PDA" type="text" class="inp">
                    </span></td>
                    <th><label for="">AORTIC ARCH:</label></th>
                    <td colspan="2"><span ><input class="form_group" name="Aortic_Arch" placeholder="AORTIC ARCH" type="text" class="inp">
                    </span></td>
                    </tr>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'> 2D/M-MODE MEASUREMENTS</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff; font-size: 15px; text-align: center;'>
                        <th>PARAMETER</th>
                        <th colspan='2'>RESULTS</th>
                        <th>PARAMETER</th>
                        <th colspan='2'>RESULTS</th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>LA: AO</th>
                        <th  colspan='2'><input name="LA_AO" class="form_group" type="text" class="inp" ></th>
                        <th>LVPWD/S</th>
                        <th colspan='2'><input name="LVPWD" class="form_group" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>LVIDD/S (mm)</th>
                        <th colspan='2'><input name="LVIDD" class="form_group" type="text" class="inp" ></th>
                        <th>LVFS (%)</th>
                        <th  colspan='2'><input name="LVFS" class="form_group" type="text" class="inp" ></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>IVSD/S (mm)</th>
                        <th  colspan='2'><input name="IVSD_S" class="form_group" type="text" class="inp" ></th>
                        <th>LVEF (%)</th>
                        <th colspan='2'><input name="LVEF" class="form_group" type="text" class="inp" ></th>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>EFFUSION</span></td>
                    </tr>
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th>PLEURAL</th>
                        <th  colspan='2'><input name="pleural" class="form_group" type="text" class="inp" ></th>
                        <th>PERICARDIAL</th>
                        <th colspan='2'><input name="pericardial" class="form_group" type="text" class="inp" ></th>
                    </tr>                  
                    <tr><td colspan='6'><hr></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>OTHER DETAILS:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type="text" name='others1' placeholder='Other Details' class="inp"></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>FINAL IMPRESSION:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type="text" name='p_final_impression' class="inp"></td>
                    </tr>
                    <tr>
                    <td colspan='6'><span style='font-weight: bold; font-size: 16px;'>RECOMMENDATION:</span></td>
                    </tr>
                    <tr><td colspan='6'><input type="text" name='p_recommendation' class="inp"></td>
                    </tr>


                </tbody>
        </table>
        <input type="text" name="short_patient_id" hidden value="<?php echo $Registration_ID;?>" >
        <input type="text" name="short_item_cache_list_id" hidden value="<?php echo $Payment_Item_Cache_List_ID ?>" >
        <input type="button" id="short_view_btn" style="border-radius:0px" value="SAVE DATA" class="btn art-button pull-right">
    </form>
    </div>

<!--div for adding long axis view-->
    <div class="table-responsive" style = "overflow-x: hidden;" id="long">
    <form action="">
        <table class="table table-bordered" id="table">
        

    </fieldset>

    <div id="echorcadiorgram_records"></div>
    <div id="echorcadiorgram_records_paediatric"></div>
<script>
$(document).ready(function() {
	$('#clinical_btn').on('click', function() {


		var ABP = $("input[name = 'ABP']").val();
        var APR = $("input[name = 'APR']").val();
        var clinical_patient_id = $("input[name = 'clinical_patient_id']").val();
        var clinical_item_cache_list_id = $("input[name = 'clinical_item_cache_list_id']").val();
        var aortic = $("input[name = 'aortic']").val();
        var aortic1 = $("input[name = 'aortic1']").val();
        var L_atrium = $("input[name = 'L_atrium']").val();
        var L_atrium1 = $("input[name = 'L_atrium1']").val();
        var R_atrium = $("input[name = 'R_atrium']").val();
        var R_atrium1 = $("input[name = 'R_atrium1']").val();
        var rvid = $("input[name = 'rvid']").val();
        var rvid1 = $("input[name = 'rvid1']").val();
        var Tapse = $("input[name = 'Tapse']").val();
        var Tapse1 = $("input[name = 'Tapse1']").val();
        var mv_area = $("input[name = 'mv_area']").val();
        var mv_area1 = $("input[name = 'mv_area1']").val();
        var lv_systolic = $("input[name = 'lv_systolic']").val();
        var lvef = $("input[name = 'lvef']").val();
        var chamber = $("input[name = 'chamber']:checked").val();
        var regional = $("input[name = 'regional']:checked").val();
        var MV_AV = $("input[name = 'MV_AV']:checked").val();
        var MV_structure = $("input[name = 'MV_structure']:checked").val();
        var AV_structure = $("input[name = 'AV_structure']:checked").val();
        var Thrombus = $("input[name = 'Thrombus']:checked").val();
        var Vegetation = $("input[name = 'Vegetation']:checked").val();
        var Effusion = $("input[name = 'Effusion']:checked").val();
        var IVC = $("input[name = 'IVC']:checked").val();
        var E_A = $("input[name = 'E_A']").val();
        var AV_Vmax = $("input[name = 'AV_Vmax']").val();
        var AV = $("input[name = 'AV']:checked").val();
        var MR = $("input[name = 'MR']:checked").val();
        var AR = $("input[name = 'AR']:checked").val();
        var MS = $("input[name = 'MS']:checked").val();
        var MS_Remarks = $("input[name = 'MS_Remarks']").val();
        var MR_Remarks1 = $("input[name = 'MR_Remarks1']").val();
        var TR = $("input[name = 'TR']:checked").val();
        var Pulmonary_Valve = $("input[name = 'Pulmonary_Valve']:checked").val();
        var tr_Vmax = $("input[name = 'tr_Vmax']").val();
        var tr_Vmax_Remarks = $("input[name = 'tr_Vmax_Remarks']").val();
        var RVSP = $("input[name = 'RVSP']").val();
        var lvtdi_septal = $("input[name = 'lvtdi_septal']").val();
        var lvtdi_leptal = $("input[name = 'lvtdi_leptal']").val();
        var rvtdi_sa = $("input[name = 'rvtdi_sa']").val();
        var es_ratio = $("input[name = 'es_ratio']").val();
        var a_final_impression = $("input[name = 'a_final_impression']").val();
        var a_recommendation = $("input[name = 'a_recommendation']").val();
        var TV = $("input[name = 'TV']:checked").val();
        var TV_structure = $("input[name = 'TV_structure']:checked").val();
        var PV = $("input[name = 'PV']:checked").val();
        var PV_structure = $("input[name = 'PV_structure']:checked").val();
        var IAS = $("input[name = 'IAS']:checked").val();
        var lv_diastolic = $("input[name = 'lv_diastolic']").val();
        var MV = $("input[name = 'MV']:checked").val();
        var others = $("input[name = 'others']").val();
        var Employee_ID = '<?= $Employee_ID ?>';


		if(clinical_patient_id!="" && clinical_item_cache_list_id!=""){
            if(confirm("You are about to save this Echocardiography Form, Do you want to Proceed?")){
                $.ajax({
                    url: "save_clinical_informations.php",
                    type: "post",
                    data: {
                        ABP: ABP,
                        APR: APR,
                        clinical_patient_id: clinical_patient_id,
                        clinical_item_cache_list_id: clinical_item_cache_list_id,
                        aortic: aortic,
                        aortic1: aortic1,
                        L_atrium: L_atrium,
                        L_atrium1: L_atrium1,
                        R_atrium: R_atrium,
                        R_atrium1: R_atrium1,
                        rvid: rvid,
                        rvid1: rvid1,
                        Tapse: Tapse,
                        Tapse1: Tapse1,
                        mv_area: mv_area,
                        mv_area1: mv_area1,
                        lv_systolic: lv_systolic,
                        lvef: lvef,
                        chamber: chamber,
                        regional: regional,
                        MV_AV: MV_AV,
                        MV_structure: MV_structure,
                        AV_structure: AV_structure,
                        Thrombus: Thrombus,
                        Vegetation: Vegetation,
                        Effusion: Effusion,
                        IVC: IVC,
                        E_A: E_A,
                        AV_Vmax: AV_Vmax,
                        MR: MR,
                        AR: AR,
                        MS: MS,
                        AV: AV,
                        MS_Remarks: MS_Remarks,
                        MR_Remarks1: MR_Remarks1,
                        TR: TR,
                        Pulmonary_Valve: Pulmonary_Valve,
                        tr_Vmax: tr_Vmax,
                        tr_Vmax_Remarks: tr_Vmax_Remarks,
                        RVSP: RVSP,
                        lvtdi_septal: lvtdi_septal,
                        lvtdi_leptal: lvtdi_leptal,
                        rvtdi_sa: rvtdi_sa,
                        es_ratio: es_ratio,
                        a_final_impression: a_final_impression,
                        a_recommendation: a_recommendation,
                        lv_diastolic : lv_diastolic,
                        TV : TV,
                        TV_structure : TV_structure,
                        PV : PV,
                        PV_structure : PV_structure,
                        IAS : IAS,
                        MV : MV,
                        others : others,
                        Employee_ID:Employee_ID,
            

                    },
                    cache: false,
                    success: function(responce){
                        alert(responce)
                    }
                });
            }
		}
		else{
			alert('Patient or Procedure does not have an ID!');
		}
	});
});


$(document).ready(function() {
	$('#short_view_btn').on('click', function() {
        var short_patient_id = $("input[name = 'short_patient_id']").val();
        var short_item_cache_list_id = $("input[name = 'short_item_cache_list_id']").val();
		var SPO2 = $("input[name = 'SPO2']").val();
        var PPR = $("input[name = 'PPR']").val();
        var WT = $("input[name = 'WT']").val();
        var Abdominal_situs = $("input[name = 'Abdominal_situs']:checked").val();
        var SVC_RA = $("input[name = 'SVC_RA']:checked").val();
        var IVC_RA = $("input[name = 'IVC_RA']:checked").val();
        var pulmonary_drainage = $("input[name = 'pulmonary_drainage']").val();
        var Atrio_ventricular = $("input[name = 'Atrio_ventricular']:checked").val();
        var avc_Remarks = $("input[name = 'avc_Remarks']").val();
        var Ventricular_arterial = $("input[name = 'Ventricular_arterial']:checked").val();
        var Mitral_valve = $("input[name = 'Mitral_valve']:checked").val();
        var vac_Remarks = $("input[name = 'vac_Remarks']").val();
        var Left_attrium = $("input[name = 'Left_attrium']:checked").val();
        var la_Remarks = $("input[name = 'la_Remarks']").val();
        var tricuspid_valves = $("input[name = 'tricuspid_valves']:checked").val();
        var Right_attrium = $("input[name = 'Right_attrium']:checked").val();
        var ra_Remarks = $("input[name = 'ra_Remarks']").val();
        var mv_Remarks = $("input[name = 'mv_Remarks']").val();
        var tv_Remarks = $("input[name = 'tv_Remarks']").val();
        var Aortic_valve = $("input[name = 'Aortic_valve']:checked").val();
        var MV = $("input[name = 'MV']:checked").val();
        var aortic_Remarks = $("input[name = 'aortic_Remarks']").val();
        var pulmonary_valves = $("input[name = 'pulmonary_valves']:checked").val();
        var pulmonary_Remarks = $("input[name = 'pulmonary_Remarks']").val();
        var lv_ventricles = $("input[name = 'lv_ventricles']:checked").val();
        var lv_Remarks = $("input[name = 'lv_Remarks']").val();
        var rv_ventricles = $("input[name = 'rv_ventricles']:checked").val();
        var rv_Remarks = $("input[name = 'rv_Remarks']").val();
        var IAS = $("input[name = 'IAS']:checked").val();
        var IVS = $("input[name = 'IVS']:checked").val();
        var IVS_Remarks = $("input[name = 'IVS_Remarks']").val();
        var PDA = $("input[name = 'PDA']").val();
        var Aortic_Arch = $("input[name = 'Aortic_Arch']").val();
        var LA_AO = $("input[name = 'LA_AO']").val();
        var LVPWD = $("input[name = 'LVPWD']").val();
        var Cardiac_position = $("input[name = 'Cardiac_position']:checked").val();
        var p_recommendation = $("input[name = 'p_recommendation']").val();
        var p_final_impression = $("input[name = 'p_final_impression']").val();
        var others1 = $("input[name = 'others1']").val();
        
        
        if(short_patient_id!="" && short_item_cache_list_id!=""){         
            
            if(confirm("You are about to save this Echocardiography Form, Do you want to Proceed?")){ 
                $.ajax({
                    url: "save_short_view_axis.php",
                    type: "post",
                    data: {
                        short_patient_id: short_patient_id,
                        short_item_cache_list_id: short_item_cache_list_id,
                        SPO2: SPO2,
                        PPR: PPR,
                        WT: WT,
                        Abdominal_situs: Abdominal_situs,
                        SVC_RA: SVC_RA,
                        IVC_RA: IVC_RA,
                        pulmonary_drainage: pulmonary_drainage,
                        Atrio_ventricular: Atrio_ventricular,
                        avc_Remarks: avc_Remarks,
                        Ventricular_arterial: Ventricular_arterial,
                        Mitral_valve: Mitral_valve,
                        vac_Remarks: vac_Remarks,
                        Left_attrium: Left_attrium,
                        la_Remarks: la_Remarks,
                        tricuspid_valves: tricuspid_valves,
                        Right_attrium: Right_attrium,
                        ra_Remarks: ra_Remarks,
                        mv_Remarks: mv_Remarks,
                        tv_Remarks: tv_Remarks,
                        Aortic_valve: Aortic_valve,
                        aortic_Remarks: aortic_Remarks,
                        pulmonary_valves: pulmonary_valves,
                        pulmonary_Remarks: pulmonary_Remarks,
                        lv_ventricles: lv_ventricles,
                        lv_Remarks: lv_Remarks,
                        rv_ventricles: rv_ventricles,
                        rv_Remarks: rv_Remarks,
                        IAS: IAS,
                        IVS: IVS,
                        IVS_Remarks: IVS_Remarks,
                        PDA: PDA,
                        Aortic_Arch: Aortic_Arch,
                        LA_AO: LA_AO,
                        LVPWD: LVPWD,
                        Cardiac_position: Cardiac_position,
                        MV: MV,
                        p_final_impression: p_final_impression,
                        p_recommendation: p_recommendation,
                        others1: others1,
                    
                    },
                    cache: false,
                    success: function(responce){
                        alert(responce)

                    }
                });
            }
		}
		else{
			alert('Patient or Procedure does not have id !');
		}
	});
});
</script>