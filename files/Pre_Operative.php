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

if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = '';
}

if (isset($_GET['consultation_id'])) {
    $consultation_ID = $_GET['consultation_id'];
} else {
    $consultation_ID = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['Payment_Item_Cache_List_ID'])) {
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
} else {
    $Payment_Item_Cache_List_ID = '';
}

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

//    select patient information to perform check in process
	
$last_checklist = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND (Admision_ID = '$Admision_ID' OR consultation_ID = '$consultation_ID') AND acceptance = '' ORDER BY Pre_Operative_ID DESC LIMIT 1"))['Pre_Operative_ID'];
$Last_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Pre_Operative_ID FROM tbl_pre_operative_checklist WHERE Registration_ID = '$Registration_ID' AND (Admision_ID = '$Admision_ID' OR consultation_ID = '$consultation_ID') AND acceptance  <> '' ORDER BY Pre_Operative_ID DESC LIMIT 1"))['Pre_Operative_ID'];

if($Last_ID > 0){
	echo "<a href='Checklist_Form.php?Registration_ID=".$Registration_ID."&Admision_ID=".$Admision_ID."&consultation_ID=".$consultation_ID."&Pre_Operative_ID=".$Last_ID."' class='art-button-green' target='blank'>
    PREVIOUS REPORT
</a>";
}
?>
 
<br/>


<br/>
<?php

$admission_details = mysqli_query($conn, "SELECT hw.Hospital_Ward_Name, wr.room_name, ad.Bed_Name FROM tbl_admission ad, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE ad.Admision_ID = '$Admision_ID' AND wr.ward_room_id = ad.ward_room_id AND hw.Hospital_Ward_ID = ad.Hospital_Ward_ID");
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

 while($wodini = mysqli_fetch_assoc($admission_details)){
	 $Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
	 $room_name = $wodini['room_name'];
	 $Bed_Name = $wodini['Bed_Name'];


	 echo "Ward: <b>".$Hospital_Ward_Name." </b> - Room/Bed Number: <b>".$room_name."/".$Bed_Name."</b>
	 ";
 }

 echo '<br/>';

$surgery_details = mysqli_query($conn, "SELECT i.Product_Name, sd.Sub_Department_Name, em.Employee_Name FROM tbl_item_list_cache ilc, tbl_items i, tbl_employee em, tbl_sub_department sd WHERE em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND sd.Sub_Department_ID = ilc.Sub_Department_ID AND ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'");

while($ops = mysqli_fetch_assoc($surgery_details)){
	$Product_Name = $ops['Product_Name'];
	$Sub_Department_Name = $ops['Sub_Department_Name'];
	$Employee_Name = $ops['Employee_Name'];


	echo "Operation: <b>".$Product_Name." </b> - Surgeon: <b>".$Employee_Name."</b> - Location/Theatre: <b>".$Sub_Department_Name."</b>
	</center>";
}

?>



<fieldset style='overflow-y: scroll; height:500px;'>
    <legend align=right><b>PRE OPERATIVE CHECKLIST</b></legend>
 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <center>
    <table style="text-align:center; width:70%">

    <tr>
	<td style="text-align:center;" colspan='4' ><b>CHECK TASK WHICH ARE COMPLETE AND UNCHEK WHICH ARE NOT COMPLETE</B></td>
    </tr>
    

     <tr>
	<td style="text-align:center; width: 1%;" ><b>SN</b></td>
	<td style="text-align:center; width: 25%;" ><b>TASK</b></td>
	<td style="text-align:center; width: 5%;" ><b>CHECK</b></td>
	<td style="text-align:center; width: 15%;" ><b>REMARKS</b></td>
<?php


$preoperative_item_list = mysqli_query($conn, "SELECT Task_Name, Task_Value, Remark FROM tbl_pre_operative_checklist_items WHERE Pre_Operative_ID = '$last_checklist' AND Task_Name <> ''");

$operative_details = mysqli_query($conn, "SELECT * FROM tbl_pre_operative_checklist  WHERE Pre_Operative_ID = '$last_checklist'");
	while($surgical = mysqli_fetch_assoc($operative_details)){
		$doctor_list = $surgical['doctor_list'];
		$Theatre_Time = $surgical['Theatre_Time'];
		$Ward_Signature = $surgical['Ward_Signature'];
		$surgeon = $surgical['surgeon'];
		$blood_available = $surgical['blood_available'];
		$blood = $surgical['blood'];
		$Special_Information = $surgical['Special_Information'];
		$Handling_nurse = $surgical['Handling_nurse'];
	}
	$num = 1;
	$num2 = 9;
	while($rows = mysqli_fetch_array($preoperative_item_list)){
		$task_Name = $rows['Task_Name'];
		$task_value = $rows['Task_Value'];
		$Remarkss = $rows['Remark'];

		echo "<tr>
					<td style='text-align: center; font-weight: bold;'>$num</td>
					<td>$task_Name</td>
					<td style='text-align: center; font-weight: bold;'>$task_value</td>
					<td>$Remarkss</td>";
		$num++;
		$num2++;
	}

?>
    
    
    <tr>
		<td style="text-align:right;">List prepared By (Selected Doctor name)</td>
		<td>
		<input type='text' style='width:100%; font-weight: bold;' name='doctor_list' id='doctor_list' value='<?php echo $doctor_list; ?>' placeholder='Select Theater Nurse' readonly='readonly' >
		</td>
	</tr>
    
    <tr>
		<td rowspan='2' style="text-align:right;">Blood</td>
		<td>(a) How many units required?</td>
		<td colspan='2'><input type="text" class="blood" name='blood' placeholder='Units required' value='<?php echo $blood; ?>' readonly='readonly' ></td>
</tr>
<tr>
		<td>(b) How Many Units available?</td>
		<td colspan='2'>
			<input type="text" class="blood_available" name='blood_available' placeholder="Units available"  value='<?php echo $blood_available; ?>' readonly='readonly' >
		</td>
	</tr>
    <tr >
    <td style="text-align:right;">Special Requirements(if any):</td>
    <td colspan='3'><textarea  name='Special_Information' id='Special_Information' readonly='readonly'><?php echo $Special_Information; ?></textarea></td>
    </tr>
    
    <tr>
	<td style="text-align:right;">To Theatre At:</td>
	<td><input type='text' id='Theatre_Time' name='Theatre_Time'  value='<?php echo $Theatre_Time; ?>' readonly='readonly'></td>
    </tr>
   
    <tr>
    <td style="text-align:right;">Ward Nurse:</td>
	<td>
	<input type='text' style='width:100%;' name='Ward_Nurse' id='Ward_Nurse'  value='<?php echo $Ward_Signature; ?>' readonly='readonly'>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
	<tr>
		<th colspan='4'>TAKING OVER IN OT</th>
	</tr>
	<tr>
		<td style="text-align:right;">Is the Patient Accepted In Theater</td>
		<td><input type="radio" name="acceptance" id="acceptance" value="Yes">&nbsp;&nbsp;Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="acceptance" id="acceptance2" value="No">&nbsp;&nbsp;No
		</td>
		<td><b>If Not:</b> What are the Instruction</td>
		<td><input type="text" placeholder="What Are the Instruction" name="instruction" id="instruction" class="instruction" style="display: none; width: 100%;"></td>

	</tr>

	<tr>
	<td style="text-align:right;">Name of Handling over Nurse/Porter :</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td>
	<?php
		if(!empty($Handling_nurse)){
	echo "<input type='text' style='width:100%;' name='Theatre_Nurse' placeholder='Select Theater Nurse' value='".$Handling_nurse."' readonly='readonly'>";
	
		}else{
	?>
	<select style='width:100%;' name='Handling_nurse' id='Handling_nurse' placeholder='Select Theater Nurse' >
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
	<?php } ?>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>
		
	<tr>
	<td style="text-align:right;">Theatre Nurse :</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td>
	<input type='text' style='width:100%;' name='Theatre_Nurse' placeholder='Select Theater Nurse' value='<?php echo $current_Employee_Name ?>' readonly='readonly'>
	</td>
	
    <td class='hide'><input type='text' id='barcode' name='barcode'  placeholder='Barcode Number'></td>
	<td class='hide'><input type='button' value='VERIFY' class='art-button-green'></td>
    </tr>

	<td style="text-align:right;">Receiving Nurse/Recovery Nurse:</td>
	<!--<td colspan='4'><input type='text' id='Theatre_Signature' name='Theatre_Signature' disabled='disabled' ></td>-->
    <td>
	<input type='text' style='width:100%;' name='recovery_nurse' id='recovery_nurse' placeholder='Enter Receiving Nurse/Recovery Nurse' >
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
	$checkbox1 = 'no';
	$checkbox2 = 'no';
	$checkbox3 = 'no';
	$checkbox4 = 'no';
	$checkbox5 = 'no'; 
	$checkbox6 = 'no';
	$checkbox7 = 'no';
	$checkbox8 = 'no';
	$checkbox9 = 'no';
	// $checkbox10 = 'no'; 
	// $checkbox11 = 'no';
	// $checkbox12 = 'no';
	// $checkbox13 = 'no';
	$checkbox14 = 'no';
	$checkbox15 = 'no'; 
	$checkbox16 = 'no';
	$checkbox17 = 'no';
	$checkbox18 = 'no'; 
	$checkbox19 = 'no';
	$checkbox20 = 'no';
	$checkbox21 = 'no';
	$checkbox22 = 'no';
	// $checkbox23 = 'no'; 
	// $checkbox24 = 'no';
	// $checkbox25 = 'no'; 
	// $checkbox26 = 'no';
   
	 
	 
	    if(isset($_POST['checkbox1'])) { $checkbox1= 'yes'; }
	    if(isset($_POST['checkbox2'])) { $checkbox2 = 'yes'; }
	    if(isset($_POST['checkbox3'])) { $checkbox3 = 'yes'; }
	    if(isset($_POST['checkbox4'])) { $checkbox4 = 'yes'; }
	    if(isset($_POST['checkbox5'])) { $checkbox5 = 'yes'; } 
	    if(isset($_POST['checkbox6'])) { $checkbox6= 'yes'; }
	    if(isset($_POST['checkbox7'])) { $checkbox7 = 'yes'; }
	    if(isset($_POST['checkbox8'])) { $checkbox8 = 'yes'; }
	    if(isset($_POST['checkbox9'])) { $checkbox9 = 'yes'; }
	    // if(isset($_POST['checkbox10'])) { $checkbox10 = 'yes'; } 
	    // if(isset($_POST['checkbox11'])) { $checkbox11= 'yes'; }
	    // if(isset($_POST['checkbox12'])) { $checkbox12 = 'yes'; }
	    // if(isset($_POST['checkbox13'])) { $checkbox13 = 'yes'; }
	    if(isset($_POST['checkbox14'])) { $checkbox14 = 'yes'; }
	    if(isset($_POST['checkbox15'])) { $checkbox15 = 'yes'; } 
	    if(isset($_POST['checkbox16'])) { $checkbox16= 'yes'; } 
	    if(isset($_POST['checkbox17'])) { $checkbox17= 'yes'; }
	    if(isset($_POST['checkbox18'])) { $checkbox18 = 'yes'; }
	    if(isset($_POST['checkbox19'])) { $checkbox19= 'yes'; }
	    if(isset($_POST['checkbox20'])) { $checkbox20= 'yes'; }
	    if(isset($_POST['checkbox21'])) { $checkbox21 = 'yes'; } 
	    if(isset($_POST['checkbox22'])) { $checkbox22 = 'yes'; } 
	    // if(isset($_POST['checkbox23'])) { $checkbox23= 'yes'; }
	    // if(isset($_POST['checkbox24'])) { $checkbox24 = 'yes'; }
	    // if(isset($_POST['checkbox25'])) { $checkbox25 = 'yes'; }
	    // if(isset($_POST['checkbox26'])) { $checkbox26= 'yes'; }
	
	    $checkboxvalue1 = 'Is the Patient Well Prepared for Operation?';
	    $checkboxvalue2 = 'Had anaesthetic visit before?';
	    $checkboxvalue3 = 'DAny Allergy(If yes, Mention)';
	    $checkboxvalue4 = 'Consent Signed';
	    $checkboxvalue5 = 'Has a theatre tag?'; 
	    $checkboxvalue6 = 'Operation site clearly marked';
	    $checkboxvalue7 = 'Operation site well prepared';
	    $checkboxvalue8 ='Urinary Catheter Insitu';
	    $checkboxvalue9 ='NGT Insitu';
	    // $checkboxvalue10 ='Are special order carried out, as Nasogastric tube';
	    // $checkboxvalue11 ='Operative site marked';
	    // $checkboxvalue12='Radiographs accompanying patient';
	    // $checkboxvalue13='Test for HIV'; 
	    $checkboxvalue14='Patient Fasted';
	    $checkboxvalue15='HB results present';
	    $checkboxvalue16='Blood Grouping & X-match done';
	    $checkboxvalue17='Removed all ornament and dentures';
	    $checkboxvalue18='Accompanied with his/her X-Ray films';
	    $checkboxvalue19='Accompanied with his/her CT-Scan films';
	    $checkboxvalue20='Accompanied with his/her US Results';
	    $checkboxvalue21='Laboratory Results available';
	    $checkboxvalue22 ='Is the patient on the Operation list?';
	    // $checkboxvalue23 ='Is patient having an i.v  catheter cannula(as 16,18)';
	    // $checkboxvalue24 = 'Check list complete';
	    // $checkboxvalue25 = 'Test for VDRL';
	    // $checkboxvalue26 ='Test for Hopatitis';
	 
	 
	 
	    $remark1 = mysqli_real_escape_string($conn,$_POST['remark1']);
	    $remark2 = mysqli_real_escape_string($conn,$_POST['remark2']);
	    $remark3 = mysqli_real_escape_string($conn,$_POST['remark3']);
	    $remark4 = mysqli_real_escape_string($conn,$_POST['remark4']);
	    $remark5 = mysqli_real_escape_string($conn,$_POST['remark5']);
	    $remark6 = mysqli_real_escape_string($conn,$_POST['remark6']);
	    $remark7 = mysqli_real_escape_string($conn,$_POST['remark7']);
	    $remark8 = mysqli_real_escape_string($conn,$_POST['remark8']);
	    $remark9 = mysqli_real_escape_string($conn,$_POST['remark9']);
	    // $remark10 =mysqli_real_escape_string($conn,$_POST['remark10']);
	    // $remark11 =mysqli_real_escape_string($conn,$_POST['remark11']);
	    // $remark12 =mysqli_real_escape_string($conn,$_POST['remark12']);
	    // $remark13 =mysqli_real_escape_string($conn,$_POST['remark13']);
	    $remark14 =mysqli_real_escape_string($conn,$_POST['remark14']);
	    $remark15 =mysqli_real_escape_string($conn,$_POST['remark15']);
	    $remark16 =mysqli_real_escape_string($conn,$_POST['remark16']);
	    $remark17 =mysqli_real_escape_string($conn,$_POST['remark17']);
	    $remark18 =mysqli_real_escape_string($conn,$_POST['remark18']);
	    $remark19 =mysqli_real_escape_string($conn,$_POST['remark19']);
	    $remark20 =mysqli_real_escape_string($conn,$_POST['remark20']);
	    $remark21 =mysqli_real_escape_string($conn,$_POST['remark21']);
	    // $remark22 =mysqli_real_escape_string($conn,$_POST['remark22']);
	    // $remark23 =mysqli_real_escape_string($conn,$_POST['remark23']);
	    // $remark24 =mysqli_real_escape_string($conn,$_POST['remark24']);
	    // $remark25 =mysqli_real_escape_string($conn,$_POST['remark25']);
	    // $remark26 =mysqli_real_escape_string($conn,$_POST['remark26']);
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
	 
	
		//insert data into tbl_pre_operative_checklist
		$insert_sql = mysqli_query($conn, "UPDATE tbl_pre_operative_checklist SET Theatre_Signature =  '$Theatre_Nurse', Handling_nurse = '$Handling_nurse', acceptance = '$acceptance', recovery_nurse = '$recovery_nurse', instruction = '$instruction' WHERE Pre_Operative_ID = '$last_checklist'") or die(mysqli_error($conn));
		
		if(!mysqli_query($insert_sql)){
	?>
	
					
		    <script type='text/javascript'>
					alert("Patient Received Successfully");
					document.location = './Checklist_Form.php?Pre_Operative_ID=<?php echo $last_checklist; ?>&consultation_ID=<?php echo $consultation_ID; ?>&Admision_ID=<?php echo $Admision_ID; ?>&Registration_ID=<?php echo $Registration_ID; ?>&Checklist_Form=Checklist_Form';
				
			    </script>";
				
				
	<?php
	}
}
?>
		
<?php
    include("./includes/footer.php");
?>

<script>
    $(document).ready(function (e){
        $("#Theatre_Nurse").select2();
        $("#Handling_nurse").select2();
        $("#recovery_nurse").select2();
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
