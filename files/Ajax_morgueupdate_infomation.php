 <?php
include("./includes/header.php");
include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    session_start();

	if(isset($_GET['from']) && $_GET['from'] == "registerBody") {
		echo "<a href='morgueupdate_infomation.php?from=registerBody' class='art-button-green'>BACK</a>";
	} else {
		echo "<a href='list_of_patient_cheked_in_n_from_inpatient.php' class='art-button-green'>BACK</a>";
	}


if(isset($_GET['Ward_suggested']) && $_GET['Ward_suggested']!=0){
    
    $Ward_suggested= $_GET['Ward_suggested'];
}  else {
    $Ward_suggested=0;  
}

$set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
$bedStat="";      
if ($set_duplicate_bed_assign == '0') {
    $bedStat=" AND Status = 'available'";
}

if(isset($_GET['fromDoctorPage'])){
 $fromDoctorPage=$_GET['fromDoctorPage'];     
}else{
 $fromDoctorPage='';   
}



$set_duplicate_bed_assign = $_SESSION['hospitalConsultaioninfo']['set_duplicate_bed_assign'];
$Can_admit_before_discharge = $_SESSION['systeminfo']['Can_admit_before_discharge'];

$section = '';
if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    
}

if (!isset($_GET['Check_In_ID'])) {
 $select_checkin = "SELECT Check_In_ID FROM tbl_check_in WHERE Registration_ID = '" . $_GET['Registration_ID'] . "' ORDER BY Check_In_ID DESC LIMIT 1";
            //echo $select_checkin;exit;
            $select_checkin_qry = mysqli_query($conn,$select_checkin) or die(mysqli_error($conn));
            $checkin = mysqli_fetch_assoc($select_checkin_qry);
            $_GET['Check_In_ID'] = $checkin['Check_In_ID'];
}

$Admission_Number = '';
//get the current date
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

if (isset($_GET['Registration_ID'])) {
  $Registration_ID = $_GET['Registration_ID'];  
}  else {
   $Registration_ID =''; 
   
   
}
$PATIENT_NAME = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['Patient_Name'];

                                
                                    //from ward

									
                         $select_desesed_from_ward_info_result=mysqli_query($conn,"SELECT * FROM tbl_mortuary_admission where Corpse_ID='$Registration_ID'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($select_desesed_from_ward_info_result)>0){
                                     while($disease_from_ward_rows=mysqli_fetch_assoc($select_desesed_from_ward_info_result)){
                                              $Corpse_Kin_Address=$disease_from_ward_rows['Corpse_Kin_Address'];
                                              $Corpse_Received_By=$disease_from_ward_rows['Corpse_Received_By'];
                                              $Corpse_Kin_Phone=$disease_from_ward_rows['Corpse_Kin_Phone'];
                                              $Nurse_On_Duty=$disease_from_ward_rows['Nurse_On_Duty'];
                                              $inalala_bilakulala=$disease_from_ward_rows['inalala_bilakulala'];
                                              $Place_Of_Death=$disease_from_ward_rows['Place_Of_Death'];
                                              $Corpse_Kin_Relationship=$disease_from_ward_rows['Corpse_Kin_Relationship'];  
                                              $Corpse_Brought_By=$disease_from_ward_rows['Corpse_Brought_By'];  
                                              $Date_Of_Death=$disease_from_ward_rows['Date_Of_Death'];  
                                              $admitted_from=$disease_from_ward_rows['admitted_from'];  
                                              $case_type=$disease_from_ward_rows['case_type']; 
                                              $course_of_death=$disease_from_ward_rows['course_of_death']; 
                                              $body_condition=$disease_from_ward_rows['body_condition']; 
                                              $name_of_doctor_confirm_death=$disease_from_ward_rows['name_of_doctor_confirm_death']; 
                                              $Vehicle_No_In=$disease_from_ward_rows['Vehicle_No_In'];
                                              $Postmortem_Done_By=$disease_from_ward_rows['Postmortem_Done_By'];
                                              $Police_Name=$disease_from_ward_rows['Police_Name'];
                                              $Police_Title=$disease_from_ward_rows['Police_Title'];
                                              $Police_Station=$disease_from_ward_rows['Police_Station'];
                                              $Vehicle_No_In=$disease_from_ward_rows['Vehicle_No_In'];
                                              $Police_No=$disease_from_ward_rows['Police_No'];
                                              $Police_Phone=$disease_from_ward_rows['Police_Phone'];
                                              $Police_Station=$disease_from_ward_rows['Police_Station'];
                                              $Postmortem_No=$disease_from_ward_rows['Postmortem_No'];
                                              $Other_Details=$disease_from_ward_rows['Other_Details'];
                                              
                                              
                                              $name_nurse = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Nurse_On_Duty'"))['Employee_Name'];
                                              $name_received_by = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Corpse_Received_By'"))['Employee_Name'];
                                              $Postmortem_Done_By_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Postmortem_Done_By'"))['Employee_Name'];
                                              
                                              
                                              
//                                           $pf3_ID=$from_pf3_rows['pf3_ID'];
//                                           $Police_Station=$from_pf3_rows['Police_Station'];
//                                           $Check_In_ID=$from_pf3_rows['Check_In_ID'];
//                                           $pf3_attachment_file=$from_pf3_rows['pf3_attachment_file'];
//                                           $pf3_Date_And_Time=$from_pf3_rows['pf3_Date_And_Time'];
//                                           $pf3_Description=$from_pf3_rows['pf3_Description'];
//                                           $Police_Station=$from_pf3_rows[''];
//                                           $Relative=$from_pf3_rows[''];
//                                           $Phone_No_Relative=$from_pf3_rows[''];
//                                           $Phone_No_Relative=$from_pf3_rows[''];
//                                           $Reason_ID=$from_pf3_rows[''];
//                                           $case_type='police';
                                              
                                              
                                        }
                                        
                                    }
                                  
                               
                               
                                   
                            
                               
//                               print_r($pf3data_rows);
//                              die("oyoooooo---$pf3");
                            ?>
			<fieldset>
				<table  width='100%'>
					<legend align=center>PATIENT INFORMATION</legend>
					<!-- <tr>
						<td colspan=2><center><b>PATIENT INFORMATION</b></center></td>
					<td id='home_case' style='' colspan="2" ><center><b><?php  echo $Registration_ID ?></b></center></td>
					<td id='police_case' style='' colspan="4"><center><b><?php  echo $PATIENT_NAME ?></b></center></td>
					</tr> CHANGED  BY JULY 24 KUNTA YONGO--> 
					<tr>
						<td colspan=2><center><b>DETAILS</b></center></td>
					<td id='home_case' style='' colspan="2" ><center><b>NEXT OF KIN NAME AND CONTACT</b></center></td>
					<td id='police_case' style='' colspan="4"><center><b>POLICE INFORMATION (FOR POLICE CASE ONLY)</b></center></td>
					<!--</tr> CHANGED  BY JULY 24 KUNTA YONGO--> 
					<tr>
                                            <td width='13%' style='text-align:right;'>Admitted From /Case :</td>
						<td width='16%'>
							<select name='admitted_from' id='admitted_from'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<option value="<?php echo $admitted_from ?>"><?php  echo $admitted_from ?></option>
                                                                <option value="from_ward">INPATIENT</option>
								<option value="from_outside_traffic">OUTSIDE-TRAFFIC CASE</option>
                                                                <option value="from_outside_police">OUTSIDE-POLICE CASE</option>
                                                                <option value="from_outside_home">OUTSIDE-HOME CASE</option>
                                                                <option value="from_foreigners">FOREIGNERS CASE</option>
					                        <option value="from_foreigners_home">FOREIGNERS-HOME CASE</option>

								
							</select> 
						</td>
                                                
						<td width='13%'style='text-align:right;'>Date Of Death :</td>
						<td>
							<input type="text"  name="Date_Of_Death" id="Date_Of_Death" value="<?php echo $Date_Of_Death; ?>" style="text-align: center;" placeholder = "~~~ ~~~ Date Of Death ~~~ ~~~">
						</td>
						
						<td id='Police_Name1' style='text-align:right;'>Police Name</td>
						<td width='16%' style=''><input type='text'  name='Police_Name' id='Police_Name' value="<?php echo $Police_Name ?>"></td>
					</tr>
					<tr>
						<td width='13%' style='text-align:right;'>HOSPITAL or CASE :</td>
						<td width='16%'>
							<select name='Case_Type' id='Case_Type'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
                                                                
								<option value="<?php echo $case_type;  ?>"><?php echo $case_type;  ?></option>
                                                                <option value="police">POLICE CASE</option>
                                                                <option value="hospital">HOSPITAL CASE</option>
								<option value="home">Dead before Arrive </option>
                                                                <option value="hospital">Dead after Arrive </option>
                                                               
                                                                
                                                                
							</select> 
						</td>
                                                <td   id='Corpse_Brought_By1' style='text-align:right;'>Brought By :</td>
                                                <td width='16%' style=''><input type='text'  name='Corpse_Brought_By' value="<?= $Corpse_Brought_By ?>" id='Corpse_Brought_By' ></td>
						
						<td id='Police_Title1' style='text-align:right;'>Title :</td>
						<td width='16%'   style=''><input type='text'  name='Police_Title' id='Police_Title' value="<?php echo $Police_Title ?>"></td>

					
					</tr>
					<tr>
                                            <td id='Corpse_Kin_Relationship1' style='text-align:right;'>Issued by/Aliyekabidhi Mwili :</td>
                                                <td width='16%'   style=''><input type='text'  name='Corpse_Kin_Relationship' value="<?= $Corpse_Kin_Relationship ?>" id='Corpse_Kin_Relationship' ></td>
					<!--span id="Nurse_On_Duty" style="display:none"-->
						<td style='text-align:right;'>Place Of Death</u> :</td>
                                                <td width='16%'><input type='text' id='Place_Of_Death' value="<?= $Place_Of_Death ?>"name='Place_Of_Death'></td>
					<!--/span-->
						
						<td id='Police_Station1' style='text-align:right;'>Police Station :</td>
						<td width='16%' style=''><input type='text'  name='Police_Station' id='Police_Station' value="<?php echo $Police_Station ?>"></td>
						
					</tr>
					<tr>
						<!--td style='text-align:right;'>Case Type :</td>
						<td width='16%'>
							<select required='required' name='Case_Type' id='Case_Type' >
								<option value="">SELECT CASE</option>
								<option value="hospital">HOSPITAL ADMISSION</option>
								<option value="home">HOME CASE</option>
								<option value="police">POLICE CASE</option>
							</select>
						</td-->
                                                <td width='13%' style='text-align:right;'>OUT BEFORE OR AFTER 24HRS?:</td>
						<td width='16%'>
							<select name='inalala_bilakulala' id='inalala_bilakulala'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<option value="<?= $inalala_bilakulala ?>"><?= $inalala_bilakulala ?></option>
                                                                <option value="inalala">Maiti inayolala</option>
								
								<option value="bilakulala">Maiti inayochukuliwa billakulala</option>
									<option value="bilakulala_na_dawa">Maiti inayochukuliwa bilakulala na dawa</option>
							</select> 
						</td>
                                                
                                                </td>
						<td style='text-align:right;'>Vehicle N<u>o</u> :</td>
						<td width='16%'><input type='text' id='Vehicle_No_In' name='Vehicle_No_In' value="<?= $Vehicle_No_In ?>"></td>
						</td>
						
						
						<td id='Police_No1' style='text-align:right;' >Police N<u>o</u> :</td>
						<td style=''><input type='text'  name='Police_No' id='Police_No' value="<?= $Police_No ?>"></td>
					</tr>
					<tr>
						<td width='13%' id='Nurse_On_Duty1' style='text-align:right; '>Nurse On Duty</td>
						<td width='16%' >
							<select   name='Nurse_On_Duty'id='Nurse_On_Duty' onchange="filterNurse()"  style='width:100%;padding: 5px;text-align: center;'>
							<option value="<?= $Nurse_On_Duty ?>"><?= $name_nurse ?></option>
								<?php
                                                                        //WHERE Employee_Type='Nurse'
                                                                
									$select_nurse = "SELECT * FROM  tbl_employee";
									$reslt = mysqli_query($conn,$select_nurse);
									while ($output = mysqli_fetch_assoc($reslt)) {
										?>
                                                                                <option value='<?php echo $output['Employee_ID']; ?>' ><?php echo $output['Employee_Name']; ?></option>    
										<?php
									}
									?>
							</select>
						</td>
						<td id='Corpse_Kin_Phone1' style='text-align:right;'>Phone :</td>
                                                <td width='16%' style=''><input type='text' id='Kin_Phone'value="<?= $Corpse_Kin_Phone ?>" name='Corpse_Kin_Phone' onkeyup="numberOnly(this)" ></td>
						
						<td id='Police_Postal_Box1' style='text-align:right;'>Police Phone :</td>
						<td width='16%'  style='' ><input type='text'  name='Police_Phone' id='Police_Phone' value="<?= $Police_Phone ?>"></td>
					</tr>
					<tr>
						<td width='13%' style='text-align:right;'>Received By</td>
						<td width='16%'>
							<input  type='hidden' id='Corpse_Received_By' name='Corpse_Received_By' value='<?php echo $Employee_ID; ?>'>
							<input type='text'   disabled='disabled' value='<?php echo $name_received_by; ?>'>
						</td>
                                                <!--KUNTACODE<td style='text-align:right;' colspan="5"><b style="color:red;font-size:14px">Select Cabinet</b></td>-->
						<td id='Corpse_Kin_Address1'  style='text-align:right;color: red; '>Address:</td>
                                                <td width='16%' style=''><input type='text'  name='Corpse_Kin_Address'value="<?= $Corpse_Kin_Address ?>" id='Corpse_Kin_Address'></td>
                                                
						 
						<td id='Police_Postal_Box1' style='text-align:right;'>Postal Box :</td>
						<td width='16%' style='' ><input type='text'  name='Police_Postal_Box' id='Police_Postal_Box' ></td>
					</tr>
					<tr>
						<td style='text-align:right;'>Course of death:</td>
                                                <td><input type='text' required="" name='course_of_death'value="<?= $course_of_death ?>" id='course_of_death'></td>
						<td></td>
						<td></td>
						<td id='Police_Place1' style='text-align:right;'>Police Place :</td>
						<td width='16%' style=''><input type='text'  name='Police_Place' id='Police_Place' value="<?= $Police_Station ?>"></td>
					</tr>
					<tr>
						<td style='text-align:right;color: red;'>Body Condition:</td>
                                        
                                                <td>
                                                    <select name='Good Condition' id='body_Condition'  style='width:100%;padding: 5px' onchange="filterCase(this);"   >
								<option value="<?= $body_condition ?>"><?= $body_condition ?></option>
                                                                <option value="Good Condition">Good Condition</option>
                                                                <option value="Fresh Dead Body">Fesh Dead Body</option>
								<option value="Bad Condition">Bad Condition</option>
								
							</select> 
                                                    <!--<input type='text'  required="" name='body_condition' id='body_condition' value="Good Condition">-->
                                                </td>
						<td></td>
						<td></td>
						<td></td>
						<td><label>Will postmoterm done?</label>  <input type="checkbox" name="postmoterm done"/>   </td>
                                          
						
					</tr>
					<tr>
						<td style='text-align:right;color: red;'>Name of doctor confirm dearth:</td>
                                                <td><input type='text'  required=""name='name_of_doctor_confirm_death' value="<?= $name_of_doctor_confirm_death ?>" id='name_of_doctor_confirm_death'></td>
						<td width='16%'  style='padding: 5px;text-align: center;'>
                                                <td></td>
                                                           <td style='text-align:right;'>Postmortem_Done_By:</td>
							<td><select  name='Postmortem_Done_By' id='Postmortem_Done_By'  style='text-align: center;'>
							<option value="<?= $Postmortem_Done_By ?>"> <?= $Postmortem_Done_By_name ?></option>
								<?php
									$select_doctor = "SELECT * FROM  tbl_employee WHERE Employee_Type='Doctor' OR Employee_Type='doctor'";
									$reslt = mysqli_query($conn,$select_doctor);
									while ($output = mysqli_fetch_assoc($reslt)) {
										?>
										<option value='<?php echo $output['Employee_ID']; ?>'><?php echo $output['Employee_Name']; ?></option>    
										<?php
									}
									?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan='4'></td>
						<td style='text-align:right;' id='Postmortem_No1' >Postmortem N<u>o</u> :</td>
						<td width='16%' ><input type='text'  name='Postmortem_No' id='Postmortem_No' value="<?php echo $Postmortem_No ?>"></td>
					</tr>
					<tr>
						<td style='text-align:right;'>Other Details:</td>
						<td colspan="5">
							<textarea name="Other_Details" id="Other_Details" style="float:right; font-size:16px;"  value="<?php echo $Other_Details ?>"> </textarea>
						</td>
					</tr>
					<tr>
                                          <td colspan="7">  
                                        <button type='submit' class='art-button-green' onclick="update_admission_mortuary()">UPDATE</button>
                                        </td>
					</tr>
                                        
				</table>
                                            

			</fieldset>

 <script>
     function update_admission_mortuary(){
           var admitted_from = $('#admitted_from').val();
           var Date_Of_Death = $('#Date_Of_Death').val();
           var Police_Name = $('#Police_Name').val();
           var Case_Type = $('#Case_Type').val();
           var Corpse_Brought_By = $('#Corpse_Brought_By').val();
           var Police_Title = $('#Police_Title').val();
           var Corpse_Kin_Relationship = $('#Corpse_Kin_Relationship').val();
           var Place_Of_Death = $('#Place_Of_Death').val();
           var Police_Station = $('#Police_Station').val();
           var inalala_bilakulala = $('#inalala_bilakulala').val();
           var Vehicle_No_In = $('#Vehicle_No_In').val();
           var Police_No = $('#Police_No').val();
           var Nurse_On_Duty = $('#Nurse_On_Duty').val();
           var Kin_Phone = $('#Kin_Phone').val();
           var Police_Phone = $('#Police_Phone').val();
           var Corpse_Kin_Address = $('#Corpse_Kin_Address').val();
           var course_of_death = $('#course_of_death').val();
           var Police_Place = $('#Police_Place').val();
           var body_Condition = $('#body_Condition').val();
           var name_of_doctor_confirm_death = $('#name_of_doctor_confirm_death').val();
           var Postmortem_Done_By = $('#Postmortem_Done_By').val();
           var Postmortem_No = $('#Postmortem_No').val();
           var Other_Details = $('#Other_Details').val();
           var Registration_ID = '<?= $Registration_ID  ?>';
         
          $.ajax({
                type: 'POST',
                url: 'Ajax_update_admission_mortuary.php',
                data: {Registration_ID:Registration_ID,admitted_from:admitted_from,Date_Of_Death:Date_Of_Death,Police_Name:Police_Name,Case_Type:Case_Type,Corpse_Brought_By:Corpse_Brought_By,Police_Title:Police_Title,
                      Corpse_Kin_Relationship:Corpse_Kin_Relationship,Place_Of_Death:Place_Of_Death,Police_Station:Police_Station,
                      inalala_bilakulala:inalala_bilakulala,Vehicle_No_In:Vehicle_No_In,Police_No:Police_No,Nurse_On_Duty:Nurse_On_Duty,Kin_Phone:Kin_Phone,
                      Police_Phone:Police_Phone,Corpse_Kin_Address:Corpse_Kin_Address,course_of_death:course_of_death,Police_Place:Police_Place,
                      body_Condition:body_Condition,name_of_doctor_confirm_death:name_of_doctor_confirm_death,Postmortem_Done_By:Postmortem_Done_By,Postmortem_No:Postmortem_No,Other_Details:Other_Details},
                cache: false,
                success: function (html) {
                    
                    alert("succssfully saved");
                    window.open('individual_admission_report.php?mortuary=print&Registration_ID=<?php echo $Registration_ID; ?>');
                
                }
            });

        }
    </script>
	<script src="js/select2.min.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Mortuary_Date_In').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Mortuary_Date_In').datetimepicker({value:'',step:01});
    $('#Mortuary_Deadline').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Mortuary_Deadline').datetimepicker({value:'',step:01});
    $('#Date_Of_Death').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_Of_Death').datetimepicker({value:'',step:01});
</script>