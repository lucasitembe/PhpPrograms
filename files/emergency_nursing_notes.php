<?php
   include("./includes/connection.php");
   include("./includes/header.php");
   

   session_start();
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


    $Registration_ID = $_GET['Registration_ID'];
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    // $Employee_ID = $_GET['Employee_ID'];
    $consultation_ID = $_GET['consultation_ID'];
	$current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	$current_Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    
    $Consent_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Consent_ID FROM tbl_consert_blood_forms_details WHERE  consultation_ID = '$consultation_ID' AND Registration_ID = '$Registration_ID'"))['Consent_ID'];
    
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    th{
        text-align:right;
    }
    #body{
        height: 670px !important;
        overflow-y: scroll;
        overflow-x: hidden;
        width: 49%;
        float: left;
        border-right: 2px dotted  #bfbfbf;

		}
    #data_input{
        height: 590px !important;
        overflow-y: scroll;
        overflow-x: hidden;
        width: 49%;
        float: right;
    }
    div {
  transition: all 0.5s cubic-bezier(.83,-0.43,.21,1.42);
}

.toggle-box-region {background-color:#fff; border:1px solid #d9d9d9; padding:16px 18px;}
#graph {background-color:#fff; border:1px solid #d9d9d9; padding:16px 18px;}
.toggle-box {display:none;}
.toggle-box + label {
	color:#555;
	cursor:pointer;
	display:block;
	font-weight:bold;
	line-height:23px;
    font-size: 21px;
    text-align: left;
	padding:.3em 0 .3em 26px;
	position:relative;
}
.toggle-box + label:hover {
	background: #f8f9f9;
}

.toggle-box + label + div {
    display:none; 
    margin:0 0 14px;
    font-size: 21px;
    text-align: left;
}
.toggle-box:checked + label:nth-child(n) + div {display:block;
    transition: width 2s linear 1s !important;
      font-size: 14px;
  transition-property: font-size;
  transition-duration: 4s;
  transition-delay: 2s;
}

.toggle-box + label:before {
	position:absolute;
	content:"\f0fe";
	font-family:FontAwesome;
	top:.3em;
	left:0px;
	color:#0085a6;
transition-delay: 500ms;

}
.toggle-box:checked + label {color:#0085a6;}
.toggle-box:checked + label:before {content:"\f146";}
.toggle-box-content {border-bottom:4px double #bfbfbf; color:#000; background:#ebf5fb ; width:100%; padding:10px 1em .6em 28px;
/* transition-property: margin-right;   */
/* transition-delay: 500ms; */

}

/* General */
*, *:before, *:after {
	-webkit-box-sizing: border-box;
	   -moz-box-sizing: border-box;
			box-sizing: border-box;
    /* transition-delay: 500ms; */

}

.box-test {
  padding:3em;
    /* transition-delay: 500ms; */
}
      #graph{
        height: 250px;
        margin:15px auto;
        width: 48vw;
        margin-bottom: 3px;
      }
      .input-to-graph{
        display: inline-block;

        
      }
.input_style{
    margin: 4px !important;
}
.ecg_table{
    display:none;
}
</style>
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
<center>
<fieldset>
<legend align=center><b>EMD NURSING REPORT</b></legend>
    <table  width="100%" class="table" style="background: #FFFFFF;">
        <caption><b>PATIENT DETAILS</b></caption>
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td><b>REGISTRATION No.</b></td>
            <td><b>WARD</b></td>
            <td><b>ATTENDING EMPLOYEE</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>ADDRESS</b></td>
            
        </tr>
        <?php 
            $Patient_Name ="";
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region ="";
            $District ="";
            $Ward ="";
            $village ="";


            $select_Patient = mysqli_query($conn,"SELECT
            patient_type,
            Old_Registration_Number,Title,Patient_Name,
                Date_Of_Birth,Patient_Picture,
                    Gender,Religion_Name,Denomination_Name,
    pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
                        Member_Number,Member_Card_Expire_Date,
                            pr.Phone_Number,Email_Address,Occupation,
                                Employee_Vote_Number,Emergence_Contact_Name,
                                    Emergence_Contact_Number,Company,Registration_ID,
                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                        Registration_ID,sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id,
                                        village
                      from tbl_patient_registration pr LEFT JOIN tbl_denominations de ON de.Denomination_ID=pr.Denomination_ID LEFT JOIN tbl_religions re  ON re.Religion_ID=de.Religion_ID, 
                      tbl_sponsor sp 
                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_Patient);

                if ($no > 0) {
                while ($row = mysqli_fetch_array($select_Patient)) { //

                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $village = $row['village'];
                $Country = $row['Country'];
                $Region = $row['Region'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Tribe = $row['Tribe'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Exemption = strtolower($row['Exemption']); //
                $Diseased = strtolower($row['Diseased']);
                $national_id = $row['national_id'];
                $Patient_Picture = $row['Patient_Picture'];
                $Religion_Name = $row['Religion_Name'];
                $Denomination_Name = $row['Denomination_Name'];
                // echo $Ward."  ".$District."  ".$Ward; exit;
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
                $sql_select_admission_ward_result=mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID=(SELECT Hospital_Ward_ID FROM tbl_admission WHERE Registration_ID='$Registration_ID' AND Admission_Status<>'Discharged' ORDER BY Admision_ID DESC LIMIT 1)") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_admission_ward_result)>0){
                    $Hospital_Ward_Name=mysqli_fetch_assoc($sql_select_admission_ward_result)['Hospital_Ward_Name'];
                }else{
                    $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
                }
                echo "<tr>
                    <td>".ucwords($Patient_Name)."</td>
                    <td>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>".ucwords($current_Employee_Name)."</td>
                    <td>$age</td>
                    <td>$Gender </td>
                    <td>$Guarantor_Name </td>
                    <td>".$Region."/".$District."</td>
                  </tr>";

                  $Select_Previous = mysqli_query($conn, "SELECT EMD_nursing_ID, Main_Complain, History_of_Present_Illness, Documented_At, em.Employee_Name FROM tbl_employee em, tbl_emd_nursing_care emc WHERE consultation_ID = '$consultation_ID' AND em.Employee_ID = emc.Employee_ID");
                  $num = mysqli_num_rows($Select_Previous);
                  if($num > 0){
                      while($details = mysqli_fetch_assoc($Select_Previous)){
                          $Main_Complain = $details['Main_Complain'];
                          $History_of_Present_Illness = $details['History_of_Present_Illness'];
                          $Main_Complain_nurse = $details['Employee_Name'];
                          $Documented_At = $details['Documented_At'];
                          $EMD_nursing_ID = $details['EMD_nursing_ID'];
                          
                          $select_notes = mysqli_query($conn, "SELECT Airway, Breathing, Circulation, Deformity, Exposure, Nursing_Interverntion, General_Remarks, Main_Complain, Saved_Date_Time, em.Employee_Name FROM tbl_employee em, tbl_emd_nursing_notes en WHERE en.EMD_nursing_ID = '$EMD_nursing_ID' AND em.Employee_ID = en.Employee_ID_Updated");
                          while($rows = mysqli_fetch_assoc($select_notes)){
                              $Airway = $rows['Airway'];
                              $Breathing = $rows['Breathing'];
                              $Circulation = $rows['Circulation'];
                              $Deformity = $rows['Deformity'];
                              $Exposure = $rows['Exposure'];
                              $Nursing_Interverntion = $rows['Nursing_Interverntion'];
                              $General_Remarks = $rows['General_Remarks'];
                              $Main_Complain_Nurse = $rows['Main_Complain'];
                              $Saved_Date_Time = $rows['Saved_Date_Time'];
                              $Employee_Saved = $rows['Employee_Name'];

                              $Intervation = $Nursing_Interverntion.';<br><br>';
                              $Intervation .="Documented By: <b>".$Employee_Saved."</b> &nbsp;&nbsp;&nbsp;&nbsp; Documented Time: <b>".$Saved_Date_Time.'</b><br>
                              -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';

                              $Complain = $Main_Complain_Nurse.';<br><br>';
                              $Complain .="Documented By: <b>".$Employee_Saved."</b> &nbsp;&nbsp;&nbsp;&nbsp; Documented Time: <b>".$Saved_Date_Time.'</b><br>
                              -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';

                              $Remarks = $General_Remarks.';<br><br>';
                              $Remarks .="Documented By: <b>".$Employee_Saved."</b> &nbsp;&nbsp;&nbsp;&nbsp; Documented Time: <b>".$Saved_Date_Time.'</b><br>
                              -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';

                              $Primary = '<b>Airway: </b>'.$Airway.'; <b>Breathing :</b>'.$Breathing.'; <b>Circulation: </b>.'.$Circulation.'; <b>Deformity: </b>'.$Deformity.'; <b>Exposure: </b>'.$Exposure.'<br><br>';
                              $Primary .="Documented By: <b>".$Employee_Saved."</b> &nbsp;&nbsp;&nbsp;&nbsp; Documented Time: <b>".$Saved_Date_Time.'</b><br>
                              -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';

                      $Primary_Survey .= $Primary;
                      $Full_intervation .= $Intervation;
                      $Diagnosis .= $Complain;
                      $Generalized_Remarks .= $Remarks;

                          }

                      }
                  }else{
                      $Primary_Survey = "<h5>NO PRIMARY SURVEY RECORDED</h5>";
                      $Full_intervation = "<h5>NO NURSING INTERVENTION  RECORDED</h5>";
                      $Diagnosis = "<h5>NO NURSING DIAGNOSIS RECORDED</h5>";
                      $Generalized_Remarks = "<h5>NO NURSING REMARKS RECORDED</h5>";
                      $Main_Complain = "<h5>NO COMPLAIN RECORDED</h5>";
                      $History_of_Present_Illness = "<h5>NO HISTORY RECORDED</h5>";
                  }

                  $Select_episode_of_Care = mysqli_query($conn, "SELECT Employee_Name AS User_Episode_of_Care, mdrt, ecg_comments, ecg, rbg, pH, pCO2, pO2, Hct, S02, Hb, Na, K, iCA, Cl, Li, nCa, GLU, LAC, HCO3, TCO2, SBC, O2Ct, p02, BE, BE_B, BE_ECF, AG_NA, AG_K, Episode_Date_Time  FROM tbl_episode_of_care ec, tbl_employee em WHERE em.Employee_ID = ec.Employee_ID AND consultation_ID = '$consultation_ID' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                  $episodes = mysqli_num_rows($Select_episode_of_Care);
                    $view_episode ='';
                  if($episodes > 0){
                      While($eps = mysqli_fetch_assoc($Select_episode_of_Care)){
                        $mdrt= $eps['mdrt'];
                        $ecg_comments= $eps['ecg_comments'];
                        $ecg= $eps['ecg'];
                        $rbg= $eps['rbg'];
                        $pH= $eps['pH'];
                        $pCO2= $eps['pCO2'];
                        $pO2= $eps['pO2'];
                        $Hct= $eps['Hct'];
                        $S02= $eps['S02'];
                        $Hb= $eps['Hb'];
                        $Na= $eps['Na'];
                        $K= $eps['K'];
                        $iCA= $eps['iCA'];
                        $Cl= $eps['Cl'];
                        $Li= $eps['Li'];
                        $nCa= $eps['nCa'];
                        $GLU= $eps['GLU'];
                        $LAC= $eps['LAC'];
                        $HCO3= $eps['HCO3'];
                        $TCO2= $eps['TCO2'];
                        $SBC= $eps['SBC'];
                        $O2Ct= $eps['O2Ct'];
                        $p02= $eps['p02'];
                        $BE= $eps['BE'];
                        $BE_B= $eps['BE_B'];
                        $BE_ECF= $eps['BE_ECF'];
                        $AG_NA= $eps['AG_NA'];
                        $AG_K= $eps['AG_K'];
                        $User_Episode_of_Care = $eps['User_Episode_of_Care'];
                        $Episode_Date_Time = $eps['Episode_Date_Time'];
                        $view_episode .="
                        <table style='border: no-border; width: 100%;' id='data'>             
                        ";

                        $User_Episode_of_Care= $eps['User_Episode_of_Care'];
                        $view_episode .= "<tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>ECG: </b></td><td>".$ecg."</td><td style='text-align:right; width='15%;'><b>MDRT: </b></td><td>".$mdrt."</td><td style='text-align:right; width='15%;'><b>RBG: </b></td><td>".$rbg."</td><tr>";
                        if(!empty($ecg_comments)){
                            $view_episode .= "<td style='text-align:right; width='15%;'><b>Comments</td><td colspan='5'>".$ecg_comments."</td></tr>";
                        }
                        $view_episode .="<tr style='background: #dedede;'><th colspan='6' style='text-align:center;font-size:14px; color: #0079AE;'>ABG RESULTS</th></tr>";
                        $view_episode .="<tr><th colspan='6' style='text-align: left;'>BLOOD GAS</th></tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>pH: </b></td><td>".$pH."</td><td style='text-align:right; width='15%;'><b>pCO2: </b></td><td>".$pCO2."</td><td style='text-align:right; width='15%;'><b>pO2: </b></td><td>".$pO2."</td><tr>";
                        $view_episode .="<tr><th colspan='6' style='text-align: left;'>HEMATOCRIT</th></tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>Hct: </b></td><td>".$Hct."</td><td style='text-align:right; width='15%;'><b>SO2: </b></td><td>".$S02."</td><td style='text-align:right; width='15%;'><b>Hb: </b></td><td>".$Hb."</td><tr>";
                        $view_episode .="<tr><th colspan='6' style='text-align: left;'>ELECTROLYTES</th></tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>Na: </b></td><td>".$Na."</td><td style='text-align:right; width='15%;'><b>K: </b></td><td>".$K."</td><td style='text-align:right; width='15%;'><b>iCA: </b></td><td>".$iCA."</td><tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>Cl: </b></td><td>".$Cl."</td><td style='text-align:right; width='15%;' class='hide'><b>Li: </b></td><td class='hide'>".$Li."</td><td style='text-align:right; width='15%;'><b>nCa: </b></td><td>".$nCa."</td><tr>";
                        $view_episode .="<tr><th colspan='6' style='text-align: left;'>METABOLITES</th></tr>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>GLU: </b></td><td>".$GLU."</td><td style='text-align:right; width='15%;'><b>LAC: </b></td><td>".$LAC."</td><td style='text-align:right; width='15%;'><b>HCO3: </b></td><td>".$HCO3."</td><tr>";
                        $view_episode .="<tr class='hide'><th colspan='6' style='text-align: left;'>CALCULATED PARAMETERS</th></tr>";
                        $view_episode .= "<tr><td style='text-align:right; width='15%;'><b>BE: </b></td><td>".$BE."</td><td style='text-align:right; width='15%;'><b>TCO2: </b></td><td>".$TCO2."</td><td style='text-align:right; width='15%;'><b>Ag: </b></td><td>".$AG_NA."</td><td style='text-align:right; width='15%;' class='hide'><b>SBC: </b></td><td class='hide'>".$SBC."</td><tr>";
                        $view_episode .= "<tr class='hide'><td style='text-align:right; width='15%;'><b>O2Ct: </b></td><td>".$O2Ct."</td><td style='text-align:right; width='15%;'><b>pO2%: </b></td><td>".$p02."</td><tr class='hide'>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>BE-B: </b></td><td>".$BE_B."</td><td style='text-align:right; width='15%;'><b>BE-ECF: </b></td><td>".$BE_ECF."</td><tr class='hide'>";
                        $view_episode .="<tr class='hide'><th colspan='6' style='text-align: left;'>ANION GAP</th></tr><tr class='hide'>";
                        $view_episode .= "<td style='text-align:right; width='15%;'><b>Ag: </b></td><td>".$AG_NA."</td><td style='text-align:right; width='15%; display: none;' class='hide'><b>AG-K: </b></td><td class='hide'>".$AG_K."</td></tr>";
                        $view_episode .= "</table>";
                        $view_episode .="</b><br></b><br> Documented By: <b>".$User_Episode_of_Care."</b> &nbsp;&nbsp;&nbsp;&nbsp; Documented Time: <b>".$Episode_Date_Time.'</b><br>
                        <hr><br>';

                      }
                      $View_all_episodes .= $view_episode;
                  }else{
                    $View_all_episodes = "<h5>NO POINT OF CARE RECORDED</h5>";

                  }

                  $limit = "disabled='disabled'";
        ?>
    </table>
  
    <!--div for adding clinical information -->
    <!-- <div id='body'> -->


    <!-- LEFT COLUMN -->

    <div id='body'>
    <form action="" id="clinical">
        <div id=''>
        <table class="table" >
            <?php 
            if($num == 0){
                echo '<tr>
                <th style="text-align: right; width: 20%;">History of Present Illness</th>
                <td colspan="2">
                    <textarea name="History_of_Present_Illness" id="History_of_Present_Illness" style="padding-left:5px;" cols="30"></textarea>
                </td>
            </tr>
            <tr>
            <th style="text-align: right; width: 15%;">Main Complain</th>
            <td colspan="2">
                <textarea name="Main_Complain" id="Main_Complain" style="padding-left:5px;" cols="30"></textarea>
            </td>
            </tr>';
            }else{
                echo "<h2>EMD NURSING NOTES</h2>";
            }
            ?>

                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right; width: 20%;' rowspan='5'>Primary Survey</th>
                        <th style='text-align: right;' colspan='2'><input name="Airway" class="form_group" id="Airway"  placeholder='Airway' title='Airway' type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;' colspan='2'><input name="Breathing" class="form_group" id="Breathing"  placeholder='Breathing' title="Breathing" type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;' colspan='2'><input name="Circulation" class="form_group" id="Circulation"  placeholder='Circulation' title="Circulation" type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;' colspan='2'><input name="Deformity" class="form_group" id="Deformity"  placeholder='Deformity' title="Deformity" type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <th style='text-align: right;' colspan='2'><input name="Exposure" class="form_group" id="Exposure"  placeholder='Exposure' title="Exposure" type="text" class="inp" value="<?php echo $Employee_collected_name; ?>"></th>
                    </tr>
                    <tr tyle='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Nursing Diagnosis</th>
                        <th style='text-align: right;' colspan='2'>
                            <textarea style="resize:none;padding-left:5px;" required="required" id="Nursing_Diagnosis"  name="Nursing_Diagnosis"><?php echo $relevant_clinical_data; ?></textarea>
                        </th>
                    </tr>
                    <tr tyle='border: 2px solid #fff;'>
                        <th style='text-align: right;'>Nursing Intervention & Management</th>
                        <th style='text-align: right;' colspan='2'>
                            <textarea style="padding-left:5px;" required="required" id="Nursing_Interverntion"  name="Nursing_Interverntion"><?php echo $relevant_clinical_data; ?></textarea>
                        </th>
                    </tr>
                    <tr tyle='border: 2px solid #fff;'>
                        <th style='text-align: right;'>General Nursing Remarks</th>
                        <th style='text-align: right;' colspan='2'>
                            <textarea style="padding-left:5px;" required="required" id="General_Remarks"  name="General_Remarks"><?php echo $relevant_clinical_data; ?></textarea>
                        </th>
                    </tr>
                    <tr>
                        <td colspan='2'>
                        <input type="button" id="clinical_btn" style="border-radius:5px; padding: 5px; font-weight: bold; background: #d40b72;" value="POINT OF CARE" class="btn art-button pull-right" onclick="add_point_care()">
                        <input type="button" id="clinical_btn" style="border-radius:5px; padding: 5px; font-weight: bold; background: #d40b72;" value="ADD VITAL SIGNS" class="btn art-button pull-right" onclick="add_nurse_vitals()">
                    <?php
                    if($num > 0){
                        echo '<input type="button" id="clinical_btn" style="border-radius:5px; padding: 5px; font-weight: bold;" value="ADD NURSING NOTES" class="btn art-button pull-right" onclick="add_new_notes()">';
                    }else{
                        echo '<input type="button" id="clinical_btn" style="border-radius:5px; padding: 5px; font-weight: bold;" value="SAVE NOTES" class="btn art-button pull-right" onclick="Save_Nursing_Notes()">';
                    }
                    ?>
                </tr>
                </tbody>
        </table>
        </div>
        </form>
        </div>
        <!-- RIGHT COLUMN -->
        <h1>EMD NURSING NOTES PROGRESS REPORT</h1>
        <div style="float: center;">
            <span style='font-weight: bold; font-size: 11px;'>COLOR CODE KEY: <span>
            <input type="button"  style='background:  #950111; color: white; border: none; font-weight: bold;' value='Systolic Blood Pressure' name="" id="">
            <input type="button"  style='background:  #406bdc; color: white; border: none; font-weight: bold;' value='Diastolic Blood Pressure' name="" id="">
            <input type="button"  style='background: #b4f4ab; border: none; font-weight: bold;' value='Temperature' name="" id="">
        </div>
        <div id='data_input' style="overflow-y: scroll !important; overflow-x: hidden; width:50%">
    <form action="" id="clinical">
        <div id=''>

                <div id="graph">
                    <div id="chartContainer" style="height: 230px; width: 100%;"></div>        
                </div>
                <div class="toggle-box-region">       
                    <input class="toggle-box" id="toggleId-1" type="checkbox">
                        <label for="toggleId-1">History of Present Illness</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $History_of_Present_Illness; ?></div>

                    <input class="toggle-box" id="toggleId-2" type="checkbox">
                        <label for="toggleId-2">Main Complain</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $Main_Complain; ?></div>

                    <input class="toggle-box" id="toggleId-3" type="checkbox">
                        <label for="toggleId-3">Primary Survey</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?PHP echo $Primary_Survey; ?></div>
                    <input class="toggle-box" id="toggleId-4" type="checkbox">
                        <label for="toggleId-4">Nursing Diagnosis</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $Diagnosis ?></div>
                    <input class="toggle-box" id="toggleId-5" type="checkbox">
                        <label for="toggleId-5">Nursing Intervention & Management</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $Full_intervation; ?></div>
                    <input class="toggle-box" id="toggleId-6" type="checkbox">
                        <label for="toggleId-6">General Nursing Remarks</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $Generalized_Remarks ?></div>
                    <input class="toggle-box" id="toggleId-7" type="checkbox">
                        <label for="toggleId-7">Point of Care</label>
                            <div class="toggle-box-content" style=" font-size: 12px; text-align: justify !important;"><?php echo $View_all_episodes ?></div>
                </div>
        </div>
    </form>
    </div>
</fieldset>
<!-- VITALS SIGN DIALOG BEGINS HERE -->
<div id="add_vitals_nurse">
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i>Please Fill numbers/values only during Vital Sign Recording</i></p></center>
    <table width="100%" >
    <tr>
            <td style="text-align:right;font-size:14px" width="13%;"><b>BP (Systolic)</b></td>
            <td>
                <input type="text" name="Blood_Pressure_Systolic" id="Blood_Pressure_Systolic" placeholder="Systolic blood pressure" title="Systolic blood pressure" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="13%;"><b>BP (Diastolic)</b></td>
            <td>
                <input type="text" name="Blood_Pressure_Diastolic" id="Blood_Pressure_Diastolic" placeholder="Diastolic blood pressure" title="Diastolic blood pressure" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="13%"><b>Pulse  (bpm)</b></td>
            <td>
                <input type="text" name="Pulse_Blood" id="Pulse_Blood" placeholder="Pulse Blood" class="input_style"  />
            </td> 
        </tr>
        
        <tr>
            <td style="text-align:right;font-size:14px" width="13%"><b>Temp(centigrade)</b></td>
            <td>
                <input type="text" name="Temperature" placeholder="Temperature" id="Temperature" title="Temperature" class="input_style" />
            </td>
            <td style="text-align:right;font-size:14px" width="13%" required="required"><b>RESP(bpm)</b></td>
            <td  ><input type="text" name="Resp_Bpressure" id="Resp_Bpressure" placeholder="Respiration" class="input_style"/></td>
            <td style="text-align:right;font-size:14px" width="13%" required="required"><b>Drainage (cc)</b></td>
            <td  ><input type="text" name="Drainage" id="Drainage" placeholder="Drainage" class="input_style"  /></td>
        </tr>
        <tr>
            <td style="text-align:right;font-size:14px " width="13%" ><b>Body Weight</b></td>
            <td ><input type="text" name="body_weight" placeholder="Body Weight" id="body_weight" class="input_style"  /></td>
            <td style="text-align:right;font-size:14px " width="13%" ><b>Oxygen saturation</b></td>
            <td><input type="text" name="oxygen_saturation" id="oxygen_saturation" placeholder="Oxygen Saturation" class="input_style"  /></td>
        </tr>
            
        </tr>
        <tr>
            <td style='text-align: center;' colspan="6">
                <br/>
                <input type='hidden' name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                <input type='hidden' name="consultation_ID" value='<?php echo $_GET['consultation_ID']; ?>'/>
                <input type='submit' name='submitRadilogyform' id='submit' value='SAVE VITALS' onclick=" save_vitals()" class='art-button pull-right' style="width:10%; border-radius: 5px; padding: 5px; font-weight: bold;">
                <input type='hidden' name='submitRadilogyform' value='true'>
            </td>
        </tr>
    </table>
</div>
<div id="add_point_care">
    <table width="100%" >
        <div class="ecg_table">
            <center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i>This Patient has Abnormal ECG, Please Specify below</i></p></center>
             <textarea name="ecg_comments" id="ecg_comments" cols="30" rows="3"  style="padding: 10px; text-align: justify;"></textarea>
        </div>
    <tr>
        
            <td style="text-align:right;font-size:14px" width="7%;"><b>ECG: </b></td>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ecg" value="Normal" id="Normal">&nbsp;&nbsp;<b>Normal</b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ecg" value="Abnormal" id="Abnormal">&nbsp;&nbsp;<b>Abnormal</b>
            </td>
            <td style="text-align:right;font-size:14px" width="7%;"><b>MDRT</b></td>
            <td>
                <input type="text" name="mdrt" id="mdrt" placeholder="MDRT" title="MDRT" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>RBG</b></td>
            <td>
                <input type="text" name="rbg" id="rbg" placeholder="RBG" class="input_style"  />
            </td> 
        </tr>
        <tr>
            <td colspan="6"><hr></td>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
               <h4>ABG RESULTS</h4>
            </th>
        </tr>
        <tr style="background:  #f2f4f4;">
            <td colspan="6">BLOOD GAS</td>
        </tr>
        <tr>
        <td style="text-align:right;font-size:14px" width="7%;"><b>pH</b></td>
            <td>
                <input type="text" name="pH" id="pH" placeholder="pH" title="pH" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>pCO2</b></td>
            <td>
                <input type="text" name="pCO2" id="pCO2" placeholder="pCO2" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>pO2</b></td>
            <td>
                <input type="text" name="pO2" id="pO2" placeholder="pO2" class="input_style"  />
            </td>
        </tr>
        <tr style="background:  #f2f4f4;">
            <td colspan="6">HEMATOCRIT</td>
        </tr>
        <tr>
        <td style="text-align:right;font-size:14px" width="7%;"><b>Hct</b></td>
            <td>
                <input type="text" name="Hct" id="Hct" placeholder="Hct" title="Hct" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>S02</b></td>
            <td>
                <input type="text" name="S02" id="S02" placeholder="S02" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>Hb</b></td>
            <td>
                <input type="text" name="Hb" id="Hb" placeholder="Hb" class="input_style"  />
            </td>
        </tr>
        <tr style="background:  #f2f4f4;">
            <td colspan="6">ELECTROLYTES</td>
        </tr>
        <tr>
        <td style="text-align:right;font-size:14px" width="7%;"><b>Na</b></td>
            <td>
                <input type="text" name="Na" id="Na" placeholder="Na" title="MDRT" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>K</b></td>
            <td>
                <input type="text" name="K" id="K" placeholder="K" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>iCA</b></td>
            <td>
                <input type="text" name="iCA" id="iCA" placeholder="iCA" class="input_style"  />
            </td>
        </tr>
        <tr>
        <td style="text-align:right;font-size:14px" width="7%;"><b>Cl</b></td>
            <td>
                <input type="text" name="Cl" id="Cl" placeholder="Cl" title="MDRT" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px; display: none;" width="7%"><b>Li</b></td>
            <td class='hide'>
                <input type="text" name="Li" id="Li" placeholder="Li" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>nCa</b></td>
            <td>
                <input type="text" name="nCa" id="nCa" placeholder="nCa" class="input_style"  />
            </td>
        </tr>
        <tr style="background:  #f2f4f4;">
            <td colspan="6">METABOLITES</td>
        </tr>
        <tr>
        <td style="text-align:right;font-size:14px" width="7%;"><b>GLU</b></td>
            <td>
                <input type="text" name="GLU" id="GLU" placeholder="GLU" title="MDRT" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%"><b>LAC</b></td>
            <td>
                <input type="text" name="LAC" id="LAC" placeholder="LAC" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%;"><b>HCO3</b></td>
            <td>
                <input type="text" name="HCO3" id="HCO3" placeholder="HCO3" title="HCO3" class="input_style" sytle="width: 50%;" />
            </td>
        </tr>
        <tr style="background:  #f2f4f4; display: none;">
            <td colspan="6">CALCULATED PARAMETERS</td>
        </tr>
        <tr>

            <td style="text-align:right;font-size:14px" width="7%"><b>TCO2</b></td>
            <td>
                <input type="text" name="TCO2" id="TCO2" placeholder="TCO2" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%" class='hide'><b>SBC</b></td>
            <td class="hide">
                <input type="text" name="SBC" id="SBC" placeholder="SBC" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%;"><b>Bef</b></td>
            <td>
                <input type="text" name="ef" id="BE" placeholder="Bef" title="Bef" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%;"><b>Ag</b></td>
            <td>
                <input type="text" name="AG_NA" id="AG_NA" placeholder="Ag" title="Ag" class="input_style" sytle="width: 50%;" />
            </td>
        </tr>
        <tr>
           <td style="text-align:right;font-size:14px" width="7%;" class="hide"><b>O2Ct</b></td>
            <td class="hide">
                <input type="text" name="O2Ct" id="O2Ct" placeholder="O2Ct" title="O2Ct" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%" class="hide"><b>pO2%</b></td>
            <td class="hide">
                <input type="text" name="p02" id="p02" placeholder="pO2%" class="input_style"  />
            </td>
        </tr>
        <tr>
           <td style="text-align:right;font-size:14px" width="7%;" class="hide"><b>Bef</b></td>
            <td class="hide">
                <input type="text" name="ef" id="BE" placeholder="Bef" title="Bef" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px" width="7%" class="hide"><b>BE-B</b></td>
            <td class="hide">
                <input type="text" name="BE_B" id="BE_B" placeholder="BE-B" class="input_style"  />
            </td>
            <td style="text-align:right;font-size:14px" width="7%" class="hide"><b>BE-ECF</b></td>
            <td class="hide">
                <input type="text" name="BE_ECF" id="BE_ECF" placeholder="BE-ECF" class="input_style"  />
            </td>
        </tr>
        <tr style="background:  #f2f4f4; display: none;">
            <td colspan="6">ANION GAP</td>
        </tr>
        <tr>
           <td style="text-align:right;font-size:14px" width="7%;" class='hide'><b>Ag</b></td>
            <td class='hide'>
                <input type="text" name="AG_NA" id="AG_NA" placeholder="Ag" title="Ag" class="input_style" sytle="width: 50%;" />
            </td>
            <td style="text-align:right;font-size:14px; display:none;" width="7%"><b>AG-K</b></td>
            <td class='hide'>
                <input type="text" name="AG_K" id="AG_K" placeholder="AG-K" class="input_style"  />
            </td>
        </tr>
        
        <tr>
            <td style='text-align: center;' colspan="6">
                <input type='hidden' name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                <input type='hidden' name="consultation_ID" value='<?php echo $_GET['consultation_ID']; ?>'/>
                <input type='submit' name='submitRadilogyform' id='submit' value='SAVE EPISODE OF CARE' onclick=" save_episode_of_care()" class='art-button pull-right' style="border-radius: 5px; padding: 5px; font-weight: bold;">
                <input type='hidden' name='submitRadilogyform' value='true'>
            </td>
        </tr>
    </table>
</div>
<script>
    function Save_Nursing_Notes() {
        var Registration_ID = '<?= $Registration_ID; ?>';
        var consultation_ID = '<?= $consultation_ID; ?>';
        var Nursing_Diagnosis = $("#Nursing_Diagnosis").val();
        var Airway = $("#Airway").val();
        var Breathing = $("#Breathing").val();
        var Circulation = $("#Circulation").val();
        var Deformity = $("#Deformity").val();
        var Exposure = $("#Exposure").val();
        var Nursing_Interverntion = $("#Nursing_Interverntion").val();
        var General_Remarks = $("#General_Remarks").val();
        var History_of_Present_Illness = $("#History_of_Present_Illness").val();
        var Main_Complain = $("#Main_Complain").val();
        var Employee_ID = '<?= $current_Employee_ID; ?>';
        if(confirm("You are abount to save These Patient Notes, Do you want to Proceed?")){
                $.ajax({
                    type: "GET",
                    url: "ajax_save_emd_notes.php",
                    data: {
                        Registration_ID:Registration_ID,
                        consultation_ID:consultation_ID,
                        Nursing_Diagnosis:Nursing_Diagnosis,
                        Airway:Airway,
                        Breathing:Breathing,
                        Circulation:Circulation,
                        Deformity:Deformity,
                        Exposure:Exposure,
                        Nursing_Diagnosis:Nursing_Diagnosis,
                        Nursing_Interverntion:Nursing_Interverntion,
                        General_Remarks:General_Remarks,
                        Employee_ID:Employee_ID,
                        Main_Complain:Main_Complain,
                        History_of_Present_Illness:History_of_Present_Illness,
                    },
                    success: function (response) {
                        if(response == 200){
                            alert("EMD Nursing Report was Saved Succesfully");
                            location.reload();
                        }else{
                            alert("Failed to save EMD Nursing Report, Please Contact your Administrator for further Assistance")
                        }
                        
                    }
                });
        }
    }

    function add_new_notes() {
        var Nursing_Diagnosis = $("#Nursing_Diagnosis").val();
        var Airway = $("#Airway").val();
        var Breathing = $("#Breathing").val();
        var Circulation = $("#Circulation").val();
        var Deformity = $("#Deformity").val();
        var Exposure = $("#Exposure").val();
        var Nursing_Interverntion = $("#Nursing_Interverntion").val();
        var General_Remarks = $("#General_Remarks").val();
        var Employee_ID = '<?= $current_Employee_ID; ?>';
        var EMD_nursing_ID = '<?= $EMD_nursing_ID; ?>';
        if(Nursing_Interverntion != '' && General_Remarks != '' && EMD_nursing_ID != ''){
            if(confirm("You are about to Save this Notes for this Patient, Click Ok to Proceed!")){
                $.ajax({
                    type: "GET",
                    url: "add_new_notes.php",
                    data: {
                        Nursing_Diagnosis:Nursing_Diagnosis,
                        Airway:Airway,
                        Breathing:Breathing,
                        Circulation:Circulation,
                        Deformity:Deformity,
                        Exposure:Exposure,
                        Nursing_Diagnosis:Nursing_Diagnosis,
                        Nursing_Interverntion:Nursing_Interverntion,
                        General_Remarks:General_Remarks,
                        Employee_ID:Employee_ID,
                        EMD_nursing_ID:EMD_nursing_ID,
                    },
                    success: function (response) {
                        if(response == 200){
                            location.reload();
                        }
                        
                    }
                });
            }
            }else{
                alert("Please Fill All Reded Fields");
                $("#Nursing_Interverntion").css("border","2px solid red");
                $("#Nursing_Interverntion").focus();
                $("#General_Remarks").css("border","2px solid red");
                $("#General_Remarks").focus();
                $("#Nursing_Diagnosis").css("border","2px solid red");
                $("#Nursing_Diagnosis").focus();
                exit();
            }

        }

        function add_nurse_vitals() {
           $("#add_vitals_nurse").dialog("open");
        }
        function add_point_care(){
            $("#add_point_care").dialog("open");
        }

        function save_vitals() {
            var Blood_Pressure_Systolic = $("#Blood_Pressure_Systolic").val();
            var Blood_Pressure_Diastolic = $("#Blood_Pressure_Diastolic").val();
            var Pulse_Blood = $("#Pulse_Blood").val();
            var Temperature = $("#Temperature").val();
            var Resp_Bpressure = $("#Resp_Bpressure").val();
            var Drainage = $("#Drainage").val();
            var body_weight = $("#body_weight").val();
            var oxygen_saturation = $("#oxygen_saturation").val();
            var Employee_ID = '<?= $Employee_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            var Registration_ID = '<?= $Registration_ID ?>';

            
            if(Blood_Pressure_Systolic == '' && Blood_Pressure_Diastolic == ''){
                alert("Please Fill Blood Pressure before Proceeding");
                $("#Blood_Pressure_Systolic").css("border","2px solid red");
                $("#Blood_Pressure_Systolic").focus();
                $("#Blood_Pressure_Diastolic").css("border","2px solid red");
                $("#Blood_Pressure_Diastolic").focus();
                $("#Temperature").css("border","2px solid red");
                $("#Temperature").focus();
                exit();
            }
            if(confirm("Are you sure You want to Submit These Vitals?")){
                $.ajax({
                    type: "GET",
                    url: "save_emd_vitals.php",
                    data: {
                        Blood_Pressure_Systolic:Blood_Pressure_Systolic,
                        Blood_Pressure_Diastolic:Blood_Pressure_Diastolic,
                        Pulse_Blood:Pulse_Blood,
                        Temperature:Temperature,
                        Resp_Bpressure:Resp_Bpressure,
                        Drainage:Drainage,
                        body_weight:body_weight,
                        oxygen_saturation:oxygen_saturation,
                        Employee_ID:Employee_ID,
                        consultation_ID:consultation_ID,
                        Registration_ID:Registration_ID,
                    },
                    success: function (response) {
                        if(response == 200){
                            $("#add_vitals_nurse").dialog("close");
                            location.reload();
                        }else{
                            alert("Failed To Save Vitals, Please Contact your Admiistrator for Assistance");
                            exit();
                        }
                    }
                });
            }
        }


        function save_episode_of_care() {
            var Employee_ID = '<?= $current_Employee_ID ?>';
            var consultation_ID = '<?= $consultation_ID ?>';
            var Registration_ID = '<?= $Registration_ID ?>';
            var mdrt = $("#mdrt").val();
            var ecg_comments = $("#ecg_comments").val();
            var ecg = $("input[name = 'ecg']:checked").val();
            var rbg = $("#rbg").val();
            var pH = $("#pH").val();
            var pCO2 = $("#pCO2").val();
            var pO2 = $("#pO2").val();
            var Hct = $("#Hct").val();
            var S02 = $("#S02").val();
            var Hb = $("#Hb").val();
            var Na = $("#Na").val();
            var K = $("#K").val();
            var iCA = $("#iCA").val();
            var Cl = $("#Cl").val();
            var Li = $("#Li").val();
            var nCa = $("#nCa").val();
            var GLU = $("#GLU").val();
            var LAC = $("#LAC").val();
            var HCO3 = $("#HCO3").val();
            var TCO2 = $("#TCO2").val();
            var SBC = $("#SBC").val();
            var O2Ct = $("#O2Ct").val();
            var p02 = $("#p02").val();
            var BE = $("#BE").val();
            var BE_B = $("#BE_B").val();
            var BE_ECF = $("#BE_ECF").val();
            var AG_NA = $("#AG_NA").val();
            var AG_K = $("#AG_K").val();

            if(confirm("You are about to Save Episode of Care for this patient, Do you want to Proceed?")){
                $.ajax({
                    type: "GET",
                    url: "ajax_save_emd_episode_of_care.php",
                    data: {
                        Employee_ID:Employee_ID,
                        consultation_ID:consultation_ID,
                        Registration_ID:Registration_ID,
                        mdrt:mdrt,
                        ecg_comments:ecg_comments,
                        ecg:ecg,
                        rbg:rbg,
                        pH:pH,
                        pCO2:pCO2,
                        pO2:pO2,
                        Hct:Hct,
                        S02:S02,
                        Hb:Hb,
                        Na:Na,
                        K:K,
                        iCA:iCA,
                        Cl:Cl,
                        Li:Li,
                        nCa:nCa,
                        GLU:GLU,
                        LAC:LAC,
                        HCO3:HCO3,
                        TCO2:TCO2,
                        SBC:SBC,
                        O2Ct:O2Ct,
                        p02:p02,
                        BE:BE,
                        BE_B:BE_B,
                        BE_ECF:BE_ECF,
                        AG_NA:AG_NA,
                        AG_K:AG_K,
                    },
                    success: function (response) {
                        if(response == 200){
                            alert("Episode of Care was Saved Successfully!");
                            location.reload();
                        }else{
                            alert("Failed to Save Episode of Care");
                        }
                    }
                });
            }
        }

//         function get_bloodPressure_chart(){
//         var Registration_ID = $("#Registration_ID").val();
//         var anasthesia_record_id = $("#anasthesia_record_id").val();
//         $.ajax({
//             type:'POST',
//             url:'add_anaesthetic_item.php',
//             data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,BPreadings_graph:''},
//             success:function(data){
//                 //console.log(data)
//                 var all_saved_data_result = JSON.parse(data);
// //                console.log(all_saved_data_result);
//                 var systoric_pressure=all_saved_data_result[0]
//                 var diastolic_pressure=all_saved_data_result[1]
//                 if(systoric_pressure.length<=0){
//                     systoric_pressure=[[]]
//                     diastolic_pressure=[[]]
//                 }
                
                
//                 console.log(systoric_pressure+"<====sytolic\n dystolic===>"+diastolic_pressure);
//                 //console.log("sytolic==="+systoric_pressure+"\n"+diastolic_pressure);
                
//                 Resp_Bpressure_value=[[]]
//                 oxygen_saturation_value=[[]]
//                 observation_chart_graph(systoric_pressure,diastolic_pressure)
//             }
//         });
//     }

    
</script>
<?php
$num=1;
// die("SELECT Blood_Pressure_Diastolic, Blood_Pressure_Systolic, date FROM Pre_post_vitals");
$select_vitals = mysqli_query($conn, "SELECT Blood_Pressure_Systolic, Blood_Pressure_Diastolic, date, Temperature FROM tbl_nursecommunication_observation WHERE consultation_ID = '$consultation_ID'");
    $Sn = mysqli_num_rows($select_vitals);
    // echo $Sn;
    $Pulse_Blood_value=array();
    $Pulse_Blood_bp=array();
    $Temperature_Value=array();

     if(($Sn)>0){
         while($array = mysqli_fetch_assoc($select_vitals)){
             $Blood_Pressure_Systolic = $array['Blood_Pressure_Systolic'];
             $Blood_Pressure_Diastolic = $array['Blood_Pressure_Diastolic'];
             $date = $array['date'];
             $Temperature = $array['Temperature'];

             $Blood_Pressure_Systolic_V = array();
             $Blood_Pressure_Diastolic_V = array();
             $Temperature_V = array();

            if($Blood_Pressure_Diastolic > 0){
                $Blood_Pressure_Diastolic_V = array("label"=>$date, "y"=> $Blood_Pressure_Diastolic);
            }
            if($Blood_Pressure_Systolic > 0){
                $Blood_Pressure_Systolic_V = array("label"=>$date, "y"=> $Blood_Pressure_Systolic);
            }
            if($Temperature > 0){
                $Temperature_V = array("label"=>$date, "y"=> $Temperature);
            }


            if(sizeof($Blood_Pressure_Diastolic_V)>0){
                array_push($Pulse_Blood_value, $Blood_Pressure_Diastolic_V);
            }
            if(sizeof($Blood_Pressure_Systolic_V)>0){
                array_push($Pulse_Blood_bp, $Blood_Pressure_Systolic_V);
            }
            if(sizeof($Temperature_V)>0){
                array_push($Temperature_Value, $Temperature_V);
            }

         }
     }
      
     ?>
     <!DOCTYPE HTML>
     <html>
     <head>
     <script>
     window.onload = function () {
      
     var chart = new CanvasJS.Chart("chartContainer", {
         title: {
             text: "EMD BP & TEMPERATURE MONITORING"
         },
         axisY: {
             title: "BP & TEMP."
         },
         axisX: {
            title: "CHARTING TIME"
         },
         data: [{
             type: "line",
             dataPoints: <?php echo json_encode($Pulse_Blood_value, JSON_NUMERIC_CHECK); ?>

         },{
            type: "line",
             dataPoints: <?php echo json_encode($Pulse_Blood_bp, JSON_NUMERIC_CHECK); ?>

         },{
            type: "line",
             dataPoints: <?php echo json_encode($Temperature_Value, JSON_NUMERIC_CHECK); ?>

         }]
         
     });
     
     chart.render();
      
     }
     </script>

     <script src="js/graph_line.js"></script>
                             
<script>
    $(document).ready(function () {
        $("#add_vitals_nurse").dialog({autoOpen: false, width: '60%', height: 260, title: 'EMD PATIENT VITAL SIGNS', modal: true});
        $("#add_point_care").dialog({autoOpen: false, width: '60%', height: 450, title: 'EMD POINT OF CARE', modal: true});
    });
var radio = document.getElementById('Normal');
var radio2 = document.getElementById('Abnormal');
radio2.addEventListener('change',function(){
    var month = document.querySelector('.ecg_table');
    if(this.checked){
        month.style.display='inline';
    }else{
        month.style.display='none';
    }
})
radio.addEventListener('change',function(){
    var month = document.querySelector('.ecg_table');
    if(this.checked){
        month.style.display='none';
    }else{
        month.style.display='inline';
    }
})
</script>

<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>

<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 

<script type="text/javascript" src="jquery.jqplot.js"></script>
    <script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
    <script type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
    <link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />

<?php
include("includes/footer.php");
?>