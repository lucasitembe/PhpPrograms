<?php
    include("./includes/header.php");
    include("./includes/connection.php");
     $temp=1;
	// $temp=++;
	 
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Admission_Works'])){
//	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	    }
//	}else{
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
		
    }
    
    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = 0;
    }
    
    if(isset($_GET['Admision_ID'])){
    	$Admision_ID = $_GET['Admision_ID'];
    }else{
    	$Admision_ID = 'NULL';
    }
    
    if(isset($_GET['consultation_ID'])){
    	$consultation_ID = $_GET['consultation_ID'];
    }else{
    	$consultation_ID = 0;
    }

    $nav='';

if(isset($_GET['discharged'])){
   $nav='&discharged=discharged';
}
	//check if previous records available
	$slct = mysqli_query($conn,"select Pre_Operative_ID from tbl_pre_operative_checklist where
							Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($slct);
?>
		<button class='art-button-green' onclick="Preview_Previous_Records()">
			PREVIOUS RECORDS
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'><?php echo $nm; ?></span>
		</button>
<a href='nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>' class='art-button-green'>BACK</a>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }

// end of the function -->


 if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }


		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_Name'])){
                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
            }else{
                $Employee_Name = 'Unknown';
            }
        }

?>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select Registration_ID,
                                    Old_Registration_Number, Patient_Name,Title,
                                    Date_Of_Birth,Gender,pr.Region,pr.District,pr.Ward,pr.Sponsor_ID,Member_Number,Member_Card_Expire_Date,
				    pr.Phone_Number,Email_Address,Occupation,Employee_Vote_Number,Emergence_Contact_Name,
				    Emergence_Contact_Number,Company,Employee_ID,Registration_Date_And_Time,Patient_Picture,Guarantor_Name
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        
										
				    where pr.Sponsor_ID = sp.Sponsor_ID  and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Title = $row['Title'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
				$Region = $row['Region'];
                $District = $row['District'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Ward = $row['Ward'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Member_Number = $row['Member_Number'];
				$Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Patient_Picture = $row['Patient_Picture'];
				$Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Occupation = $row['Occupation'];
                $Email_Address = $row['Email_Address'];
				$Guarantor_Name = $row['Guarantor_Name'];
				
                
               }
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";   
	   
	    
        }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
	            $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
                $Guarantor_Name=''; 
             			
        }
    }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
		        $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
		        $Guarantor_Name='';
				
        }
	
?>
<br/><br/>
<fieldset>
	<table width="100%">
		<tr>
			<td width="8%" style="text-align: right;">Patient Name</td>
			<td><input type="text" name="P_Name" id="P_Name" value="<?php echo ucwords(strtolower($Patient_Name)); ?>"></td>
			<td width="8%" style="text-align: right;">Patient #</td>
			<td width="6%"><input type="text" name="P_Name" id="P_Name" value="<?php echo ucwords(strtolower($Registration_ID)); ?>"></td>
			<td width="6%" style="text-align: right;">Gender</td>
			<td width="7%"><input type="text" name="P_Name" id="P_Name" value="<?php echo $Gender; ?>"></td>
			<td width="8%" style="text-align: right;">Patient Age</td>
			<td><input type="text" name="P_Name" id="P_Name" value="<?php echo $age; ?>"></td>
			<td width="8%" style="text-align: right;">Sponsor Name</td>
			<td><input type="text" name="P_Name" id="P_Name" value="<?php echo ucwords(strtolower($Guarantor_Name)); ?>"></td>
		</tr>
	</table>
</fieldset>
<fieldset style='overflow-y: scroll; height:400px; background-color: white;'>
     <legend align="left"><b>PRE OPERATIVE CHECKLIST</b></legend>
 	<form action='#' method='post' name='myForm' id='myForm'>
    	<table style="text-align:center; width:100%">
     		<tr id="sss">
				<td style="text-align:center; width: 5%;" ><b>SN</b></td>
				<td style="text-align:left; width: 30%;" ><b>TASK</b></td>
				<td style="text-align:center; width: 15%;" ><b>REMARKS</b></td>
				<td style="text-align:center; width: 5%;" ><b>SN</b></td>
				<td style="text-align:left; width: 30%;" ><b>TASK</b></td>
				<td style="text-align:center; width: 15;" ><b>REMARKS</b></td>
    		</tr>
			<tr id="sss">
				<td style="text-align:center;">1</td>
				<td><input type='checkbox' id='Patient_Identified_Name' name='Patient_Identified_Name' value='yes'><label for="Patient_Identified_Name">Patient identified name</label></td>
				<td><input type='text' id='Patient_Idenified_Remark' name='Patient_Idenified_Remark' autocomplete="off"></td>
				<td style="text-align: center;">14</td>
				<td><input type='checkbox' id='Identification_bands' name='Identification_bands' value='yes'><label for="Identification_bands">Identification bands present and correct</label></td>
				<td><input type='text' id='Identification_bands_Remark' name='Identification_bands_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">2</td>
				<td><input type='checkbox' id='Urine_passed' name='Urine_passed' value='yes'><label for="Urine_passed">Urine passed before promed action</label></td>
				<td><input type='text' name='Urine_passed_Remark' id='Urine_passed_Remark' autocomplete="off"></td>
				<td style="text-align:center;">15</td>
				<td><input type='checkbox' id='Loose_teeth' name='Loose_teeth' value='yes'><label for="Loose_teeth">Loose teeth,crowns, and bridges</label></td>
				<td><input type='text' name='Loose_teeth_Remark' id='Loose_teeth_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">3</td>
				<td><input type='checkbox' id='Dentures_removed' name='Dentures_removed' value='yes'><label for="Dentures_removed">Dentures removed</label></td>
				<td><input type='text' id='Dentures_removed_Remark' name='Dentures_removed_Remark' autocomplete="off"></td>
				<td style="text-align:center;">16</td>
				<td><input type='checkbox' id='Hearing_adis' name='Hearing_adis' value='yes'><label for="Hearing_adis">Hearing adis removed</label></td>
				<td><input type='text' name='Hearing_adis_Remark' id='Hearing_adis_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">4</td>
				<td><input type='checkbox' id='Contact_lenses' name='Contact_lenses' value='yes'><label for="Contact_lenses">Contact lenses removed</label></td>
				<td><input type='text' id='Contact_lenses_Remark' name='Contact_lenses_Remark' autocomplete="off"></td>
				<td style="text-align:center;">17</td>
				<td><input type='checkbox' id='Pre_operative_skin' name='Pre_operative_skin' value='yes'><label for="Pre_operative_skin">Pre - operative skin preparation</label></td>
				<td><input type='text' id='Pre_operative_skin_Remark' name='Pre_operative_skin_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">5</td>
				<td><input type='checkbox' id='Jowerly_removed' name='Jowerly_removed' value='yes'><label for="Jowerly_removed">Jowerly removed and rings tapped</label></td>
				<td><input type='text' id='Jowerly_removed_Remark' name='Jowerly_removed_Remark' autocomplete="off"></td>
				<td style="text-align:center;">18</td>
				<td><input type='checkbox' id='Valuable_securely' name='Valuable_securely' value='yes'><label for="Valuable_securely">Valuable securely stored</label></td>
				<td><input type='text' id='Valuable_securely_Remark' name='Valuable_securely_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">6</td>
				<td><input type='checkbox' id='Cosmetic_and_Clothing' name='Cosmetic_and_Clothing' value='yes'><label for="Cosmetic_and_Clothing">Cosmetic and Clothing Removed</label></td>
				<td><input type='text' id='Cosmetic_and_Clothing_Remark' name='Cosmetic_and_Clothing_Remark' autocomplete="off"></td>
				<td style="text-align:center;">19</td>
				<td><input type='checkbox' id='Theatre_gowns' name='Theatre_gowns' value='yes'><label for="Theatre_gowns">Theatre gowns and pants wom</label></td>
				<td><input type='text' id='Theatre_gowns_Remark' name='Theatre_gowns_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">7</td>
				<td><input type='checkbox' id='Consent_form_signed' name='Consent_form_signed' value='yes'><label for="Consent_form_signed">Consent form signed</td>
				<td><input type='text' id='Consent_form_signed_Remark' name='Consent_form_signed_Remark' autocomplete="off"></td>
				<td style="text-align:center;">20</td>
				<td><input type='checkbox' id='Care_patient_case' name='Care_patient_case' value='yes'><label for="Care_patient_case">Care patient case notes and other relevant chart sheet present</label></td>
			    <td><input type='text' id='Care_patient_case_Remark' name='Care_patient_case_Remark' autocomplete="off"></td>
			</tr>
		    <tr id="sss">
				<td style="text-align:center;">8</td>
				<td ><input type='checkbox' id='Enema_or_laxative' name='Enema_or_laxative' value='yes'><label for="Enema_or_laxative">Enema or laxative given</label></td>
				<td><input type='text' id='Enema_or_laxative_Remark' name='Enema_or_laxative_Remark' autocomplete="off"></td>
				<td style="text-align:center;">21</td>
				<td><input type='checkbox' id='Oral_hygiene' name='Oral_hygiene' value='yes'><label for="Oral_hygiene">Oral hygiene given</label></td>
			    <td><input type='text' id='Oral_hygiene_Remark' name='Oral_hygiene_Remark' autocomplete="off"></td>
			</tr>
		    <tr id="sss">
				<td style="text-align:center;">9</td>
				<td><input type='checkbox' id='Other_prosthesis' name='Other_prosthesis' value='yes'><label for="Other_prosthesis">Other prosthesis (if any) removed</label></td>
				<td><input  id='Other_prosthesis_Remark' name='Other_prosthesis_Remark' type='text' autocomplete="off"></td>
				<td style="text-align:center;">22</td>
				<td><input type='checkbox' id='Catheter' name='Catheter' value='yes'><label for="Catheter">Catheter is situ</label></td>
			    <td><input type='text' id='Catheter_Remark' name='Catheter_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">10</td>
				<td ><input type='checkbox' id='Special_order' name='Special_order' value='yes'><label for="Special_order">Special order carried out, as Nasogastric tube</label></td>
				<td><input type='text' id='Special_order_Remark' name='Special_order_Remark' autocomplete="off"></td>
				<td style="text-align:center;">23</td>
				<td><input type='checkbox' id='Patient_having' name='Patient_having' value='yes'><label for="Patient_having">Patient having an i.v  catheter cannula(as 16,18)</label></td>
				<td><input type='text' id='Patient_having_Remark' name='Patient_having_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">11</td>
				<td ><input type='checkbox' id='Operative_site' name='Operative_site' value='yes'><label for="Operative_site">Operative site marked</label></td>
				<td><input type='text' id='Operative_site_Remark' name='Operative_site_Remark' autocomplete="off"></td>
				<td style="text-align:center;">24</td>
				<td><input type='checkbox' id='Check_list' name='Check_list' value='yes'><label for="Check_list">Check list complete</label></td>
				<td><input type='text' id='Check_list_Remark' name='Check_list_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">12</td>
				<td><input type='checkbox' id='Radiographs_accompanying' name='Radiographs_accompanying' value='yes'><label for="Radiographs_accompanying">Radiographs accompanying patient</label></td>
				<td><input type='text' id='Radiographs_accompanying_Remark' name='Radiographs_accompanying_Remark' autocomplete="off"></td>
				<td style="text-align:center;">25</td>
				<td><input type='checkbox' id='Test_for_VDRL' name='Test_for_VDRL' value='yes'><label for="Test_for_VDRL">Test for VDRL</label></td>
				<td><input type='text' id='Test_for_VDRL_Remark' name='Test_for_VDRL_Remark' autocomplete="off"></td>
			</tr>
			<tr id="sss">
				<td style="text-align:center;">13</td>
				<td><input type='checkbox' id='Test_for_HIV' name='Test_for_HIV' value='yes'><label for="Test_for_HIV">Test for HIV</label></td>
				<td><input type='text' id='Test_for_HIV_Remark' name='Test_for_HIV_Remark' autocomplete="off"></td>
				<td style="text-align:center;">26</td>
				<td><input type='checkbox' id='Test_for_Hopatitis' name='Test_for_Hopatitis' value='yes'><label for="Test_for_Hopatitis">Test for Hopatitis</label></td>
				<td><input type='text' id='Test_for_Hopatitis_Remark' name='Test_for_Hopatitis_Remark' autocomplete="off"></td>
			</tr>
			<tr>
				<td colspan="3">
					<table width="100%">
						<tr>
							<td width="25%" style="text-align: right;">Special Information(if any)</td>
							<td><textarea  name='Special_Information' id='Special_Information' style="width: 100%; height: 20px;"></textarea></td>
						</tr>
					</table>
				</td>
				<td colspan="3">
					<table width="100%">
						<tr>
							<td width="25%" style="text-align: right;">Theatre Date & Time</td>
							<td><input type="text" name="Date_From" id="Date_From" placeholder="Theatre Date & Time"></td>
						</tr>
					</table>
				</td>
			</tr>
			<script src="css/jquery.js"></script>
			<script src="css/jquery.datetimepicker.js"></script>
			<script>
			    $('#Date_From').datetimepicker({
				    dayOfWeekStart : 1,
				    lang:'en',
			    });
			    $('#Date_From').datetimepicker({value:'',step:01});
			</script>
			<tr>
				<td colspan="3">
					<table width="100%">
						<tr>
							<td width="25%" style="text-align: right;">Ward Nurse Signature</td>
							<td>
								<select style='width:100%;' name='Ward_Nurse' id='Ward_Nurse'  >
									<option selected='selected' value=''></option>
						<?php
							$data = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Nurse'") or die(mysqli_error($conn));
								while($row = mysqli_fetch_array($data)){
						?>
									<option value='<?php echo $row['Employee_ID'];?>'><?php echo ucwords(strtolower($row['Employee_Name'])); ?></option>
						<?php
								}
						?>
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td colspan="3">
					<table width="100%">
						<tr>
							<td width="25%" style="text-align: right;">Theatre Nurse Signature</td>
							<td>
								<select style='width:100%;' name='Theatre_Nurse' id='Theatre_Nurse'>
									<option selected='selected' value=''></option>
						<?php
							$data = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Nurse'") or die(mysqli_error($conn));
								while($row = mysqli_fetch_array($data)){
						?>
									<option value='<?php echo $row['Employee_ID'];?>'><?php echo ucwords(strtolower($row['Employee_Name'])); ?></option>
						<?php
								}
						?>
								</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
   		</table>
   </fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: center;"><span style="color: #037CB0;"><b>CHECK TASK WHICH ARE COMPLETE AND UNCHEK WHICH ARE NOT COMPLETE</b></span></td>
			<td style='text-align:right;width:40%;'>
				<input type="button" style='width:40%;' name="C_Button" id="C_Button" class="art-button-green" value="SAVE" onclick="Open_Save_Buttons_Dialog()">
				<input type='hidden' name='submittedProActiveCheckList' value='true'/>
			</td>
		</tr>
	</table>

	<div id="Save_Buttons">
		Are you sure you want to save pre operative?<br/><br/>
		<table width="100%" id="sss">
			<tr>
				<td width="55%" style="text-align: center;" id="Error_Msg"></td>
				<td style="text-align: right;">
					<input type="submit" name="submit" value="YES" class="art-button-green" onclick="Submit_Form()">
					<input type="button" name="Cancel" value="CANCEL" class="art-button-green" onclick="Cancel_Save_Process()">
				</td>
			</tr>
		</table>
	</div>
</form>       
</center>

<div id="Previous_Records">
	
</div>

<script type="text/javascript">
	function Preview_Report(Pre_Operative_ID){
		window.open("previewpreorerative.php?Pre_Operative_ID="+Pre_Operative_ID+"&PreviewPreOperative=PreviewPreOperativeThisPage","_blank");
	}
</script>

<script type="text/javascript">
	function Preview_Previous_Records(){
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		var Admision_ID = '<?php echo $Admision_ID; ?>';
		var consultation_ID = '<?php echo $consultation_ID; ?>';

		if (window.XMLHttpRequest) {
            myObjectPrevious = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPrevious = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPrevious.overrideMimeType('text/xml');
        }

        myObjectPrevious.onreadystatechange = function () {
            dataPrev = myObjectPrevious.responseText;
            if (myObjectPrevious.readyState == 4) {
				document.getElementById("Previous_Records").innerHTML = dataPrev;
				$("#Previous_Records").dialog("open");
            }
        };
        myObjectPrevious.open('GET', 'Pre_Operative_Preview_Previous_Records.php?Registration_ID='+Registration_ID+"&Admision_ID="+Admision_ID+"&consultation_ID="+consultation_ID, true);
        myObjectPrevious.send();
	}
</script>

<script type="text/javascript">
	function Submit_Form(){
		var Ward_Nurse = document.getElementById("Ward_Nurse").value;
		var Theatre_Nurse = document.getElementById("Theatre_Nurse").value;
		var Date_From = document.getElementById("Date_From").value;

		if(Ward_Nurse != null && Ward_Nurse != '' && Theatre_Nurse != null && Theatre_Nurse != '' && Date_From != null && Date_From != ''){
			document.getElementById("myForm").submit();
		}else{
			if(Ward_Nurse == null || Ward_Nurse == ''){
				document.getElementById("Ward_Nurse").style = 'border: 3px solid red';
				document.getElementById("Error_Msg").innerHTML = '<span style="color: #037CB0;"><b>Please Select All Nurse Signatures & Date</b></span>';
			}
			
			if(Theatre_Nurse == null || Theatre_Nurse == ''){
				document.getElementById("Theatre_Nurse").style = 'border: 3px solid red';
				document.getElementById("Error_Msg").innerHTML = '<span style="color: #037CB0;"><b>Please Select All Nurse Signatures & Date</b></span>';
			}

			if(Date_From == null || Date_From == ''){
				document.getElementById("Date_From").style = 'border: 3px solid red';
				document.getElementById("Error_Msg").innerHTML = '<span style="color: #037CB0;"><b>Please Select All Nurse Signatures & Date</b></span>';
			}
		}
	}
</script>
<script type="text/javascript">
	function Open_Save_Buttons_Dialog(){
		$("#Save_Buttons").dialog("open");
	}
</script>

<script type="text/javascript">
	function Cancel_Save_Process(){
		$("#Save_Buttons").dialog("close");
		document.getElementById("Error_Msg").innerHTML = '';
	}
</script>

<?php
	if(isset($_POST['submittedProActiveCheckList'])){
		$Patient_Identified_Name = '0';
		$Urine_passed = '0';
		$Dentures_removed = '0';
		$Contact_lenses = '0';
		$Jowerly_removed = '0'; 
		$Cosmetic_and_Clothing = '0';
		$Consent_form_signed = '0';
		$Enema_or_laxative = '0';
		$Other_prosthesis = '0';
		$Special_order = '0'; 
		$Operative_site = '0';
		$Radiographs_accompanying = '0';
		$Test_for_HIV = '0';
		$Identification_bands = '0';
		$Loose_teeth = '0'; 
		$Hearing_adis = '0';
		$Pre_operative_skin = '0';
		$Valuable_securely = '0'; 
		$Theatre_gowns = '0';
		$Care_patient_case = '0';
		$Oral_hygiene = '0';
		$Catheter = '0';
		$Patient_having = '0'; 
		$Check_list = '0';
		$Test_for_VDRL = '0'; 
		$Test_for_Hopatitis = '0';   
	 
	 
	    if(isset($_POST['Patient_Identified_Name'])) { $Patient_Identified_Name= '1'; }
	    if(isset($_POST['Urine_passed'])) { $Urine_passed = '1'; }
	    if(isset($_POST['Dentures_removed'])) { $Dentures_removed = '1'; }
	    if(isset($_POST['Contact_lenses'])) { $Contact_lenses = '1'; }
	    if(isset($_POST['Jowerly_removed'])) { $Jowerly_removed = '1'; } 
	    if(isset($_POST['Cosmetic_and_Clothing'])) { $Cosmetic_and_Clothing= '1'; }
	    if(isset($_POST['Consent_form_signed'])) { $Consent_form_signed = '1'; }
	    if(isset($_POST['Enema_or_laxative'])) { $Enema_or_laxative = '1'; }
	    if(isset($_POST['Other_prosthesis'])) { $Other_prosthesis = '1'; }
	    if(isset($_POST['Special_order'])) { $Special_order = '1'; } 
	    if(isset($_POST['Operative_site'])) { $Operative_site= '1'; }
	    if(isset($_POST['Radiographs_accompanying'])) { $Radiographs_accompanying = '1'; }
	    if(isset($_POST['Test_for_HIV'])) { $Test_for_HIV = '1'; }
	    if(isset($_POST['Identification_bands'])) { $Identification_bands = '1'; }
	    if(isset($_POST['Loose_teeth'])) { $Loose_teeth = '1'; } 
	    if(isset($_POST['Hearing_adis'])) { $Hearing_adis= '1'; } 
	    if(isset($_POST['Pre_operative_skin'])) { $Pre_operative_skin= '1'; }
	    if(isset($_POST['Valuable_securely'])) { $Valuable_securely = '1'; }
	    if(isset($_POST['Theatre_gowns'])) { $Theatre_gowns= '1'; }
	    if(isset($_POST['Care_patient_case'])) { $Care_patient_case= '1'; }
	    if(isset($_POST['Oral_hygiene'])) { $Oral_hygiene = '1'; } 
	    if(isset($_POST['Catheter'])) { $Catheter = '1'; } 
	    if(isset($_POST['Patient_having'])) { $Patient_having= '1'; }
	    if(isset($_POST['Check_list'])) { $Check_list = '1'; }
	    if(isset($_POST['Test_for_VDRL'])) { $Test_for_VDRL = '1'; }
	    if(isset($_POST['Test_for_Hopatitis'])) { $Test_for_Hopatitis= '1'; }
	

	    $Patient_Idenified_Remark = mysqli_real_escape_string($conn,$_POST['Patient_Idenified_Remark']);
	    $Urine_passed_Remark = mysqli_real_escape_string($conn,$_POST['Urine_passed_Remark']);
	    $Dentures_removed_Remark = mysqli_real_escape_string($conn,$_POST['Dentures_removed_Remark']);
	    $Contact_lenses_Remark = mysqli_real_escape_string($conn,$_POST['Contact_lenses_Remark']);
	    $Jowerly_removed_Remark = mysqli_real_escape_string($conn,$_POST['Jowerly_removed_Remark']);
	    $Cosmetic_and_Clothing_Remark = mysqli_real_escape_string($conn,$_POST['Cosmetic_and_Clothing_Remark']);
	    $Consent_form_signed_Remark = mysqli_real_escape_string($conn,$_POST['Consent_form_signed_Remark']);
	    $Enema_or_laxative_Remark = mysqli_real_escape_string($conn,$_POST['Enema_or_laxative_Remark']);
	    $Other_prosthesis_Remark = mysqli_real_escape_string($conn,$_POST['Other_prosthesis_Remark']);
	    $Special_order_Remark =mysqli_real_escape_string($conn,$_POST['Special_order_Remark']);
	    $Operative_site_Remark =mysqli_real_escape_string($conn,$_POST['Operative_site_Remark']);
	    $Radiographs_accompanying_Remark =mysqli_real_escape_string($conn,$_POST['Radiographs_accompanying_Remark']);
	    $Test_for_HIV_Remark =mysqli_real_escape_string($conn,$_POST['Test_for_HIV_Remark']);
	    $Identification_bands_Remark =mysqli_real_escape_string($conn,$_POST['Identification_bands_Remark']);
	    $Loose_teeth_Remark =mysqli_real_escape_string($conn,$_POST['Loose_teeth_Remark']);
	    $Hearing_adis_Remark =mysqli_real_escape_string($conn,$_POST['Hearing_adis_Remark']);
	    $Pre_operative_skin_Remark =mysqli_real_escape_string($conn,$_POST['Pre_operative_skin_Remark']);
	    $Valuable_securely_Remark =mysqli_real_escape_string($conn,$_POST['Valuable_securely_Remark']);
	    $Theatre_gowns_Remark =mysqli_real_escape_string($conn,$_POST['Theatre_gowns_Remark']);
	    $Care_patient_case_Remark =mysqli_real_escape_string($conn,$_POST['Care_patient_case_Remark']);
	    $Oral_hygiene_Remark =mysqli_real_escape_string($conn,$_POST['Oral_hygiene_Remark']);
	    $Catheter_Remark =mysqli_real_escape_string($conn,$_POST['Catheter_Remark']);
	    $Patient_having_Remark =mysqli_real_escape_string($conn,$_POST['Patient_having_Remark']);
	    $Check_list_Remark =mysqli_real_escape_string($conn,$_POST['Check_list_Remark']);
	    $Test_for_VDRL_Remark =mysqli_real_escape_string($conn,$_POST['Test_for_VDRL_Remark']);
	    $Test_for_Hopatitis_Remark =mysqli_real_escape_string($conn,$_POST['Test_for_Hopatitis_Remark']);
	    $Special_Information = mysqli_real_escape_string($conn,$_POST['Special_Information']);
	    $Theatre_Time = mysqli_real_escape_string($conn,$_POST['Date_From']);
	    $Theatre_Nurse = mysqli_real_escape_string($conn,$_POST['Theatre_Nurse']);
	    $Ward_Nurse=mysqli_real_escape_string($conn,$_POST['Ward_Nurse']);

		//insert data into tbl_pre_operative_checklist
		$insert_sql = mysqli_query($conn,"insert into tbl_pre_operative_checklist(
									Employee_ID, Registration_ID, consultation_ID, Admision_ID, Theatre_Time,
									Ward_Signature, Theatre_Signature, Special_Information, Operative_Date_Time)
								values('$Employee_ID','$Registration_ID','$consultation_ID','$Admision_ID','$Theatre_Time',
									'$Ward_Nurse','$Theatre_Nurse','$Special_Information',(select now()))") or die(mysqli_error($conn));
		
		//get the last 	Pre_Operative_ID based on employee and patient ids
		$select_ids = mysqli_query($conn,"select Pre_Operative_ID from tbl_pre_operative_checklist where
									Employee_ID = '$Employee_ID' and
									Registration_ID = '$Registration_ID' order by Pre_Operative_ID DESC LIMIT 1") or die(mysqli_error($conn));
											
		$no = mysqli_num_rows($select_ids);
		if($no > 0){
			while($row = mysqli_fetch_array($select_ids)){
				$Pre_Operative_ID = $row['Pre_Operative_ID'];
			}
		}else{
			$Pre_Operative_ID = 'NULL';
		}
			
		//insert data into tbl_pre_operative_checklist_items
		$insert2 = mysqli_query($conn,"insert into tbl_pre_operative_Remarks(
									Pre_Operative_ID, Patient_Idenified_Remark, Urine_passed_Remark, Dentures_removed_Remark, Contact_lenses_Remark,
									Jowerly_removed_Remark, Cosmetic_and_Clothing_Remark, Consent_form_signed_Remark, Enema_or_laxative_Remark,
									Other_prosthesis_Remark, Special_order_Remark, Operative_site_Remark, Radiographs_accompanying_Remark,
									Test_for_HIV_Remark, Identification_bands_Remark, Loose_teeth_Remark, Hearing_adis_Remark,
									Pre_operative_skin_Remark, Valuable_securely_Remark, Theatre_gowns_Remark, Care_patient_case_Remark,
									Oral_hygiene_Remark, Catheter_Remark, Patient_having_Remark, Check_list_Remark,
									Test_for_VDRL_Remark, Test_for_Hopatitis_Remark)

								values('$Pre_Operative_ID','$Patient_Idenified_Remark','$Urine_passed_Remark','$Dentures_removed_Remark','$Contact_lenses_Remark',
										'$Jowerly_removed_Remark','$Cosmetic_and_Clothing_Remark','$Consent_form_signed_Remark','$Enema_or_laxative_Remark',
										'$Other_prosthesis_Remark','$Special_order_Remark','$Operative_site_Remark','$Radiographs_accompanying_Remark',
										'$Test_for_HIV_Remark','$Identification_bands_Remark','$Loose_teeth_Remark','$Hearing_adis_Remark',
										'$Pre_operative_skin_Remark','$Valuable_securely_Remark','$Theatre_gowns_Remark','$Care_patient_case_Remark',
										'$Oral_hygiene_Remark','$Catheter_Remark','$Patient_having_Remark','$Check_list_Remark',
										'$Test_for_VDRL_Remark','$Test_for_Hopatitis_Remark')") or die(mysqli_error($conn));
		if($insert2){
			$insert3 = mysqli_query($conn,"insert into tbl_pre_operative_checklist_items(
									Pre_Operative_ID, Patient_Identified_Name, Urine_passed, Dentures_removed, Contact_lenses, 
									Jowerly_removed, Cosmetic_and_Clothing, Consent_form_signed, Enema_or_laxative, Other_prosthesis,
									Special_order, Operative_site, Radiographs_accompanying, Test_for_HIV,
									Identification_bands, Loose_teeth, Hearing_adis, Pre_operative_skin,
									Valuable_securely, Theatre_gowns, Care_patient_case, Oral_hygiene,
									Catheter, Patient_having, Check_list, Test_for_VDRL, Test_for_Hopatitis)

									values('$Pre_Operative_ID', '$Patient_Identified_Name', '$Urine_passed', '$Dentures_removed', '$Contact_lenses', 
									'$Jowerly_removed', '$Cosmetic_and_Clothing', '$Consent_form_signed', '$Enema_or_laxative', '$Other_prosthesis',
									'$Special_order', '$Operative_site', '$Radiographs_accompanying', '$Test_for_HIV',
									'$Identification_bands', '$Loose_teeth', '$Hearing_adis', '$Pre_operative_skin',
									'$Valuable_securely', '$Theatre_gowns', '$Care_patient_case', '$Oral_hygiene',
									'$Catheter', '$Patient_having', '$Check_list', '$Test_for_VDRL', '$Test_for_Hopatitis')") or die(mysqli_error($conn));
			if($insert3){
				echo "<script type='text/javascript'>
						alert('Pre Operative Information Saved Successfully');
						document.location = 'nursecommunicationpage.php?Registration_ID=".$Registration_ID."&Admision_ID=".$Admision_ID."&consultation_ID=".$consultation_ID."&NurseCommunication=NurseCommunicationThisPage';
						//document.location = 'previewpreorerative.php?Registration_ID=".$Registration_ID."&Admision_ID=".$Admision_ID."&consultation_ID=".$consultation_ID."&PreviewPreOperative=PreviewPreOperativeThisPage';
					</script>";				
			}else{
				echo "<script type='text/javascript'>
						alert('Process fail!! Please try again.');
					</script>";
			}
		}

		/*if(!mysqli_query($conn,$insert_sql)){
	?>
	
					
		    <script type='text/javascript'>
			    var save=confirm('Do you want to fill VitalSigns?');
				if(save == true){
			    document.location = './Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&Vital=NurseThisPage';
				}
			    </script>";
				
				
	<?php
	}*/
}
?>


<script>
    $('#Start_Date').datetimepicker({
	    dayOfWeekStart : 1,
	    lang:'en',
    });
    $('#Start_Date').datetimepicker({value:'',step:01});
</script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/select2.min.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>

<script>
    $(document).ready(function () {
        $("#Save_Buttons").dialog({autoOpen: false, width: '40%', height: 140, title: 'eHMS 2.0 : CONFIRM', modal: true});
        $("#Previous_Records").dialog({autoOpen: false, width: '85%', height: 400, title: 'eHMS 2.0 : Previous Pre Operatives ~ <?php echo ucwords(strtolower($Patient_Name)); ?>', modal: true});
        $('select').select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>