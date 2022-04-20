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
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
		
    }
?>


<a href='searchPatients.php?Patients=PatientReport' class='art-button-green'>
    BACK
</a>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
		}
		}
?>
    
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Admission_Works'] == 'yes'){ 
?>
    
<?php  } } ?>


	
	<!--new date function (Contain years, Months and days)--> 
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
                $current_Employee_Name = $_SESSION['userinfo']['Employee_Name'];
            }else{
                $current_Employee_Name = 'Unknown';
            }
        }

?>

<?php

if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = '';
}

if (isset($_GET['consultation_id'])) {
    $consultation_ID = $_GET['consultation_id'];
} else {
    $consultation_ID = '';
}

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"SELECT Registration_ID,
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
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Patient_Picture = $row['Patient_Picture'];
				$Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Occupation = $row['Occupation'];
                $Email_Address = $row['Email_Address'];
				$Guarantor_Name = $row['Guarantor_Name'];
				
                
				
                
               }
			   
	$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
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
                $Employee_ID = '';
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
	
		$last_checklist = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' ORDER BY Pre_Operative_ID DESC LIMIT 1"))['Pre_Operative_ID'];
		if($last_checklist > 0){
			echo "<a target='_blank' href='Checklist_Form.php?Registration_ID=".$Registration_ID."&Admision_ID=".$Admision_ID."&consultation_ID=".$consultation_ID."&Pre_Operative_ID=".$last_checklist."' class='art-button-green'>
			PREVIOUS REPORT
		</a>";
		}

?>
 
<br/>


<br/>
<?php

$admission_details = mysqli_query($conn, "SELECT hw.Hospital_Ward_Name, wr.room_name, ad.Bed_Name FROM tbl_admission ad, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE ad.Registration_ID = '$Registration_ID' AND ad.Admission_Status = 'Admitted' AND wr.ward_room_id = ad.ward_room_id AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID ORDER BY ad.Admision_ID DESC LIMIT 1");
echo '<center>';

echo "<b>".ucwords(strtolower($Patient_Name))."</b>";
				echo " | ";
				echo "<b>".$Registration_ID."</b>";
				echo " | ";
				echo "<b>".$Gender."</b>";
				echo " | ";
				echo $age;
				echo " | ";
				echo "<b>".$Guarantor_Name."</b>";

echo '<br/>';

if(mysqli_num_rows($admission_details) > 0){
	while($wodini = mysqli_fetch_assoc($admission_details)){
		$Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
		$room_name = $wodini['room_name'];
		$Bed_Name = $wodini['Bed_Name'];


		echo "Ward: <b>".$Hospital_Ward_Name." </b> - Room/Bed Number: <b>".$room_name."/".$Bed_Name."</b>
		";
	}
}else{
	echo "<span style='font-size: 18px; color: red; font-weight: bold'>THE PATIENT WAS NOT ADMITTED IN THE SYSTEM</span>";
}

 echo '<br/>';

 $surgery_details = mysqli_query($conn, "SELECT i.Product_Name, sd.Sub_Department_Name, em.Employee_Name FROM tbl_item_list_cache ilc, tbl_items i, tbl_employee em, tbl_sub_department sd WHERE i.Item_ID = ilc.Item_ID and ilc.Status IN ('active','paid') AND ilc.Check_In_Type = 'Surgery' AND em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

while($ops = mysqli_fetch_assoc($surgery_details)){
	$Product_Name = $ops['Product_Name'];
	$Sub_Department_Name = $ops['Sub_Department_Name'];
	$Employee_Name = $ops['Employee_Name'];


	echo "Operation: <b>".$Product_Name." </b> - Surgeon: <b>".$Employee_Name."</b> - Location/Theatre: <b>".$Sub_Department_Name."</b>
	</center>";
}
?>



<fieldset style='overflow-y: scroll; height:450px;'>
    <legend align=right><b>PRE OPERATIVE CHECKLIST</b></legend>
 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <center>
    <table style="text-align:center; width:100%">

    <tr>
	<td style="text-align:center;" colspan='6' ><b>CHECK TASK WHICH ARE COMPLETE AND UNCHEK WHICH ARE NOT COMPLETE</B></td>
    </tr>
    

     <tr>
	<td style="text-align:center; width: 5%;" ><b>SN</b></td>
	<td style="text-align:center; width: 25%;" ><b>TASK</b></td>
	<td style="text-align:center; width: 5%;" ><b>CHECK</b></td>
	<td style="text-align:center; width: 15%;" ><b>REMARKS</b></td>
	<td style="text-align:center; width: 5%;" ><b>SN</b></td>
	<td style="text-align:center; width: 25%;" ><b>TASK</b></td>
	<td style="text-align:center; width: 5%;" ><b>CHECK</b></td>
	<td style="text-align:center; width: 15;" ><b>REMARKS</b></td>
    </tr>
                      
		      
		      
     <tr>
	 <td style="text-align:right;">1</td>
	 <td >Is the patient Well Prepared for Operation?</td>
	 <td><input type='radio' id='checkbox1' name='checkbox1' value='yes'>Yes
	 	<input type='radio' id='checkbox1' name='checkbox1' value='no'>No</td>
	 <td><input type='text' id='remark1' name='remark1'></td>
        
	 <td style="text-align:right;">10</td>
	 <td>Patient Fasted?</td>
	 <td><input type='radio' id='checkbox14' name='checkbox14' value='yes'>Yes
	 	<input type='radio' id='checkbox14' name='checkbox14' value='no'>No</td>
     <td><input type='text' id='remark14' name='remark14'></td>
     </tr>
               
    
    
    <tr>
	<td style="text-align:right;">2</td>
	<td>Had anaesthetic visit before?</td>
	<td><input type='radio' id='checkbox2' name='checkbox2' value='yes'>Yes
	<input type='radio' id='checkbox2' name='checkbox2' value='no'>No</td>
	<td><input type='text' name='remark2' id='remark2'></td>

	<td style="text-align:right;">11</td>
	<td>HB results present?</td>
	<td><input type='radio' id='checkbox15' name='checkbox15' value='yes'>Yes
		<input type='radio' id='checkbox15' name='checkbox15' value='no'>No</td>
    <td><input type='text' name='remark15' id='remark15'></td>
    </tr>
                
    
    
    <tr>
	<td style="text-align:right;">3</td>
	<td>Any allergy (if Yes, Mention)?</td>
	<td><input type='radio' id='checkbox3' name='checkbox3' value='yes'>Yes
		<input type='radio' id='checkbox3' name='checkbox3' value='no'>No</td>
	<td><input type='text' id='remark3' name='remark3'></td>
	
	<td style="text-align:right;">12</td>
	<td>Blood Grouping & X-Match done?</td>
	<td><input type='radio' id='checkbox16' name='checkbox16' value='yes'>Yes
		<input type='radio' id='checkbox16' name='checkbox16' value='no'>No</td>
    <td><input type='text' name='remark16' id='remark16'></td>
    </tr>
                
    <tr>
	<td style="text-align:right;">4</td>
	<td>Consent Signed?</td>
	<td><input type='radio' id='checkbox4' name='checkbox4' value='yes'>Yes
		<input type='radio' id='checkbox4' name='checkbox4' value='no'>No</td>
	<td><input type='text' id='remark4' name='remark4'></td>
	
	<td style="text-align:right;">13</td>
	<td>Removed all ornaments and dentures?</td>
	<td><input type='radio' id='checkbox17' name='checkbox17' value='yes'>Yes
		<input type='radio' id='checkbox17' name='checkbox17' value='no'>No</td>
    <td><input type='text' id='remark17' name='remark17' ></td>
    </tr>
                
   
   
    <tr>
	<td style="text-align:right;">5</td>
	<td>Has a theatre tag</td>
	<td><input type='radio' id='checkbox5' name='checkbox5' value='yes'>Yes
		<input type='radio' id='checkbox5' name='checkbox5' value='no'>No</td>
    <td><input type='text' id='remark5' name='remark5'></td>
	
	<td style="text-align:right;">14</td>
	<td>Accompanied with his/her X-Ray films?</td>
	<td><input type='radio' id='checkbox18' name='checkbox18' value='yes'>Yes
		<input type='radio' id='checkbox18' name='checkbox18' value='no'>No</td>
    <td><input type='text' id='remark18' name='remark18'></td>
    </tr>
                
	
	<tr>
	<td style="text-align:right;">6</td>
	<td>Operation site clearly marked?</td>
	<td><input type='radio' id='checkbox6' name='checkbox6' value='yes'>Yes
		<input type='radio' id='checkbox6' name='checkbox6' value='no'>No</td>
	<td><input type='text' id='remark6' name='remark6'></td>
	
	<td style="text-align:right;">15</td>
	<td>Accompanied with his/her CT-Scan films?</td>
	<td><input type='radio' id='checkbox19' name='checkbox19' value='yes'>Yes
		<input type='radio' id='checkbox19' name='checkbox19' value='no'>No</td>
    <td><input type='text' id='remark18' name='remark19'></td>
	</tr>
                
		
		
	<tr>
	<td style="text-align:right;">7</td>
	<td>Operation site well prepared?</td>
	<td><input type='radio' id='checkbox7' name='checkbox7' value='yes'>Yes
		<input type='radio' id='checkbox7' name='checkbox7' value='no'>No</td>
	<td><input type='text' id='remark7' name='remark7'></td>
	
	<td style="text-align:right;">16</td>
	<td>Accompanied with his/her US Results?</td>
	<td><input type='radio' id='checkbox20' name='checkbox20' value='yes'>Yes
		<input type='radio' id='checkbox20' name='checkbox20' value='no'>No</td>
    <td><input type='text' id='remark20' name='remark20'></td>
	</tr>
              
	      
	      
    <tr>
	<td style="text-align:right;">8</td>
	<td >Urinary Catheter Insitu?</td>
	<td><input type='radio' id='checkbox8' name='checkbox8' value='yes'>Yes
		<input type='radio' id='checkbox8' name='checkbox8' value='no'>No
	</td>
	<td><input type='text' id='remark8' name='remark8'></td>
     
	<td style="text-align:right;">17</td>
	<td>Laboratory results available?</td>
	<td><input type='radio' id='checkbox21' name='checkbox21' value='yes'>Yes
		<input type='radio' id='checkbox21' name='checkbox21' value='no'>No</td>
    <td><input type='text' id='remark21' name='remark21'></td>
	</tr>
                
				
    <tr>
	<td style="text-align:right;">9</td>
	<td>NGT Insitu? </td>
	<td> <input type='radio' id='checkbox9' name='checkbox9' value='yes'>Yes
		<input type='radio' id='checkbox9' name='checkbox9' value='no'>No</td>
	<td><input  id='remark9' name='remark9' type='text'></td>
     
	<td style="text-align:right;">18</td>
	<td>Is the patient on the operation list?</td>
	<td><input type='radio' id='checkbox22' name='checkbox22' value='yes'>Yes
		<input type='radio' id='checkbox22' name='checkbox22' value='no'>No</td>
    <td><input type='text' id='remark22' name='remark22'></td>
	</tr>
				   
    <tr>
		<td colspan='2' style="text-align:right;">List prepared By (Select Doctor name)</td>
		<td colspan='3'>
		<select style='width:100%;' name='doctor_list' id='doctor_list' placeholder='Select Theater Nurse' >
		<option selected='selected' value=''></option>
						<?php
					$data = mysqli_query($conn,"SELECT * from tbl_employee where Employee_Type = 'doctor'");
						while($row = mysqli_fetch_array($data)){
					?>
					<option value='<?php echo $row['Employee_Name'];?>'>
					<?php echo $row['Employee_Name']; ?>
					</option>
					<?php
						}
					?>
	</select>

		</td>
	</tr>
    
    <tr>
		<td colspan='2' rowspan='2' style="text-align:right;">Blood</td>
		<td>(a) How many units required?</td>
		<td colspan='2'><input type="text" class="blood" name='blood' placeholder='Units required'></td>
</tr>
<tr>
		<td>(b) How Many Units available?</td>
		<td colspan='2'>
			<input type="text" class="blood_available" name='blood_available' placeholder="Units available">
		</td>
	</tr>
    <tr >
    <td colspan='2' style="text-align:right;">Special Information(if any):</td>
    <td colspan='4'><textarea  name='Special_Information' id='Special_Information' ></textarea></td>
    </tr>
    
    <tr>
	<td colspan='2' style="text-align:right;">To Theatre At:</td>
	<td colspan='4'><input type='text' id='Theatre_Time' name='Theatre_Time' class='ehms_date_time' required='required'></td>
    </tr>
    <tr>
		<th colspan='8'>Handling Over</th>
	</tr>
    <tr>
    <td colspan='2' style="text-align:right;">Ward Nurse:</td>
	<td colspan='3'>
	<input type='text' style='width:100%;' name='Ward_Nurse' placeholder='Select Theater Nurse' value='<?php echo $current_Employee_Name ?>' readonly='readonly'>

	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
	<tr class='hide'>
		<td colspan='2' style="text-align:right;">Is the Patient Accepted In Theater</td>
		<td><input type="radio" name="acceptance" id="acceptance" value="Yes">&nbsp;&nbsp;Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="acceptance" id="acceptance2" value="No">&nbsp;&nbsp;No
		</td>
		<td><b>If Not:</b> What are the Instruction</td>
		<td colspan='2'><input type="text" placeholder="What Are the Instruction" name="instruction" id="instruction" class="instruction" style="display: none; width: 100%;"></td>

	</tr>

	<tr>
	<td colspan='2' style="text-align:right;">Name of Handling over Nurse/Porter :</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td colspan='3'>
	<input type='text' style='width:100%;' name='Handling_nurse' id='Handling_nurse' placeholder='Enter Handling over Nurse / Portal' required='required'>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
		
	<tr class='hide'>
	<td colspan='2' style="text-align:right;">Theatre Nurse :</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td colspan='3'>
	<select style='width:100%;' name='Theatre_Nurse' id='Theatre_Nurse' placeholder='Select Theater Nurse' >
		<option selected='selected' value=''></option>
						<?php
					$data = mysqli_query($conn,"SELECT * from tbl_employee where Employee_Type LIKE '%nurse%'");
						while($row = mysqli_fetch_array($data)){
					?>
					<option value='<?php echo $row['Employee_Name'];?>'>
					<?php echo $row['Employee_Name']; ?>
					</option>
					<?php
						}
					?>
	</select>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
	<tr  class='hide'>
	<td colspan='2' style="text-align:right;">Receiving Nurse/Recovery Nurse:</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td colspan='3'>
	<select style='width:100%;' name='recovery_nurse' id='recovery_nurse' placeholder='Select Theater Nurse' >
		<option selected='selected' value=''></option>
						<?php
					$data = mysqli_query($conn,"SELECT * from tbl_employee where Employee_Type LIKE '%nurse%'");
						while($row = mysqli_fetch_array($data)){
					?>
					<option value='<?php echo $row['Employee_Name'];?>'>
					<?php echo $row['Employee_Name']; ?>
					</option>
					<?php
						}
					?>
	</select>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
    
   
   </table>
   </fieldset>

<div style='text-align:right;width:100%;'>
	<input style='width:25%;'  type='submit' name='Save' id='Save' class='art-button-green' value='SAVE'>
           <input type='hidden' name='submittedProActiveCheckList' value='true'/>
</div>	
        
   </form>
   </center>
		
<?php
if(isset($_POST['submittedProActiveCheckList'])){
	
	    $checkboxvalue1 = 'Is the Patient Well Prepared for Operation?';
	    $checkboxvalue2 = 'Had anaesthetic visit before?';
	    $checkboxvalue3 = 'Any Allergy (If yes, Mention)';
	    $checkboxvalue4 = 'Consent Signed';
	    $checkboxvalue5 = 'Has a theatre tag?'; 
	    $checkboxvalue6 = 'Operation site clearly marked';
	    $checkboxvalue7 = 'Operation site well prepared';
	    $checkboxvalue8 ='Urinary Catheter Insitu';
	    $checkboxvalue9 ='NGT Insitu';

	    $checkboxvalue14='Patient Fasted';
	    $checkboxvalue15='HB results present';
	    $checkboxvalue16='Blood Grouping & X-match done';
	    $checkboxvalue17='Removed all ornament and dentures';
	    $checkboxvalue18='Accompanied with his/her X-Ray films';
	    $checkboxvalue19='Accompanied with his/her CT-Scan films';
	    $checkboxvalue20='Accompanied with his/her US Results';
	    $checkboxvalue21='Laboratory Results available';
	    $checkboxvalue22 ='Is the patient on the Operation list?';

	 
		$checkbox1 = mysqli_real_escape_string($conn,$_POST['checkbox1']);
	    $checkbox2 = mysqli_real_escape_string($conn,$_POST['checkbox2']);
	    $checkbox3 = mysqli_real_escape_string($conn,$_POST['checkbox3']);
	    $checkbox4 = mysqli_real_escape_string($conn,$_POST['checkbox4']);
	    $checkbox5 = mysqli_real_escape_string($conn,$_POST['checkbox5']);
	    $checkbox6 = mysqli_real_escape_string($conn,$_POST['checkbox6']);
	    $checkbox7 = mysqli_real_escape_string($conn,$_POST['checkbox7']);
	    $checkbox8 = mysqli_real_escape_string($conn,$_POST['checkbox8']);
	    $checkbox9 = mysqli_real_escape_string($conn,$_POST['checkbox9']);
	    $checkbox14 =mysqli_real_escape_string($conn,$_POST['checkbox14']);
	    $checkbox15 =mysqli_real_escape_string($conn,$_POST['checkbox15']);
	    $checkbox16 =mysqli_real_escape_string($conn,$_POST['checkbox16']);
	    $checkbox17 =mysqli_real_escape_string($conn,$_POST['checkbox17']);
	    $checkbox18 =mysqli_real_escape_string($conn,$_POST['checkbox18']);
	    $checkbox19 =mysqli_real_escape_string($conn,$_POST['checkbox19']);
	    $checkbox20 =mysqli_real_escape_string($conn,$_POST['checkbox20']);
	    $checkbox21 =mysqli_real_escape_string($conn,$_POST['checkbox21']);
	    $checkbox22 =mysqli_real_escape_string($conn,$_POST['checkbox22']);
	 
	 
	    $remark1 = mysqli_real_escape_string($conn,$_POST['remark1']);
	    $remark2 = mysqli_real_escape_string($conn,$_POST['remark2']);
	    $remark3 = mysqli_real_escape_string($conn,$_POST['remark3']);
	    $remark4 = mysqli_real_escape_string($conn,$_POST['remark4']);
	    $remark5 = mysqli_real_escape_string($conn,$_POST['remark5']);
	    $remark6 = mysqli_real_escape_string($conn,$_POST['remark6']);
	    $remark7 = mysqli_real_escape_string($conn,$_POST['remark7']);
	    $remark8 = mysqli_real_escape_string($conn,$_POST['remark8']);
	    $remark9 = mysqli_real_escape_string($conn,$_POST['remark9']);
	    $remark14 =mysqli_real_escape_string($conn,$_POST['remark14']);
	    $remark15 =mysqli_real_escape_string($conn,$_POST['remark15']);
	    $remark16 =mysqli_real_escape_string($conn,$_POST['remark16']);
	    $remark17 =mysqli_real_escape_string($conn,$_POST['remark17']);
	    $remark18 =mysqli_real_escape_string($conn,$_POST['remark18']);
	    $remark19 =mysqli_real_escape_string($conn,$_POST['remark19']);
	    $remark20 =mysqli_real_escape_string($conn,$_POST['remark20']);
	    $remark21 =mysqli_real_escape_string($conn,$_POST['remark21']);
	    $remark22 =mysqli_real_escape_string($conn,$_POST['remark22']);

	    $Special_Information = mysqli_real_escape_string($conn,$_POST['Special_Information']);
	    $Theatre_Time = mysqli_real_escape_string($conn,$_POST['Theatre_Time']);
	    $Theatre_Nurse = mysqli_real_escape_string($conn,$_POST['Theatre_Nurse']);
	    $Ward_Nurse=mysqli_real_escape_string($conn,$_POST['Ward_Nurse']);
	    $Special_Information=mysqli_real_escape_string($conn,$_POST['Special_Information']);
	    $doctor_list=mysqli_real_escape_string($conn,$_POST['doctor_list']);
	    $Handling_nurse=mysqli_real_escape_string($conn,$_POST['Handling_nurse']);
	    $recovery_nurse=mysqli_real_escape_string($conn,$_POST['recovery_nurse']);
	    $acceptance=mysqli_real_escape_string($conn,$_POST['acceptance']);
	    $blood_available=mysqli_real_escape_string($conn,$_POST['blood_available']);
	    $blood=mysqli_real_escape_string($conn,$_POST['blood']);
	    $instruction=mysqli_real_escape_string($conn,$_POST['instruction']);
	 	$Employee_Name = mysqli_real_escape_string($conn,str_replace("'", "&#39;", $Employee_Name));
	 
	
		// insert data into tbl_pre_operative_checklist
		$insert_sql = mysqli_query($conn,"INSERT into tbl_pre_operative_checklist(Employee_ID,Registration_ID,Theatre_Time,Ward_Signature,Theatre_Signature,Special_Information,Operative_Date_Time,doctor_list,Handling_nurse,recovery_nurse,acceptance,blood_available,blood,instruction,surgery,surgeon, Admision_ID, consultation_ID, Payment_Item_Cache_List_ID)
											values('$Employee_ID','$Registration_ID','$Theatre_Time','$Ward_Nurse','$Theatre_Nurse','$Special_Information',(select now()),'$doctor_list', '$Handling_nurse', '$recovery_nurse', '$acceptance', '$blood_available', '$blood', '$instruction', '$Product_Name', '$Employee_Name', '$Admision_ID', '$consultation_ID','$Payment_Item_Cache_List_ID')") or die(mysqli_error($conn));
		
		//get the last 	Pre_Operative_ID based on employee and patient ids
		$select_ids = mysqli_query($conn,"SELECT Pre_Operative_ID from tbl_pre_operative_checklist where
										Employee_ID = '$Employee_ID' and
											Registration_ID = '$Registration_ID' order by Pre_Operative_ID DESC LIMIT 1") or die(mysqli_error($conn));
											
			$no = mysqli_num_rows($select_ids);
			if($no > 0){
				while($row = mysqli_fetch_array($select_ids)){
					$Pre_Operative_ID = $row['Pre_Operative_ID'];
				}
			}else{
				$Pre_Operative_ID = 0;
			}
			
		//insert data into tbl_pre_operative_checklist_items
		$i = 1;
		$checkboxvalue = 'checkboxvalue';
		$checkbox = 'checkbox';
		$remark = 'remark';
		while($i < 26){
			$Temp_Name = ${$checkboxvalue.$i}; //$checkboxvalue1
			$Temp_Value = ${$checkbox.$i};
			$Temp_Remark = ${$remark.$i};

			$insert_sql = mysqli_query($conn,"INSERT into tbl_pre_operative_checklist_items(
								Task_Name,Task_Value,Remark,Pre_Operative_ID)
								values('$Temp_Name','$Temp_Value','$Temp_Remark','$Pre_Operative_ID')") or die(mysqli_error($conn));
			$i++;
		}
		if($insert_sql){
	?>
	
					
		    <script type='text/javascript'>
			    var save=confirm('Do you want to fill VitalSigns?');
				if(save == true){
			    // document.location = './Pre_Operative_VitalSigns.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital=NurseThisPage';
				}else{
					// location('./searchPatients.php?Patients=PatientReport');

				}
			    </script>";
				
				
	<?php			echo "Pre-operative Checklist was saved Successfully!";
					header("location:./searchPatients.php?Patients=PatientReport");
	}
}
?>
		
<?php
    include("./includes/footer.php");
?>

<script>
    $(document).ready(function (e){
        $("#Ward_Nurse").select2();
        $("#Theatre_Nurse").select2();
        $("#recovery_nurse").select2();
		$("#doctor_list").select2();
    });
</script>
<script>
var radio = document.getElementById('acceptance2');
var radio2 = document.getElementById('acceptance');
radio.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    if(this.checked){
        month.style.display='inline';
    }else{
        month.style.display='none';
    }
})
radio2.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    if(this.checked){
        month.style.display='none';
    }else{
        month.style.display='inline';
    }
})
</script>

<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('.ehms_date_time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('.ehms_date_time').datetimepicker({value: '', step: 01});
</script>
